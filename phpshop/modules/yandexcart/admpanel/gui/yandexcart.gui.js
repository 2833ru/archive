$().ready(function () {

    // OAuth-токен
    $("#client_token").on('click', function (event) {
        event.preventDefault();
        if ($('#client_id_new').val() !== '') {
            window.open($(this).attr('href') + $('#client_id_new').val());
        } else {
            $.MessageBox({
                buttonDone: "OK",
                message: locale.select_no + ' ID Yandex.OAuth?'
            });
        }
    });

    // Выбрать все категории
    $("body").on('change', "#categories_all", function () {
        if (this.checked)
            $('[name="categories[]"]').selectpicker('selectAll');
        else
            $('[name="categories[]"]').selectpicker('deselectAll');
    });

    var block = $('#collapseExample2').html();
    $('[name="vendor_name_new"]').closest('.tab-pane').append(block);
    $('#collapseExample2').closest('.collapse-block').next('hr').remove();
    $('#collapseExample2').closest('.collapse-block').remove();
});
