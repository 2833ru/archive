function ozonrocketvalidate(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode(key);
    var regex = /[0-9]|\./;
    if (!regex.test(key)) {
        theEvent.returnValue = false;
        if (theEvent.preventDefault)
            theEvent.preventDefault();
    }
}

$(document).ready(function () {


    if (Number($.getUrlVar('tab')) === 4) {
        $('a[href="#tabs-4"]').tab('show');
    }

    // Блокировка
    $('body').on('change', '#hide_pvz_new', function () {
        if ($(this).prop('checked') === true) {
            $('#hide_postamat_new').bootstrapToggle('off');
        }
    });

    // Блокировка
    $('body').on('change', '#hide_postamat_new', function () {
        if ($(this).prop('checked') === true) {
            $('#hide_pvz_new').bootstrapToggle('off');
        }
    });

    // Изменение статуса оплаты
    $('#payment_status').on('change', function () {
        var paymentStatus = 0;
        if ($('#payment_status').prop('checked')) {
            paymentStatus = 1;
        }
        $.ajax({
            mimeType: 'text/html; charset=' + locale.charset,
            url: '../modules/ozonrocket/ajax/ajax.php',
            type: 'post',
            data: {
                operation: 'paymentStatus',
                value: paymentStatus,
                orderId: $('input[name="ozonrocket_order_id"]').val()
            },
            dataType: "json",
            async: false,
            success: function (json) {
                if (json['success']) {
                } else {
                    console.log(json['error'])
                }
            }
        });
    });

    $('.ozonrocket-change-address').on('click', function () {
        OzonRocketWidgetStart();
    });

    $('.ozonrocket-send').on('click', function () {
        $.ajax({
            mimeType: 'text/html; charset=' + locale.charset,
            url: '../modules/ozonrocket/ajax/ajax.php',
            type: 'post',
            data: {
                operation: 'send',
                orderId: $('input[name="ozonrocket_order_id"]').val()
            },
            dataType: "json",
            async: false,
            success: function (json) {
                if (json['success']) {
                    if (Number($.getUrlVar('tab')) !== 4) {
                        window.location.href += '&tab=4';
                    } else {
                        location.reload();
                    }
                } else {
                    console.log(json['error'])
                }
            }
        });
    });

})

window.addEventListener("message", adminReceiveMessage, false);
function adminReceiveMessage(event)
{
    if (event.origin !== "https://rocket.ozon.ru" && event.origin !== "https://rocket-demo.ozonru.me")
        return;
    var data = JSON.parse(event.data);

    if (data["price"] > 0)
        ozonRocketChangeAddress(data);
}

function ozonRocketChangeAddress(data)
{
    $.ajax({
        mimeType: 'text/html; charset=' + locale.charset,
        url: '../modules/ozonrocket/ajax/ajax.php',
        type: 'post',
        data: {
            operation: 'changeAddress',
            address: data['address'],
            orderId: $('input[name="ozonrocket_order_id"]').val(),
            cost: data['price'],
            delivery_id: data['id'],
            delivery_type: data['type']
        },
        dataType: "json",
        async: false,
        success: function (json) {
            if (json['success']) {
                if (Number($.getUrlVar('tab')) !== 4) {
                    window.location.href += '&tab=4';
                } else {
                    location.reload();
                }
            } else {
                console.log(json['error'])
            }
        }
    });
}