
// Состояние формы
var is_change = false;

$().ready(function() {

    // Форма редактировалась
    $('#product_edit .form-control, #product_edit .hidden-edit, #product_edit input:radio:checked, #product_edit input:checkbox:checked').change(function() {
        is_change = true;
    });

    // Загрузка файлов на сервер
    $("body").on('click', '.btn-upload', function(event) {
        event.preventDefault();
        $("#uploader").contents().find('#send-btn').click();
    });

    // Пакетный загрузчик
    $("body").on('click', "#uploaderModal", function(event) {
        event.preventDefault();
        var id = $('input[name="rowID"]').val();
        $('#selectModal .modal-body').html($('#elfinderModal .modal-body').html());
        $('#selectModal .elfinder-modal-content').attr('src', './product/gui/uploader.gui.php?id=' + id);
        $('#selectModal .elfinder-modal-content').attr('id', 'uploader');
        $('#selectModal .modal-title').html(locale.select_file + 'ы');
        $('#selectModal .modal-footer .btn-primary').addClass('btn-upload');
        $('#selectModal .modal-footer .btn-primary').prop("type", "button");

        $('#selectModal').modal('show');
    });

    // Память закладки для изображений
    $('a[data-toggle="tab"]').on('shown.bs.tab', function() {
        $('input[name="tabName"]').val($(this).attr('data-id'));
    });

    // Перезагрузка страницы при добавлении изображения
    $("button[name=editID]").on('click', function(event) {
        event.preventDefault();
        if ($('input[name="img_new"]').val()) {
            setTimeout(function() {
                window.location.href = window.location.href.split('&tab=1').join('') + '&tab=1';
            }, 5000);
        }

        // Мобильная версия с перезагрузкой
        else if ($('.navbar-right  button[name="saveID"]').is(":hidden")) {
            $('#product_edit').append('<input type="hidden" name="saveID" value="1">');
            $('#product_edit').submit();
        }

        // Проверка характеристики
        $('.vendor_add').each(function() {
            if (this.value != '') {
                setTimeout(function() {
                    window.location.href = window.location.href.split('&tab=1').join('') + '&tab=5';
                }, 5000);
            }
        });
    });

    // закрепление навигации
    if ($('#fix-check:visible').length && typeof(WAYPOINT_LOAD) != 'undefined')
        var waypoint = new Waypoint({
            element: document.getElementById('fix-check'),
            handler: function(direction) {
                $('.navbar-action').toggleClass('navbar-fixed-top');
            }
        });

    // Указать ID товара в виде тега - Поиск
    $("body").on('click', "#selectModal .search-action", function(event) {
        event.preventDefault();

        var data = [];
        data.push({name: 'selectID', value: 2});
        data.push({name: 'ajax', value: 1});
        data.push({name: 'actionList[selectID]', value: 'actionSearch'});

        $.ajax({
            mimeType: 'text/html; charset=windows-1251',
            url: '?path=catalog.search&words=' + escape($('input:text[name=search_name]').val()) + '&cat=' + $('select[name=search_category]').val() + '&price_start=' + $('input:text[name=search_price_start]').val() + '&price_end=' + $('input:text[name=search_price_end]').val(),
            type: 'post',
            data: data,
            dataType: "html",
            async: false,
            success: function(data) {
                $('#selectModal .modal-body').html(data);

            }

        });
    });

    // Указать ID товара в виде тега  -  2 шаг
    $("body").on('click', "#selectModal .modal-footer .id-add-send", function(event) {
        event.preventDefault();

        $('.search-list input:checkbox:checked').each(function() {
            var id = $(this).attr('data-id');
            if ($('#odnotip_new').tagExist(id))
            {
                this.disabled;
            }
            else
                $(selectTarget).addTag(id);
        });

        $('#selectModal').modal('hide');
    });


    // Выбор элемента по клику в модальном окне подбора товара
    $('body').on('click', ".search-list  td", function() {
        $(this).parent('tr').find('input:checkbox[name=items]').each(function() {
            this.checked = !this.checked && !this.disabled;
        });
    });


    // Указать ID товара в виде тега  - 1 шаг
    $(".tag-search").on('click', function(event) {
        event.preventDefault();

        selectTarget = $(this).attr('data-target');

        var data = [];
        data.push({name: 'selectID', value: 2});
        data.push({name: 'ajax', value: 1});
        data.push({name: 'currentID', value: $(selectTarget).val()});
        data.push({name: 'actionList[selectID]', value: 'actionSearch'});

        $.ajax({
            mimeType: 'text/html; charset=windows-1251',
            url: '?path=catalog.search',
            type: 'post',
            data: data,
            dataType: "html",
            async: false,
            success: function(data) {
                //$('#selectModal .modal-dialog').removeClass('modal-lg');
                $('#selectModal .modal-title').html(locale.add_cart_value);
                $('#selectModal .modal-footer .btn-primary').removeClass('edit-select-send');
                $('#selectModal .modal-footer .btn-primary').addClass('id-add-send');
                $('#selectModal .modal-footer .btn-delete').addClass('hidden');
                $('#selectModal .modal-body').css('max-height', ($(window).height() - 200) + 'px');
                $('#selectModal .modal-body').css('overflow-y', 'auto');
                $('#selectModal .modal-body').html(data);

                $(".search-list td input:checkbox").each(function() {
                    this.checked = true;
                });

                $('#selectModal').modal('show');

            }

        });
    });

    $('#odnotip_new').tagsInput({
        'height': '100px',
        'width': '100%',
        'interactive': true,
        'defaultText': 'Ввод...',
        'removeWithBackspace': true,
        'minChars': 0,
        'maxChars': 0, // if not provided there is no limit
        'placeholderColor': '#666666'
    });

    $('#parent_new').tagsInput({
        'height': '100px',
        'width': '100%',
        'interactive': true,
        'defaultText': 'Ввод...',
        'removeWithBackspace': true,
        'minChars': 0,
        'maxChars': 0, // if not provided there is no limit
        'placeholderColor': '#666666'
    });

    $('#dop_cat_new').tagsInput({
        'height': '100px',
        'width': '100%',
        'interactive': true,
        'defaultText': 'Ввод...',
        'removeWithBackspace': true,
        'minChars': 0,
        'delimiter': ['#'],
        'maxChars': 0, // if not provided there is no limit
        'placeholderColor': '#666666'
    });

    // Редактирование изображения товара
    $(".img-main").on('click', function(event) {
        event.preventDefault();

        $('input[name="pic_big_new"]').val($(this).attr('data-path'));
        $('input[name="pic_small_new"]').val($(this).attr('data-path-s'));

        $('[data-icon="pic_big_new"]').html($(this).attr('data-path'));
        $('[data-icon="pic_small_new"]').html($(this).attr('data-path-s'));
        $('[data-thumbnail="pic_big_new"]').attr('src', $(this).attr('data-path'));

        $('.img-main').removeClass('btn-success');
        $(this).removeClass('btn-default');
        $(this).addClass('btn-success');
    });

    // Удаление изображения товара
    $(".img-delete").on('click', function(event) {
        event.preventDefault();
        var data = [];
        var id = $(this).attr('data-id');
        var parent = $(this).closest('.data-row');
        data.push({name: 'ajax', value: 1});
        data.push({name: 'rowID', value: id});
        data.push({name: 'actionList[rowID]', value: 'actionImgDelete.catalog.edit'});

        if (confirm(locale.confirm_delete)) {

            $.ajax({
                mimeType: 'text/html; charset=windows-1251',
                url: '?path=product&id=' + id,
                type: 'post',
                data: data,
                dataType: "json",
                async: false,
                success: function(json) {
                    if (json['success'] == 1) {
                        showAlertMessage(locale.save_done);
                        parent.fadeOut();
                    } else
                        showAlertMessage(locale.save_false, true, true);
                }

            });
        }
    });

    // Добавить файл товара - 2 шаг
    $("body").on('click', "#selectModal .modal-footer .file-add-send", function(event) {
        event.preventDefault();
        var id = parseInt($('input[name=fileCount]').val());
        $('.file-list').append('<tr class="data-row" data-row="' + id + '"><td class="file-edit"><a href="' + $('input[name=lfile]').val() + '" class="file-edit"></a></td><td><input class="hidden-edit " value="" name="files_new[' + id + '][path]" type="hidden"><input class="hidden-edit" value="" name="files_new[' + id + '][name]" type="hidden"></td><td style="text-align:right" class="file-edit-path"><a href="' + $('input[name=lfile]').val() + '" class="file-edit-path" target="_blank"></a></td></tr>');

        $('.file-list [data-row="' + id + '"] .file-edit > a').html($('input[name=modal_file_name]').val());
        $('.file-list [data-row="' + id + '"] input[name="files_new[' + id + '][name]"]').val($('input[name=modal_file_name]').val());
        $('.file-list [data-row="' + id + '"] .file-edit-path > a').html('<span class="glyphicon glyphicon-floppy-disk"></span>' + $('input[name=lfile]').val());
        $('.file-list [data-row="' + id + '"] input[name="files_new[' + id + '][path]"]').val($('input[name=lfile]').val());

        $('.file-add').attr('data-count', id);
        $('#selectModal .modal-footer .btn-primary').removeClass('file-add-send');


        $('#selectModal').modal('hide');
    });

    // Добавить файл товара - 1 шаг
    $(".file-add").on('click', function(event) {
        event.preventDefault();

        var data = [];
        var id = $(this).closest('.data-row').attr('data-row');
        data.push({name: 'selectID', value: id});
        data.push({name: 'ajax', value: 1});
        data.push({name: 'fileCount', value: $(this).attr('data-count')});
        data.push({name: 'actionList[selectID]', value: 'actionFileEdit'});

        $.ajax({
            mimeType: 'text/html; charset=windows-1251',
            url: '?path=product&id=file' + '&name=' + escape('Новый Файл'),
            type: 'post',
            data: data,
            dataType: "html",
            async: false,
            success: function(data) {
                $('#selectModal .modal-dialog').removeClass('modal-lg');
                $('#selectModal .modal-title').html(locale.file_add);
                $('#selectModal .modal-footer .btn-primary').removeClass('edit-select-send');
                $('#selectModal .modal-footer .btn-primary').addClass('file-add-send');
                $('#selectModal .modal-footer .btn-delete').addClass('hidden');
                $('#selectModal .modal-footer .btn-delete').addClass('file-delete');
                $('#selectModal .modal-body').html(data);

                $('.elfinder-modal-content').attr('data-option', 'return=lfile');
                $('#selectModal').modal('show');
            }

        });
    });

    // Удаление файла товара
    $("body").on('click', "#selectModal .modal-footer .file-delete", function(event) {
        event.preventDefault();
        var id = $('input[name=selectID]').val();
        if (confirm(locale.confirm_delete)) {
            $('.file-list [data-row="' + id + '"]').remove();
            $('#selectModal').modal('hide');
        }
    });

    // Редактировать файл товара - 2 шаг
    $("body").on('click', "#selectModal .modal-footer .file-edit-send", function(event) {
        event.preventDefault();
        var id = $(this).closest('.data-row').attr('data-row');

        var name = $('input[name=modal_file_name]').val();
        $('.file-list [data-row="' + id + '"] .file-edit > a').html(name);
        $('.file-list [data-row="' + id + '"] input[name="files_new[' + id + '][name]"]').val(name);
        $('.file-list [data-row="' + id + '"] .file-edit-path > a').html('<span class="glyphicon glyphicon-floppy-disk"></span>' + $('input[name=lfile]').val());
        $('.file-list [data-row="' + id + '"] .file-edit input[name="files_new[' + id + '][path]"]').val($('input[name=lfile]').val());
        $('#selectModal').modal('hide');

    });

    // Редактировать файл товара
    $("body").on('click', ".data-row .file-edit > a", function(event) {
        event.preventDefault();

        var data = [];
        var id = $(this).closest('.data-row').attr('data-row');
        data.push({name: 'selectID', value: id});
        data.push({name: 'ajax', value: 1});
        data.push({name: 'actionList[selectID]', value: 'actionFileEdit'});

        var name = $(this).html();

        $.ajax({
            mimeType: 'text/html; charset=windows-1251',
            url: '?path=product&id=file&file=' + $(this).attr('href') + '&name=' + escape(name),
            type: 'post',
            data: data,
            dataType: "html",
            async: false,
            success: function(data) {
                $('#selectModal .modal-dialog').removeClass('modal-lg');
                $('#selectModal .modal-title').html(locale.file_edit + ': ' + name);
                $('#selectModal .modal-footer .btn-primary').removeClass('edit-select-send');
                $('#selectModal .modal-footer .btn-primary').addClass('file-edit-send');
                $('#selectModal .modal-footer .btn-delete').removeClass('hidden');
                $('#selectModal .modal-footer .btn-delete').addClass('file-delete');
                $('#selectModal .modal-body').html(data);

                $('.elfinder-modal-content').attr('data-option', 'return=lfile');
                $('#selectModal').modal('show');
            }

        });
    });
});