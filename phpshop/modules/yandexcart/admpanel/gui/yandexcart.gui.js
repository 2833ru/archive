
$().ready(function() {
    var block = $('#collapseExample2').html();
    $('[name="vendor_name_new"]').closest('.tab-pane').append(block);
    $('#collapseExample2').closest('.collapse-block').next('hr').remove();
    $('#collapseExample2').closest('.collapse-block').remove();
});
