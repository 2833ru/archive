function ddeliveryprodwidgetStart() {
    // Инициализация виджета
    var widget = new DDeliveryWidgetCard("dd-widget-card", {
        apiScript: "/phpshop/modules/ddeliverywidget/api/dd-widget-api.php",
		userFullName: $('input[name="fio_new"]').val(),
		userPhone: $('input[name="tel_new"]').val(),
    });

    $("#ddeliverywidgetModal").modal("toggle");
}