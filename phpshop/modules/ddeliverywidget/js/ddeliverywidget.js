function ddeliverywidgetStart() {

    var products = $.parseJSON($("input:hidden.cartListJson").val());
    $('<input type="hidden" name="ddeliverySum">').insertAfter('#d');
    $('<input type="hidden" name="ddeliveryReq" class="req form-control">').insertAfter('#dop_info');
    DDeliveryWidget.init('widget', {
        products: products,
        id: $("#ddeliveryId").val(),
        width: 500,
        height: 550,
        env: DDeliveryWidget.ENV_PROD
    }, {
        change: function(data) {

            $('<input type="hidden" name="ddeliveryToken" value="' + data['client_token'] + '">').insertAfter('#d');

            $("#DosSumma").html(data['client_price']);
            $("#TotalSumma").html(Number(data['client_price']) + Number($('#OrderSumma').val()));
            $('input[name="ddeliverySum"').val(data['client_price']);

            $('input[name="city_new"]').val(data['city_name']).attr('disabled', 'true');
            $('input[name="flat_new"]').val(data['to_flat']).attr('disabled', 'true');
            $('input[name="house_new"]').val(data['to_house']).attr('disabled', 'true');
            $('input[name="street_new"]').val(data['to_street']).attr('disabled', 'true');
            $('input[name="ddeliveryReq"]').val(data['info']);
            $('#deliveryInfo').html(data['info']);
            $('#ddelivery-close').text('Продолжить').addClass('btn-success');

            // Hook
            if ($.isFunction(window.ddeliverywidgetHook))
                ddeliverywidgetHook(data);
            /*
             setTimeout(function() {
             $("#ddeliverywidgetModal").modal("hide");
             }, 3000);*/
        }
    });

    $("#ddeliverywidgetModal").modal("toggle");
}


function ddeliverywidgetReset() {
    $('input[name="ddeliveryReq"]').remove();
}

/*
function ddeliverywidgetHook(data) {
    
    // Блокировка по городу
    if (data['city_name'] != 'Москва') {

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