var path = window.location.pathname.split("/")[1];
var item;

$(function() {
    var url;

    if (path == "config") {
        url = "http://api.la-vendimia.tk/" + path;
    }
    else if (window.location.search) {
        var id = window.location.search.split("=")[1];

        url = "http://api.la-vendimia.tk/" + path + "/" + parseInt(id);
    }
    else {
        url = "http://api.la-vendimia.tk/" + path + "/new";
    }

    $.ajax({
        method: "GET",
        url: url
    }).done(function(response) {
        item = response;

        switch (path) {
            case "ventas":
                $("#folio").html(item.folio != null ? item.folio : "");
                break;
            case "clientes":
                $("#clave").html(item.clave != null ? item.clave : "");
                $("#nombre").val(item.nombre != null ? item.nombre : "");
                $("#paterno").val(item.paterno != null ? item.paterno : "");
                $("#materno").val(item.materno != null ? item.materno : "");
                $("#rfc").val(item.rfc != null ? item.rfc : "");
                break;
            case "articulos":
                $("#clave").html(item.clave != null ? item.clave : "");
                $("#descripcion").val(item.descripcion != null ? item.descripcion : "");
                $("#modelo").val(item.modelo != null ? item.modelo : "");
                $("#precio").val(item.precio != null ? item.precio : "");
                $("#existencia").val(item.existencia != null ? item.existencia : "");
                break;
            case "config":
                $("#tasa").val((item.tasa != null ? item.tasa : ""));
                $("#porcentaje_engancho").val((item.porcentaje_engancho != null ? item.porcentaje_engancho : ""));
                $("#plazo_max").val((item.plazo_max != null ? item.plazo_max : ""));
                break;
        }
    });
});

function addItem() {
    if (!validateForm()) {
        toastr.error("Por favor llene correctamente los datos marcados.");

        return;
    }

    switch (path) {
        case "ventas":
            var plazo = $("input[name='plazos']:checked").val();

            if (plazo == null) {
                toastr.error("Debe seleccionar un plazo para realizar el pago de su compra.");

                return;
            }

            item.cliente = idCliente;
            item.articulos = articulosAComprar;
            item.total = accounting.unformat($("#total").html());
            item.plazo = plazo;
            break;
        case "clientes":
            item.nombre = $("#nombre").val();
            item.paterno = $("#paterno").val();
            item.materno = $("#materno").val();
            item.rfc = $("#rfc").val();
            break;
        case "articulos":
            item.descripcion = $("#descripcion").val();
            item.modelo = $("#modelo").val();
            item.precio = $("#precio").val();
            item.existencia = $("#existencia").val();
            break;
        case "config":
            item.tasa = $("#tasa").val();
            item.porcentaje_engancho = $("#porcentaje_engancho").val();
            item.plazo_max = $("#plazo_max").val();
            break;
    }
    console.log(item);
    $.ajax({
        method: "POST",
        url: "http://api.la-vendimia.tk/" + path + "/register",
        data: item
    }).done(function(response) {
        if (response == true) {
            var msg;

            switch (path) {
                case "ventas":
                    msg = "Bien Hecho. La venta ha sido registrada correctamente.";
                    break;
                case "clientes":
                    msg = "Bien Hecho. El cliente ha sido registrado correctamente.";
                    break;
                case "articulos":
                    msg = "Bien Hecho. El articulo ha sido registrado correctamente.";
                    break;
                case "config":
                    msg = "Bien Hecho. La configuración ha sido registrada.";
                    break;
            }

            toastr.options.onHidden = changeView;
            toastr.success(msg);
        }
        else {
            toastr.error("Hubo un error al guardar, por favor intente mas tarde.");
        }
    });
}

function changeView() {
    window.location.href = "/" + (path != "config" ? path : "");
}

function validateForm() {
    var inputs = $(".form-input");
    var everythingOk = true;

    $.each(inputs, function(index, value) {
        var input = $(value);

        if(input.prop("id") == "rfc" && $.trim(input.val()).length < 13){
            if (!input.hasClass("error")) {
                input.addClass("error");

                toastr.info("El campo RFC debe ser 13 caracteres.");
            }

            everythingOk = false;
        }
        else if((input.prop("id") == "porcentaje_engancho" || input.prop("id") == "plazo_max") && input.val() == 0){
            if (!input.hasClass("error")) {
                input.addClass("error");
            }

            everythingOk = false;
        }
        else if((input.prop("id") == "precio" || input.prop("id") == "tasa") && input.val() == 0 || (input.val().match(/\./g) || []).length > 1){
            if (!input.hasClass("error")) {
                input.addClass("error");
            }

            everythingOk = false;
        }
        else if (input.prop("id") == "articulo" && $.trim(input.val()).length == 0) {
            if (!input.hasClass("error")) {
                input.addClass("error");
            }

            everythingOk = false;
        }
    });

    return everythingOk;
}

function onlyText(event) {
    var keyCode = event.which;

    if (!(keyCode >= 65 && keyCode <= 90) && keyCode != 8 && keyCode != 32 && keyCode != 9) {
        event.preventDefault();

        return;
    }

    if ($(event.target).hasClass("error")) {
        $(event.target).removeClass("error");
    }
}

function onlyTextAndNumber(event) {
    var keyCode = event.which;

    if (!(keyCode >= 65 && keyCode <= 90) && !(keyCode >= 48 && keyCode <= 57) && keyCode != 8 && keyCode != 32 && keyCode != 9) {
        event.preventDefault();

        return;
    }

    if ($(event.target).hasClass("error")) {
        $(event.target).removeClass("error");
    }
}

function onlyDecimalNumber(event) {
    var keyCode = event.which;

    if (!(keyCode >= 48 && keyCode <= 57) && keyCode != 8 && keyCode != 190 && keyCode != 9) {
        event.preventDefault();

        return;
    }

    if ($(event.target).hasClass("error")) {
        $(event.target).removeClass("error");
    }
}

function onlyNumber(event) {
    var keyCode = event.which;

    if (!(keyCode >= 48 && keyCode <= 57) && keyCode != 8 && keyCode != 9) {
        event.preventDefault();

        return;
    }

    if ($(event.target).hasClass("error")) {
        $(event.target).removeClass("error");
    }
}

function toUpperCase(field) {
    field.value = field.value.toUpperCase();
}