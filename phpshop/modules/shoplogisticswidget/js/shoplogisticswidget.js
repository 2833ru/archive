$(document).ready(function () {
    $("form[name='forma_order']").on('change', "select[name='city_new']",  function() {
        var key = $('#shoplogisticsKey').val();
        var weight = $('#shoplogisticsCartWeight').val();
        var cartSum = $('#OrderSumma').val();
        var lenght = $('#shoplogisticsDefaultLength').val();
        var height = $('#shoplogisticsDefaultHeight').val();
        var width = $('#shoplogisticsDefaultWidth').val();
        wgShopLogistics(setShopLogistics,$(this).val(),weight,lenght,width,height,cartSum,key);
    });
});
function setShopLogistics(request) {
    var info = '';

    if (request['delivery_type'] == 'pickup')
    {
        info = 'Код выбранного ПВЗ: ' + request['code_id'] + ', город ' + request['name'] + ', адрес выбранного ПВЗ ' + request['address'] + ', телефон выбранного ПВЗ ' + request['phone'];

        $('input[name="shoplogisticsInfo"]').val(info);
        $('input[name="shoplogistics_pvz_id"]').val(request['code_id']);

        $('input[name="shoplogistics_pvz_adress"]').val(request['address']);

        $('#deliveryInfo').html('ПВЗ: ' + request['address']);
    }
    else
    {
        info = 'Курьерская доставка: ' + request['name'] + ', срок доставки: ' + request['srok_dostavki'] + ' д.';

        $('input[name="shoplogisticsInfo"]').val(info);

        $('#deliveryInfo').html(info);
    }
    var shoplogistics_sum = request['price'];
    $('input[name="DeliverySum"]').val(shoplogistics_sum);
    $('input[name="shoplogistics_delivery_date"]').val(request['srok_dostavki']);
    $("#DosSumma").html(shoplogistics_sum);
    $("#TotalSumma").html(Number(shoplogistics_sum) + Number($('#OrderSumma').val()));
}
