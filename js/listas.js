$(function() {
    $location = window.location.pathname;

    $.ajax({
        method: "GET",
        url: "http://api.la-vendimia.tk" + $location
    }).done(addRows);
});

function addRows(dataArray) {
    if (dataArray.length > 0) {
        $.each(dataArray, function(index, value) {

            switch (window.location.pathname) {
                case "/ventas":
                    $(".lista")
                        .append($('<tr>')
                            .attr("class", "row")
                            .append($('<td>')
                                .attr("class", "row-td id")
                                .html(padLeft(value.folio, 4))
                            )
                            .append($('<td>')
                                .attr("class", "row-td")
                                .html(padLeft(value.cliente, 4))
                            )
                            .append($('<td>')
                                .attr("class", "row-td")
                                .html(value.nombre + " " + value.paterno + " " + value.materno)
                            )
                            .append($('<td>')
                                .attr("class", "row-td")
                                .html(accounting.formatMoney(value.total))
                            )
                            .append($('<td>')
                                .attr("class", "row-td")
                                .html(value.fecha)
                            )
                        );
                    break;
                case "/clientes":
                    $(".lista")
                        .append($('<tr>')
                            .attr("class", "row")
                            .append($('<td>')
                                .attr("class", "row-td id")
                                .html(padLeft(value.clave, 4))
                            )
                            .append($('<td>')
                                .attr("class", "row-td")
                                .html(value.nombre + " " + value.paterno + " " + value.materno)
                            )
                            .append($('<td>')
                                .attr("class", "row-td")
                                .html($('<button>')
                                    .attr("class", "edit-btn")
                                    .html($('<img>')
                                        .attr('src', "/img/ic_edit.png")
                                    )
                                    .click(editItem)
                                )
                            )
                        );
                    break;
                case "/articulos":
                    $(".lista")
                        .append($('<tr>')
                            .attr("class", "row")
                            .append($('<td>')
                                .attr("class", "row-td id")
                                .html(padLeft(value["clave"], 4))
                            )
                            .append($('<td>')
                                .attr("class", "row-td")
                                .html(value["descripcion"])
                            )
                            .append($('<td>')
                                .attr("class", "row-td")
                                .html($('<button>')
                                    .attr("class", "edit-btn")
                                    .html($('<img>')
                                        .attr('src', "/img/ic_edit.png")
                                    )
                                    .click(editItem)
                                )
                            )
                        );
                    break;
            }

        });
    }
    else {
        if (window.location.pathname != "/ventas") {
            $(".lista")
                .append($('<tr>')
                    .append($('<td>')
                    )
                    .append($('<td>')
                    )
                    .append($('<img>')
                    )
                );
        }
        else {
            $(".lista")
                .append($('<tr>')
                    .append($('<td>')
                    )
                    .append($('<td>')
                    )
                    .append($('<td>')
                    )
                    .append($('<td>')
                    )
                    .append($('<td>')
                    )
                );
        }
    }

}

function addItem() {
    window.location.pathname = window.location.pathname + "/agregar";
}

function editItem(event) {
    var itemId = $(event.target).closest("tr").find("td.id").html();

    window.location.href = window.location.pathname + "/editar?itemId=" + itemId;
}