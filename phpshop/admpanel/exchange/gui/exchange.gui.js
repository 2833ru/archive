// Переопределение функции
var TABLE_EVENT = true;
locale.icon_load = locale.file_load;

$().ready(function () {

    // Выбор сохраненной настройки
    $('body').on('change', '#exchanges', function () {
        if (this.value != "new")
            window.location.href += '&exchanges=' + this.value;
    });

    // Автоматизация загрузки файла
    if ($('.bot-progress .progress-bar').hasClass('active')) {

        $(window).bind("beforeunload", function () {
            return "Are you sure you want to exit? Please complete sign up or the app will get deleted.";
        });

        var time = performance.now();

        var min = $('[name="time_limit"]').val();
        var limit = Number($('[name="line_limit"]').val());
        var start = limit;
        var end = limit + limit;
        var csv_file = $('[name="csv_file"]').val();
        var total = $('[name="total"]').val();
        var count = Number($('#total-update').html());
        var refreshId = setInterval(function () {

            var data = [];
            data.push({name: 'selectID', value: 1});
            data.push({name: 'actionList[selectID]', value: 'actionSave'});
            data.push({name: 'start', value: start});
            data.push({name: 'end', value: end});
            data.push({name: 'time', value: min});
            data.push({name: 'performance', value: performance.now() - time});
            data.push({name: 'ajax', value: true});
            data.push({name: 'csv_file', value: csv_file});
            data.push({name: 'total', value: total});

            $('#product_edit').ajaxSubmit({
                data: data,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function (json) {
                    $('#bot_result').html(json['result']);
                    count += json['count'];
                    $('#total-update').html(count);
                    if (json['success'] == 'done') {
                        clearInterval(refreshId);
                        $('.progress-bar').css('width', '100%');
                        $('.progress-bar').removeClass('active').html('100%');
                        $('#play').trigger("play");
                        $(window).unbind("beforeunload");
                        $('#total-min').html(0);
                    } else if (json['success']) {
                        start += limit;
                        end += limit;
                        $('.progress-bar').css('width', json['bar'] + '%').html(json['bar'] + '%');
                    }

                }

            });

        }, min * 60000);

    }

    // Модальное окно таблиц
    $('#selectModal').on('show.bs.modal', function (event) {
        $('#selectModal .modal-title').html($('[data-target="#selectModal"]').attr('data-title'));
        $('#selectModal .modal-footer .btn-primary').addClass('hidden');
        $('#selectModal .modal-footer [data-dismiss="modal"]').html(locale.close);
        $('#selectModal .modal-body').css('max-height', ($(window).height() - 200) + 'px');
        $('#selectModal .modal-body').css('overflow-y', 'auto');
    });

    // Сохранить Ace
    $(".ace-save").on('click', function (event) {
        event.preventDefault();
        $('#editor_src').val(editor.getValue());
        $('#product_edit').submit();
    });


    // Ace
    if ($('#editor_src').length) {
        var editor = ace.edit("editor");
        var mod = $('#editor_src').attr('data-mod');
        var theme = $('#editor_src').attr('data-theme');
        editor.setTheme("ace/theme/" + theme);
        editor.session.setMode("ace/mode/" + mod);
        editor.setValue($('#editor_src').val(), 1);
        editor.getSession().setUseWrapMode(true);
        editor.setShowPrintMargin(false);
        editor.setAutoScrollEditorIntoView(true);
        $('#editor').height(300);
        editor.resize();
    }

    // Корректировка обязательных полей update/insert
    $('#export_action').on('changed.bs.select', function () {
        $('kbd.enabled').toggle();
        if ($('#export_action').val() == 'update') {
            $('#export_uniq').attr('disabled', 'disabled');
            $('#export_key').attr('disabled', null);
        } else {
            $('#export_uniq').attr('disabled', null);
            $('#export_key').attr('disabled', 'disabled');
        }
    });

    if ($('#export_action').val() == 'update') {
        //$('#export_uniq').attr('disabled', 'disabled');
    }

    // Удалить диапазон
    $(".select-remove").on('click', function (event) {
        event.preventDefault();

        var data = [];
        data.push({name: 'selectID', value: 1});
        data.push({name: 'ajax', value: 1});
        data.push({name: 'actionList[selectID]', value: 'actionSelect'});
        $.ajax({
            mimeType: 'text/html; charset=' + locale.charset,
            url: '?path=exchange.export.product',
            type: 'post',
            data: data,
            dataType: "json",
            async: false,
            success: function () {
                window.location.reload();
            }
        });
    });

    // Очистить сервисную таблицу из списка
    $(".data-row .clean-base").on('click', function (event) {
        event.preventDefault();
        var table = $(this).closest('.data-row').find('td:nth-child(2)').html();

        $.MessageBox({
            buttonDone: "OK",
            buttonFail: locale.cancel,
            message: locale.confirm_clean + ': ' + table + '?'
        }).done(function () {

            var data = [];
            data.push({name: 'table', value: table});
            data.push({name: 'saveID', value: 1});
            data.push({name: 'ajax', value: 1});
            data.push({name: 'actionList[saveID]', value: 'actionSave'});
            $.ajax({
                mimeType: 'text/html; charset=' + locale.charset,
                url: '?path=exchange.service',
                type: 'get',
                data: data,
                dataType: "json",
                async: false,
                success: function (json) {
                    if (json['success'] == 1) {
                        window.location.reload();
                    } else
                        showAlertMessage(locale.save_false, true, true);
                }
            });
        })

    });

    // Очистить сервисную таблицу с отмеченными
    $('.select-action .sql-clean').on('click', function (event) {
        event.preventDefault();

        var chk = $('input:checkbox:checked').length;
        var i = 0;

        if (chk > 0) {

            $.MessageBox({
                buttonDone: "OK",
                buttonFail: locale.cancel,
                message: locale.confirm_clean
            }).done(function () {

                $('input:checkbox:checked').each(function () {
                    var table = $(this).closest('.data-row').find('td:nth-child(2)').html();
                    var data = [];

                    data.push({name: 'table', value: table});
                    data.push({name: 'saveID', value: 1});
                    data.push({name: 'ajax', value: 1});
                    data.push({name: 'actionList[saveID]', value: 'actionSave'});
                    $.ajax({
                        mimeType: 'text/html; charset=' + locale.charset,
                        url: '?path=exchange.service',
                        type: 'get',
                        data: data,
                        dataType: "json",
                        async: false
                    });

                    i++;
                    if (chk == i)
                        window.location.reload();
                });
            })

        } else
            alert(locale.select_no);
    });

    // Восстановить бекап из списка
    $("body").on('click', ".data-row .restore", function (event) {
        event.preventDefault();
        var file = $(this).closest('.data-row').find('td:nth-child(2)>a').html();

        $.MessageBox({
            buttonDone: "OK",
            buttonFail: locale.cancel,
            message: locale.confirm_restore + ': ' + file + '?'
        }).done(function () {

            var data = [];
            data.push({name: 'lfile', value: '/phpshop/admpanel/dumper/backup/' + file});
            data.push({name: 'saveID', value: 1});
            data.push({name: 'ajax', value: 1});
            data.push({name: 'actionList[saveID]', value: 'actionSave'});
            $.ajax({
                mimeType: 'text/html; charset=' + locale.charset,
                url: '?path=exchange.sql',
                type: 'post',
                data: data,
                dataType: "json",
                async: false,
                success: function (json) {
                    if (json['success'] == 1) {
                        showAlertMessage(locale.backup_done);
                    } else
                        showAlertMessage('<strong>' + locale.backup_false + '</strong><br>' + json['error'], true, true);
                }
            });
        })
    });

    // Удаление из списка
    $("body").on('click', ".data-row .delete", function (event) {
        event.preventDefault();
        $('.list_edit_' + $(this).attr('data-id')).append('<input type="hidden" name="file" value="' + $(this).closest('.data-row').find('td:nth-child(2)>a').html() + '">');
    });

    // Удалить с выбранными
    $(".select-action .select").on('click', function (event) {
        event.preventDefault();
        if ($('input:checkbox:checked').length) {

            $('input:checkbox:checked').each(function () {
                var id = $(this).closest('.data-row');
                $('.list_edit_' + $(this).attr('data-id')).append('<input type="hidden" name="file" value="' + $(this).closest('.data-row').find('td:nth-child(2)>a').html() + '">');
            });

        } else
            alert(locale.select_no);
    });

    // Скачать бекап с отмеченными
    $('.select-action .load').on('click', function (event) {
        event.preventDefault();
        if ($('input:checkbox:checked').length) {

            $('input:checkbox:checked').each(function () {
                var add = $(this).closest('.data-row').find('td:nth-child(2)>a').html();
                window.open('?path=exchange.backup&file=' + add);
            });
        } else
            alert(locale.select_no);
    });

    // Оптимизировать базу
    $(".select-action .sql-optim").on('click', function (event) {
        event.preventDefault();
        window.location.href = '?path=exchange.sql&query=optimize';
    });

    // Скачать бекап из списка
    $(".data-row .load").on('click', function (event) {
        event.preventDefault();
        window.location.href = $(this).closest('.data-row').find('td:nth-child(2)>a').attr('href');
    });

    // SQL команда
    $('#sql_query').on('change', function () {
        if ($(this).val() != 0)
            editor.setValue($(this).val());
        //$('#sql_text').html($(this).val());
    });

    // Cнять выделения таблиц
    $("#select-none").on('click', function (event) {
        event.preventDefault();
        $('#pattern_table option:selected').each(function () {
            this.selected = false;
        });
    });

    // Поставить выделения всех таблиц
    $("#select-all").on('click', function (event) {
        event.preventDefault();
        $('#pattern_table option').each(function () {
            this.selected = true;
        });
    });

    // Удаление всех полей
    $("#remove-all").on('click', function (event) {
        event.preventDefault();
        $('#pattern_default option').each(function () {
            this.selected = false;
            $('#pattern_more').append('<option value="' + this.value + '" selected>' + $(this).html() + '</option>');
            $(this).remove();
        });
    });

    // Добавление все поля в выгрузку
    $("#send-all").on('click', function (event) {
        event.preventDefault();
        $('#pattern_more option').each(function () {
            this.selected = true;
            $('#pattern_default').append('<option value="' + this.value + '" selected>' + $(this).html() + '</option>');
            $(this).remove();
        });
    });

    // Добавление выделенные поля в выгрузку
    $("#send-default").on('click', function (event) {
        event.preventDefault();
        $('#pattern_more option:selected').each(function () {
            if (typeof this.value != 'undefined') {
                $('#pattern_default').append('<option value="' + this.value + '" selected>' + $(this).html() + '</option>');
                $(this).remove();
            }
        });
    });

    // Удаление выделенные поля из выгрузки
    $("#send-more").on('click', function (event) {
        event.preventDefault();
        if (typeof $('#pattern_default :selected').html() != 'undefined') {
            $('#pattern_more').append('<option value="' + $('#pattern_default :selected').val() + '">' + $('#pattern_default :selected').html() + '</option>');
            $('#pattern_default option:selected').remove();
        }
    });

    // Лимит
    $(".btn-file-search").on('click', function () {
        $('#file_search').submit();
    });


    // Лимит - очистка
    $(".btn-file-cancel").on('click', function () {
        window.location.replace('?path=exchange.file');
    });


    // Таблица сортировки
    var table = $('#data').dataTable({
        "paging": true,
        "ordering": true,
        "info": false,
        "language": locale.dataTable,
         "fnDrawCallback": function () {

            // Активация из списка dropdown
            $('.data-row').hover(
                    function () {
                        $(this).find('#dropdown_action').show();
                    },
                    function () {
                        $(this).find('#dropdown_action').hide();
                    });
        }
    });
});