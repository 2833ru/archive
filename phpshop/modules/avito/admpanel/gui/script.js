$(document).ready(function () {
   $('select[name="category_avito_new"]').on('change', function () {
       $.ajax({
           mimeType: 'text/html; charset='+locale.charset,
           url: '/phpshop/modules/avito/admpanel/gui/type.ajax.php',
           type: 'post',
           data: {categoryId: $(this).val()},
           dataType: "json",
           async: false,
           success: function(json) {
               var select = $('select[name="type_avito_new"]');
               if(json['success']) {
                   select.html('');
                   Object.keys(json['data']).forEach(function (item, index) {
                       select.append($('<option></option>', {value: item, text: json['data'][item]}));
                   });
                   select.selectpicker('refresh');
               } else {
                   console.log(json['error'])
               }
           }
       });
   });
});