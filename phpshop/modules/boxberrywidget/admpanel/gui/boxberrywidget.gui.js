function getPVZ() {
    var api_token = $('input[name=api_key_new]').val();
    boxberry.open('setPVZ', api_token, '', '', '', '', '', '', '', '');
}
function setPVZ(result) {
    $('input[name="pvz_id_new"]').val(result.id);
}
