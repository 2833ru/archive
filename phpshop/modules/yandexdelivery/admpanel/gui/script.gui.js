function yadeliveryvalidate(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode( key );
    var regex = /[0-9]|\./;
    if( !regex.test(key) ) {
        theEvent.returnValue = false;
        if(theEvent.preventDefault) theEvent.preventDefault();
    }
}

$(document).ready(function () {
    $('#yandex_payment_status').click(function () {
        var value = 0;
        if($('#yandex_payment_status').prop('checked') === true) {
            value = 1;
        }

        $.ajax({
            mimeType: 'text/html; charset=' + locale.charset,
            url: '/phpshop/modules/yandexdelivery/ajax/admin.php',
            type: 'post',
            data: {
                operation: 'changePaymentStatus',
                value: value,
                orderId: $('input[name="yadelivery_order_id"]').val()
            },
            dataType: "json",
            async: false,
            success: function(json) {}
        });
    });

    $('.yadelivery-send').on('click', function () {
        $.ajax({
            mimeType: 'text/html; charset=' + locale.charset,
            url: '/phpshop/modules/yandexdelivery/ajax/admin.php',
            type: 'post',
            data: {
                operation: 'send',
                orderId: $('input[name="yadelivery_order_id"]').val()
            },
            dataType: "json",
            async: false,
            success: function(json) {
                if(json['success'] == false) {
                    $.MessageBox({
                        buttonDone: false,
                        buttonFail: locale.close,
                        input: false,
                        message: json['error']
                    })
                } else {
                    $.MessageBox({
                        buttonDone: false,
                        buttonFail: locale.close,
                        input: false,
                        message: 'Заказ успешно отправлен'
                    });
                    $('.yandex-status').html('Отправлен');
                    $('.yadelivery_actions').css('display', 'none');
                    $("#yadelivery-table input[type=checkbox]").attr('disabled', true);
                }
            }
        });
    });
});