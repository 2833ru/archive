function pochtaCalculate() {

    var index = $('input[name="index_new"]').val();
    var sum = $("#OrderSumma").val();
    var wsum = $("#WeightSumma").html();
    var xid = $("#d").val();

    $.ajax({
        url: ROOT_PATH + '/phpshop/ajax/delivery.php',
        type: 'post',
        data: 'type=json&xid=' + xid + '&sum=' + sum + '&wsum=' + (wsum/1000) + '&index=' + index,
        dataType: 'json',
        success: function(json) {
            if (json['success']) {
                $("#DosSumma").html(json['delivery']);
                $("#TotalSumma").html(json['total']);
                showAlertMessage(json['message']);
                $('input[name="city_new"]').val(json['city']);
                $('input[name="pochta_delivery_cost"]').remove();
                $('<input type="hidden" name="pochta_delivery_cost">').insertAfter('#dop_info');
                $('input[name="pochta_delivery_cost"]').val(json['delivery']);
            } else if (json['success'] === false) {
                $('input[name="index_new"]').val('');
                showAlertMessage(json['message']);
            }
        }
    });
}

$(document).ready(function() {

    $("#adres_id").change(function() {
        pochtaCalculate();
    }).change();


    $('body').on('change', 'input[name="index_new"]', function() {
        if ($('input[name="index_new"]').val() >= 6) {
            pochtaCalculate()
        }
    });
});