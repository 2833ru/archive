function boxberrywidgetStart() {
    var api_token = $('#boxberryApiKey').val();
    var city = $('#boxberryCity').val();
    var weight = $('#boxberryCartWeight').val();
    var cartSum = $('#OrderSumma').val();
    var depth = $('#boxberryCartDepth').val();
    var height = $('#boxberryCartHeight').val();
    var width = $('#boxberryCartWidth').val();
    boxberry.open('boxberryWidget', api_token, city, '', cartSum, weight, 0, height, width, depth);
}

function boxberryWidget(result) {

    var info = 'Код выбранного ПВЗ: ' + result.id + ', город ' + result.name + ', адрес выбранного ПВЗ ' + result.address + ', телефон выбранного ПВЗ ' + result.phone;
    var boxberry_sum = result.price;

    $('input[name="boxberryInfo"]').val(info);
    $('input[name="boxberry_pvz_id"]').val(result.id);
    $('input[name="DeliverySum"]').val(boxberry_sum);

    $("#DosSumma").html(boxberry_sum);
    $("#TotalSumma").html(Number(boxberry_sum) + Number($('#OrderSumma').val()));


    $('input[name="city_new"]').val(result.name);
    $('#deliveryInfo').html('ПВЗ: ' + result.address);
}

function boxberrywidgetCourier() {

    var zip = $('input[name="index_new"]').val();
    var sum = $("#OrderSumma").val();
    var xid = $("#d").val();
    var weight = $('#boxberryCartWeight').val();
    var depth = $('#boxberryCartDepth').val();
    var height = $('#boxberryCartHeight').val();
    var width = $('#boxberryCartWidth').val();
    if (zip !== '') {

        $.ajax({
            url: ROOT_PATH + '/phpshop/ajax/delivery.php',
            type: 'post',
            data: 'type=json&xid=' + xid + '&sum=' + sum + 'weight=' + weight + '&depth=' + depth + '&height=' + height + '&width=' + width + '&zip=' + zip,
            dataType: 'json',
            success: function(json) {
                if (json['success'] == 'index') {
                    $("#DosSumma").html(json['delivery']);
                    $("#TotalSumma").html(json['total']);
                    showAlertMessage(json['message']);

                    $('input[name="DeliverySum"]').val(json['delivery']);
                    $('input[name="boxberryInfo"]').val('Курьерская доставка Boxberry по индексу ' + zip);

                    $('#deliveryInfo').html('Курьерская доставка Boxberry по индексу ' + zip);
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
    $('body').on('change', 'input[name="index_new"]', function() {
        boxberrywidgetCourier();
    });
});