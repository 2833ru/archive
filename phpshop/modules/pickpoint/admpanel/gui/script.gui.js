function pickpointvalidate(evt) {
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
    if (typeof $('#body').attr('data-token') !== 'undefined' && $('#body').attr('data-token').length)
        var DADATA_TOKEN = $('#body').attr('data-token');
    if (DADATA_TOKEN !== false && DADATA_TOKEN !== undefined) {
        $("input[name='city_from_new']").suggestions({
            token: DADATA_TOKEN,
            type: 'ADDRESS',
            hint: false,
            bounds: "city-settlement",
            onSelect: function (response) {
                $("input[name='city_from_new']").val(response.data.city);
                $("input[name='region_from_new']").val(response.data.region);
            }
        });
    }

    $('body').on('click', 'input[name="pickpoint_payment_status"]', function() {
        var value;
        if($('#pickpoint_payment_status').prop('checked') === true) {
            value = 1;
        }
        if($('#pickpoint_payment_status').prop('checked') === false) {
            value = 0;
        }

        $.ajax({
            mimeType: 'text/html; charset='+locale.charset,
            url: '/phpshop/modules/pickpoint/ajax/admin.php',
            type: 'post',
            data: {
                operation: 'changePaymentStatus',
                value: value,
                orderId: $('input[name="pickpoint_order_id"]').val()
            },
            dataType: "json",
            async: false,
            success: function(json) {}
        });
    });
});