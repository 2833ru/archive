function boxberrywidgetStart() {
    var api_token = $('#boxberryApiKey').val();
    var city = $('#boxberryCity').val();
    var weight = $('#boxberryCartWeight').val();
    var cartSum = $('#OrderSumma').val();
    var depth = $('#boxberryCartDepth').val();
    var height = $('#boxberryCartHeight').val();
    var width = $('#boxberryCartWidth').val();
    $('<input type="hidden" name="boxberrySum" id="boxberrySum">').insertAfter('#d');
    $('<input type="hidden" name="boxberryInfo" class="req form-control">').insertAfter('#dop_info');
    $('<input type="hidden" name="boxberry_pvz_id_new" class="req form-control">').insertAfter('#dop_info');
    boxberry.open('boxberryWidget', api_token, city, '', cartSum, weight, 0, height, width, depth);
}

function boxberryWidget(result) {

    var info = 'Код выбранного ПВЗ: ' + result.id + ', город ' + result.name + ', адрес выбранного ПВЗ ' + result.address + ', телефон выбранного ПВЗ ' + result.phone;
    var boxberry_sum = result.price;

    $('input[name="boxberryInfo"]').val(info);
    $('input[name="boxberry_pvz_id_new"]').val(result.id);

    $("#DosSumma").html(boxberry_sum);
    $("#TotalSumma").html(Number(boxberry_sum) + Number($('#OrderSumma').val()));
    $('#boxberrySum').val(boxberry_sum);

    $('input[name="city_new"]').val(result.name);
    $('#deliveryInfo').html('ПВЗ: ' + result.address);
}

function boxberrywidgetReset() {
    $('input[name="boxberryInfo"]').remove();
    $('input[name="boxberrySum"]').remove();
}