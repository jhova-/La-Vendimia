var articulosAComprar = [];
var articulosEnLista = [];
var idCliente;
var tasa, porcentaje_engancho, plazo_max;

$(function() {
    $.ajax({
        method: "GET",
        url: "/api/src/index.php/config"
    }).done(function(response) {
        if (response.tasa == "" || response.porcentaje_engancho == "" || response.plazo_max == "") {
            toastr.options.onHidden = function() {
                window.location.pathname = "/config";
            };

            $("#cliente").prop("disabled", true);
            $("#articulo").prop("disabled", true);
            $("#add-btn").prop("onclick", false);

            toastr.error("Paracer no haber llenado correctamente la configuracion general, sera trasladado a configuracion general. Le pedimos llenar correctamente los datos.");

            return;
        }

        tasa = response.tasa;
        porcentaje_engancho = response.porcentaje_engancho;
        plazo_max = response.plazo_max;
    });
});

function addArticulos() {
    var articulo = $("#articulo");
    var articuloId = $("option[value='" + articulo.val() + "']").attr("id");

    if (articuloId == null) {
        toastr.error("Favor de seleccionar un articulo valido.");

        return;
    }

    $.ajax({
        method: "GET",
        url: "/api/src/index.php/articulos/" + articuloId
    }).done(function(response) {
        if (response.existencia > 0) {
            var itemExits = false;

            $.each(articulosEnLista, function(index, value) {
                if (value.clave == response.clave) {
                    toastr.info("El articulo ya existe el carito de compra.");

                    itemExits = true;
                    return;
                }
            });

            if (itemExits) {
                articulo.val("");
                $("#articulos").empty();

                return;
            }

            articulosEnLista.push(response);
            articulosAComprar.push({ "clave": articuloId, "cantidad": 1 });

            var precio = response.precio * (1 + (tasa * plazo_max) / 100);
            precio = accounting.formatMoney(precio, "");

            $(".lista-articulos")
                .append($("<tr>")
                    .append($("<td>")
                        .attr("class", "row-td")
                        .html(response.descripcion)
                    )
                    .append($("<td>")
                        .attr("class", "row-td")
                        .html(response.modelo)
                    )
                    .append($("<td>")
                        .attr("class", "row-td")
                        .append($("<input>")
                            .attr("type", "text")
                            .attr("class", "cantidad")
                            .attr("id", "cantidad")
                            .keydown(onlyNumber)
                            .change(cambiarCantidad)
                            .val(1)
                        )
                    )
                    .append($("<td>")
                        .attr("class", "row-td")
                        .attr("id", "precio")
                        .html(precio)
                    )
                    .append($("<td>")
                        .attr("class", "row-td importe")
                        .attr("id", "importe")
                        .html(precio)
                    )
                    .append($("<td>")
                        .attr("class", "row-td row-td-center")
                        .html($('<button>')
                            .attr("type", "button")
                            .attr("class", "delete-btn")
                            .html($('<img>')
                                .attr('src', "/img/ic_clear.png")
                            )
                            .click(removeItem)
                        )
                    )
                );

            calcularVenta();

            if ($("#detalles-plazo").hasClass("show")) {
                calcularPlazos();
            }
        }
        else {
            toastr.error("El artículo seleccionado no cuenta con existencia, favor de verificar.");
        }

        articulo.val("");
        $("#articulos").empty();
    });
}

function calcularVenta() {
    var detalles_venta = $("#detalles-venta");
    var content_btns = $("#content-btns");

    if (articulosAComprar.length > 0 && articulosEnLista.length > 0) {
        if (!detalles_venta.hasClass("show")) {
            detalles_venta.addClass("show");
            content_btns.addClass("show");
        }
    }
    else {
        if (detalles_venta.hasClass("show")) {
            detalles_venta.removeClass("show");
            content_btns.removeClass("show");
        }

        return;
    }

    var importe_total = 0;

    $(".importe").each(function(index, value) {
        importe_total += accounting.unformat($(value).html());
    });

    var enganche_total = calcularEnganche(importe_total);
    var bonificacion_enganche = calcularBonificacionEnganche(enganche_total);
    var adeudo_total = calcularAdeudoTotal(importe_total, enganche_total, bonificacion_enganche);

    $("#enganche").html(accounting.formatMoney(enganche_total, ""));
    $("#bonificacion").html(accounting.formatMoney(bonificacion_enganche, ""));
    $("#total").html(accounting.formatMoney(adeudo_total, ""));
}

function calcularEnganche(importe_total) {
    return (porcentaje_engancho / 100) * importe_total;
}

function calcularBonificacionEnganche(enganche_total) {
    return enganche_total * ((tasa * plazo_max) / 100);
}

function calcularAdeudoTotal(importe_total, enganche_total, bonificacion_enganche) {
    return importe_total - enganche_total - bonificacion_enganche;
}

