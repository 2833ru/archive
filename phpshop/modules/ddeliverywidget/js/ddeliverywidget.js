function ddeliverywidgetStart() {
    $('input[name="ddeliveryReq"]').remove();

    var products = $.parseJSON($("input:hidden.cartListJson").val());
    if(!$('input').is('[name="ddeliverySum"]')) { $('<input type="hidden" name="ddeliverySum" id="ddeliverySum">').insertAfter('#d'); }
    if(!$('input').is('[name="ddeliveryReq"]')) { $('<input type="hidden" name="ddeliveryReq" class="req form-control">').insertAfter('#dop_info'); }
    if(!$('input').is('[name="ddeliveryDop"]')) { $('<input type="hidden" name="ddeliveryDop">').insertAfter('#dop_info'); }
    if(!$('input').is('[name="ddeliveryData"]')) { $('<input type="hidden" name="ddeliveryData">').insertAfter('#dop_info'); }

    var widget = new DDeliveryWidgetCart("dd-widget", {
        apiScript: "/phpshop/modules/ddeliverywidget/api/dd-widget-api.php",
        products: products,
        weight: $("#ddweight").val(), 
        //stopSubmit: true
    });
    
    //widget.on("error", function (errors) {
        // Вызовется при возникновении ошибок при обработке запроса,
        // при передаче в виджет некорректных или неполных данных
        //console.error(errors);
    //}); 
    
    widget.on("afterSubmit", function (response) {
    // Вызовется при получении ответа от сервера DDelivery
        
        $('<input type="hidden" name="ddeliveryToken" value="' + response.id + '">').insertAfter('#d');
        $('input[name="ddeliveryReq"]').val($('input[name="ddeliveryDop"]').val());
        $('#ddelivery-close').text('Продолжить').addClass('btn-success');
    });       
    
    widget.on("beforeSubmit", function (data) {
        if(typeof data.delivery!=="undefined") {
            if(typeof data.delivery.point!=="undefined") {
                var info = 'ПВЗ: ' + data.delivery.point.delivery_company_name;
                info = info + ', срок: ' + data.delivery.point.delivery_date;
                info = info +', адрес ПВЗ: ' + data.city.name + ' ' + data.delivery.point.address;
                info = info + ', телефон: ' + data.contacts.phone;  
                var del_sum = data.delivery.point.price_delivery;
            } else {
                var info = 'Доставка: ' + data.delivery.delivery_company_name;
                info = info + ', срок: ' + data.delivery.delivery_date;
                info = info + ', адрес: ' + data.contacts.address.index + ' ' + data.city.name + ' ул.' + data.contacts.address.street + ' д.' + data.contacts.address.house + ' кв.' + data.contacts.address.flat;
                info = info + ', телефон: ' + data.contacts.phone;  
                var del_sum = data.delivery.total_price;
            }
            $('input[name="ddeliveryDop"]').val(info);
            $('#deliveryInfo').html(info);

            $("#DosSumma").html(del_sum);
            $("#TotalSumma").html(Number(del_sum) + Number($('#OrderSumma').val()));
            $('#ddeliverySum').val(del_sum);
        }

        $('input[name="name_new"]').val(data.contacts.fullName);
		$('input[name="fio_new"]').val(data.contacts.fullName); 
		if(data.contacts.phone) $('input[name="tel_new"]').val(data.contacts.phone.substring(1)); 
        $('input[name="city_new"]').val(data.city.name);
        $('input[name="flat_new"]').val(data.contacts.address.flat);
        $('input[name="house_new"]').val(data.contacts.address.house);
        $('input[name="street_new"]').val(data.contacts.address.street);
        $('input[name="index_new"]').val(data.contacts.address.index);
        
        $('input[name="ddeliveryData"]').val(JSON.stringify(data, null, 0));
        
        // Hook
        if ($.isFunction(window.ddeliverywidgetHook))
            ddeliverywidgetHook(data);
    });      

    $("#ddeliverywidgetModal").modal("toggle");
}

/*
function ddeliverywidgetHook(data) {
    
    // Блокировка по городу
    if (data.city.name != 'Москва') {

        // блокировка способов оплат
        var paymentStop = '3'; // ИД оплаты через запятую
        if (paymentStop !== undefined)
            var payment_array = paymentStop.split(",");

        $('input[name="order_metod"]').each(function() {
            $(this).attr('disabled', false);
        });

        if ($.isArray(payment_array)) {
            $.each(payment_array, function(index, value) {
                $('input[data-option="payment' + value + '"]').attr('disabled', true);
                $('input[data-option="payment' + value + '"]').attr('checked', false);
            });
        }

        if ($("input#order_metod:checked").length == 0) {
            $('input#order_metod').each(function() {
                if (!this.disabled) {
                    this.checked = true;
                    return false;
                }
            });
        }
    }
}*/