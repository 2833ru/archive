function saferoutewidgetStart() {
  $('input[name="saferouteReq"]').remove();

  if(!$('input[name="saferouteSum"]').length)
    $('<input type="hidden" name="saferouteSum" id="saferouteSum">').insertAfter('#d');
  if(!$('input[name="saferouteDop"]').length)
    $('<input type="hidden" name="saferouteDop">').insertAfter('#dop_info');
  if(!$('input[name="saferouteData"]').length)
    $('<input type="hidden" name="saferouteData">').insertAfter('#dop_info');
  $('<input type="hidden" name="saferouteReq" class="req form-control">').insertAfter('#dop_info');

  var widget = new SafeRouteCartWidget('saferoute-widget', {
    apiScript: '/phpshop/modules/saferoutewidget/api/saferoute-widget-api.php',
    products: $.parseJSON($("input:hidden.cartListJson").val()),
    weight: $('#ddweight').val()
  });

  widget.on('error', function (e) {
    console.error(e);
  });

  widget.on('done', function (response) {
    $('<input type="hidden" name="saferouteToken" value="' + response.id + '">').insertAfter('#d');
    $('input[name="saferouteReq"]').val($('input[name="saferouteDop"]').val());
    $('#saferoute-close').text('Продолжить').addClass('btn-success');
  });

  widget.on('change', function (data) {
    if(data.delivery) {
      var total = data.delivery.totalPrice + Number($('#OrderSumma').val());

      $('input[name="saferouteDop"]').val(data._meta.commonDeliveryData);
      $('#deliveryInfo').html(data._meta.commonDeliveryData);
      $('#DosSumma').html(data.delivery.totalPrice);
      $('#TotalSumma').html(total.toFixed(2));
      $('#saferouteSum').val(data.delivery.totalPrice);
    }

    $('input[name="name_new"]').val(data.contacts.fullName);
    $('input[name="fio_new"]').val(data.contacts.fullName);

    if(data.contacts.phone) $('input[name="tel_new"]').val(data.contacts.phone.substring(1));
    $('input[name="flat_new"]').val(data.contacts.address.apartment);
    $('input[name="house_new"]').val(data.contacts.address.building);
    $('input[name="street_new"]').val(data.contacts.address.street);
    $('input[name="index_new"]').val(data.contacts.address.zipCode);

    if(data.city) $('input[name="city_new"]').val(data.city.name);

    $('input[name="saferouteData"]').val(JSON.stringify(data, null, 0));

    if (window.saferoutewidgetHook) window.saferoutewidgetHook(data);
  });

  $('#saferoutewidgetModal').modal('toggle');
}
