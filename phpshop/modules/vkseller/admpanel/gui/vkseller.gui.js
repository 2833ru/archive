$().ready(function () {

    // OAuth-токен
    $("#client_token").on('click', function (event) {
        event.preventDefault();
        if ($('#client_id_new').val() !== '') {
           window.open($(this).attr('href') + $('#client_id_new').val());
        } else {
            $.MessageBox({
                buttonDone: "OK",
                message: locale.select_no + ' ID Application?'
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

    // datetimepicker
    if ($(".date").length) {
        $.fn.datetimepicker.dates['ru'] = locale;
        $(".date").datetimepicker({
            format: 'yyyy-mm-dd',
            weekStart: 1,
            language: 'ru',
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0
        });
    }

    // Поиск заказа
    $(".btn-order-search").on('click', function () {
        $('#order_search').submit();
    });

    // Поиск заказа - очистка
    $(".btn-order-cancel").on('click', function () {
        window.location.replace('?path=modules.dir.vkseller.order');
    });

});