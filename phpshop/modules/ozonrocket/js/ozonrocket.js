function OzonRocketWidgetStart() {
    if(typeof $().modal !== "function") {
        setTimeout(function () {OzonRocketWidgetStart();}, 1000);
        return;
    }
    $('input[name="ozonrocketSum"]').remove();
    $('input[name="ozonrocketType"]').remove();
    $('input[name="delivery_id"]').remove();
    $('input[name="address"]').remove();

    $('<input type="hidden" name="ozonrocketSum" id="ozonrocketSum">').insertAfter('#dop_info');
    $('<input type="hidden" name="ozonrocketType" id="ozonrocketType">').insertAfter('#dop_info');
    $('<input type="hidden" name="delivery_id" id="delivery_id">').insertAfter('#dop_info');
    $('<input type="hidden" name="address" id="address">').insertAfter('#dop_info');

    $("#makeyourchoise").val(null);
    $("#ozonrocketwidgetModal").modal("toggle");
    $('#ozonrocketwidget iframe').attr('src', $('#ozonrocketwidget iframe' ).data('src'));
}
window.addEventListener("message", receiveMessage, false);
function receiveMessage(event)
{
    if (event.origin !== "https://rocket.ozon.ru" && event.origin !== "https://rocket-demo.ozonru.me")
        return;
    var data = JSON.parse(event.data);
    $("#makeyourchoise").val('DONE');
    $("#DosSumma").html(data["price"]);
    $('#deliveryInfo').html(data['type'] + '. ' + data['address']);
    $("#TotalSumma").html(Number(data["price"]) + Number($('#OrderSumma').val()));
    $("#ozonrocketSum").val(data["price"]);
    $("#ozonrocketType").val(data['type']);
    $('#delivery_id').val(data['id']);
    $('#address').val(data['address']);
    $("#ozonrocketwidgetModal").modal("toggle");
}

$(document).ready(function (){
    $('.ozonwidgetproductstart').on('click', function (){
        $('#ozonrocketwidget iframe').attr('src', $('#ozonrocketwidget iframe' ).data('src'));
    })
});

