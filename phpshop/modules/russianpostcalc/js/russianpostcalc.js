
function rusianpostCheck() {

    var index = $('input[name="index_new"]').val();
    var sum = $("#OrderSumma").val();
    var wsum = $("#WeightSumma").html();
    var xid = $("#d").val();

    if (index >= 6) {

        $.ajax({
            url: ROOT_PATH + '/phpshop/ajax/delivery.php',
            type: 'post',
            data: 'type=json&xid=' + xid + '&sum=' + sum + '&wsum=' + (wsum/1000) + '&index=' + index,
            dataType: 'json',
            success: function(json) {
                if (json['success'] == 'index') {
                    $("#DosSumma").html(json['delivery']);
                    $("#TotalSumma").html(json['total']);
                    showAlertMessage(json['message']);
                    $('input[name="city_new"]').val(json['city']);
                }
                else if (json['success'] == 'indexError') {
                    $('input[name="index_new"]').val('');
                    showAlertMessage(json['message']);
                }
            }
        });

    }
}



$(document).ready(function() {

    $("#adres_id").change(function() {
        rusianpostCheck();
    }).change();


    $('body').on('change', 'input[name="index_new"]', function() {
        rusianpostCheck();
    });

});