function calcularPlazos() {
    if ($.trim($("#cliente").val()) == "") {
        toastr.error("Por favor selecione un cliente antes de continuar");

        return;
    }

    var detalles_plazo = $("#detalles-plazo");
    var content_btns_ok = $("#content-btn-ok");

    if (articulosAComprar.length > 0 && articulosEnLista.length > 0) {
        if (!detalles_plazo.hasClass("show")) {
            detalles_plazo.addClass("show");
            content_btns_ok.text("Guardar");
            content_btns_ok.attr("onclick", "addItem()");
        }
    }
    else {
        if (detalles_plazo.hasClass("show")) {
            detalles_plazo.removeClass("show");
            content_btns_ok.text("Siguiente");
            content_btns_ok.attr("onclick", "calcularPlazos()");
        }

        toastr.error("Debe tener minimo un articulo en el carrito de compra para continuar.");

        return;
    }

    var adeudo_total = accounting.unformat($("#total").html());
    var precio_contado = adeudo_total / (1 + ((tasa * plazo_max) / 100));

    var totales = calcularTotalesDePlazo(precio_contado);
    calcularAbonos(totales);
    calcularAhorros(adeudo_total, totales);
}

function calcularTotalesDePlazo(precio_contado) {
    var totales = [];
    var totalLabels = $(".total");

    for (var i = 0, plazo = 3; i < totalLabels.length; i++ , plazo += 3) {
        var total = precio_contado * (1 + (tasa * plazo) / 100);

        $(totalLabels[i]).html(accounting.formatMoney(total));

        totales.push(total);
    }

    return totales;
}

function calcularAbonos(totales) {
    var abonoLabels = $(".abono");

    for (var i = 0, plazo = 3; i < abonoLabels.length; i++ , plazo += 3) {
        var abono = totales[i] / plazo;

        $(abonoLabels[i]).html(accounting.formatMoney(abono));
    }
}

function calcularAhorros(adeudo_total, totales) {
    var ahorroLabels = $(".ahorro");

    ahorroLabels.each(function(index, value) {
        var ahorro = adeudo_total - totales[index];

        $(value).html(accounting.formatMoney(ahorro));
    });
}

function buscarCliente(event, cliente) {
    if ((event.which >= 48 && event.which <= 90 || event.which == 32 || event.which == 8) && cliente.length >= 3) {
        $.ajax({
            method: "POST",
            url: "/api/src/index.php/clientes/find",
            data: {
                cliente: cliente
            }
        }).done(function(response) {
            $("#clientes").empty();

            $.each(response, function(index, value) {
                $("#clientes")
                    .append($("<option>")
                        .val(padLeft(value.clave, 4) + " - " + value.nombre + " " + value.paterno + " " + value.materno)
                        .attr("id", value.clave)
                        .attr("name", value.rfc)
                    );
            });
        });
    }
}

function seleccionarCliente(input) {
    idCliente = $("option[value='" + input + "']").attr("id");

    var rfc = $("option[value='" + input + "']").attr("name");

    $("#rfc").html(rfc);

    if (rfc != null) {
        $("#rfc-container").addClass("show-inline");
    }
    else {
        $("#rfc-container").removeClass("show-inline");
    }
}

function buscarArticulo(event, articulo) {
    if ((event.which >= 48 && event.which <= 90 || event.which == 32 || event.which == 8) && articulo.length >= 3) {
        $.ajax({
            method: "POST",
            url: "/api/src/index.php/articulos/find",
            data: {
                articulo: articulo
            }
        }).done(function(response) {
            $("#articulos").empty();

            $.each(response, function(index, value) {
                $("#articulos")
                    .append($("<option>")
                        .val(value.descripcion + " - " + value.modelo)
                        .attr("id", value.clave)
                    );
            });
        });
    }
}

function cambiarCantidad(event) {
    var cantidadInput = $(event.target);
    var cantidad = cantidadInput.val();
    var row = $(event.target).closest("tr");
    var rowIndex = row.index();

    if (cantidad <= parseInt(articulosEnLista[rowIndex].existencia)) {
        var precio = accounting.unformat(row.find("td#precio").html());

        var importe = cantidad * precio;

        row.find("td#importe").html(accounting.formatMoney(importe, ""));

        articulosAComprar[rowIndex].cantidad = cantidad;

        calcularVenta();

        if ($("#detalles-plazo").hasClass("show")) {
            calcularPlazos();
        }
    }
    else {
        toastr.error("Esta excediendo la cantidad de exitencia del articulo.");

        cantidadInput.val(articulosEnLista[rowIndex].existencia);
        cantidadInput.change();
    }
}

function removeItem(event) {
    var row = $(event.target).closest("tr");
    var rowIndex = row.index();

    row.remove();

    articulosAComprar.splice(rowIndex, 1);
    articulosEnLista.splice(rowIndex, 1);

    calcularVenta();

    if ($("#detalles-plazo").hasClass("show")) {
        calcularPlazos();
    }
}