$().ready(function() {

    // Id каталога
    var cat = $.getUrlVar('cat');

    // Дерево категорий
    $('#tree [role="progressbar"]').css('width', '90%');
    if ($('#tree').length) {

        $.ajax({
            type: "GET",
            url: "./catalog/gui/tree.gui.php",
            data: "id=" + $.getUrlVar('id') + '&cat=' + $.getUrlVar('cat') + '&action=' + $.getUrlVar('action'),
            dataType: "html",
            async: false,
            success: function(json)
            {
                $('#tree').treeview({
                    data: json,
                    enableLinks: false,
                    showIcon: true,
                    color: $('#temp-color').css('color'),
                    showBorder: false,
                    selectedBackColor: $('#temp-color-selected').css('color'),
                    onhoverColor: $('.navbar-action').css('background-color'),
                    backColor: "transparent",
                    expandIcon: 'glyphicon glyphicon-triangle-right',
                    collapseIcon: 'glyphicon glyphicon-triangle-bottom'
                });

                // Путь node
                $('#tree').treeview('getExpanded').forEach(function(entry) {
                    $('#tree').treeview('revealNode', [entry['nodeId'], {silent: true}]);
                });
            }
        });
    }


    // Настройка полей - 2 шаг
    $("body").on('click', "#selectModal .modal-footer .option-send", function(event) {
        event.preventDefault();

        if ($('#selectModal input:checkbox:checked').length) {
            var data = [];
            $('#selectModal input:checkbox:checked').each(function() {
                data.push({name: 'option[' + $(this).attr('name') + ']', value: $(this).val()});

            });

            data.push({name: 'selectID', value: 1});
            data.push({name: 'ajax', value: 1});
            data.push({name: 'actionList[selectID]', value: 'actionOptionSave'});
            $.ajax({
                mimeType: 'text/html; charset=windows-1251',
                url: '?path=catalog.select',
                type: 'post',
                data: data,
                dataType: "json",
                async: false,
                success: function() {
                    window.location.reload();
                }

            });
        }
        else
            alert(locale.select_no);
    });

    // Настройка полей - 1 шаг
    $(".option").on('click', function(event) {
        event.preventDefault();

        var data = [];
        data.push({name: 'selectID', value: 1});
        data.push({name: 'ajax', value: 1});
        data.push({name: 'cat', value: cat});
        data.push({name: 'actionList[selectID]', value: 'actionOption'});

        $.ajax({
            mimeType: 'text/html; charset=windows-1251',
            url: '?path=catalog.select',
            type: 'post',
            data: data,
            dataType: "html",
            async: false,
            success: function(data) {
                $('#selectModal .modal-dialog').removeClass('modal-lg');
                $('#selectModal .modal-title').html(locale.option_title);
                $('#selectModal .modal-footer .btn-primary').addClass('option-send');
                $('#selectModal .modal-footer .btn-delete').addClass('hidden');
                $('#selectModal .modal-footer .btn-primary').html(locale.ok);
                $('#selectModal .modal-body').html(data);
                $('#selectModal').modal('show');
            }
        });
    });

    // Расширенный поиск товара
    $(".search").on('click', function(event) {
        event.preventDefault();

        var data = [];
        data.push({name: 'selectID', value: 1});
        data.push({name: 'ajax', value: 1});
        data.push({name: 'cat', value: $.getUrlVar('cat')});
        data.push({name: 'actionList[selectID]', value: 'actionAdvanceSearch'});

        $.ajax({
            mimeType: 'text/html; charset=windows-1251',
            url: '?path=catalog.search',
            type: 'post',
            data: data,
            dataType: "html",
            async: false,
            success: function(data) {
                $('#selectModal .modal-dialog').removeClass('modal-lg');
                $('#selectModal .modal-title').html(locale.search_advance_title);
                $('#selectModal .modal-footer .btn-primary').html(locale.search_advance_but);
                $('#selectModal .modal-footer .btn-primary').addClass('search-send');
                $('#selectModal .modal-footer .btn-delete').addClass('hidden');
                $('#selectModal .modal-body').html(data);
                $('#selectModal').modal('show');
                $('#modal-form').attr('method', 'get');
            }
        });
    });

    // Переход на страницу из списка
    $("#dropdown_action  .url").on('click', function(event) {
        event.preventDefault();
        window.open('../../shop/UID_' + $(this).attr('data-id') + '.html');
    });

    // Редактировать с выбранными - 2 шаг
    $("body").on('click', "#selectModal .modal-footer .edit-select-send", function(event) {
        event.preventDefault();

        if ($('#selectModal input:checkbox:checked').length) {
            var data = [];
            $('#selectModal input:checkbox:checked').each(function() {
                data.push({name: 'select_col[' + $(this).attr('name') + ']', value: $(this).attr('name')});

            });

            data.push({name: 'selectID', value: 1});
            data.push({name: 'ajax', value: 1});
            data.push({name: 'actionList[selectID]', value: 'actionSelectEdit'});
            $.ajax({
                mimeType: 'text/html; charset=windows-1251',
                url: '?path=catalog.select',
                type: 'post',
                data: data,
                dataType: "json",
                async: false,
                success: function() {
                    window.location.href = '?path=catalog.select&cat=' + cat;
                }

            });
        }
        else
            alert(locale.select_no);
    });

    // Cнять выделения чекбоксов
    $('#selectModal').on('click', "#select-none", function(event) {
        event.preventDefault();
        $('#selectModal input:checkbox:checked').each(function() {
            this.checked = false;
        });
    });

    // Выделить все чекбоксы
    $('#selectModal').on('click', "#select-all", function(event) {
        event.preventDefault();
        $('#selectModal input:checkbox').each(function() {
            this.checked = true;
        });
    });

    // Редактировать с выбранными - 1 шаг
    $(".select-action .edit-select").on('click', function(event) {
        event.preventDefault();

        if ($('#data input:checkbox:checked').length) {
            var data = [];
            $('#data input:checkbox:checked').each(function() {
                if (this.value != 'all')
                    data.push({name: 'select[' + $(this).attr('data-id') + ']', value: $(this).attr('data-id')});

            });

            data.push({name: 'selectID', value: 1});
            data.push({name: 'ajax', value: 1});
            data.push({name: 'actionList[selectID]', value: 'actionSelect'});

            $.ajax({
                mimeType: 'text/html; charset=windows-1251',
                url: '?path=catalog.select',
                type: 'post',
                data: data,
                dataType: "html",
                async: false,
                success: function(data) {
                    $('#selectModal .modal-title').html(locale.select_title);
                    $('#selectModal .modal-footer .btn-primary').html(locale.select_edit);
                    $('#selectModal .modal-footer .btn-primary').addClass('edit-select-send');
                    $('#selectModal .modal-dialog').addClass('modal-lg');
                    $('#selectModal .modal-body').html(data);
                    $('#selectModal').modal('show');
                }
            });
        }
        else
            alert(locale.select_no);
    });

    // Экспортировать с выбранными
    $(".select-action .export-select").on('click', function(event) {
        event.preventDefault();

        if ($('#data input:checkbox:checked').length) {
            var data = [];
            $('#data input:checkbox:checked').each(function() {
                if (this.value != 'all')
                    data.push({name: 'select[' + $(this).attr('data-id') + ']', value: $(this).attr('data-id')});
            });

            data.push({name: 'selectID', value: 1});
            data.push({name: 'ajax', value: 1});
            data.push({name: 'actionList[selectID]', value: 'actionSelect'});
            $.ajax({
                mimeType: 'text/html; charset=windows-1251',
                url: '?path=exchange.export.product',
                type: 'post',
                data: data,
                dataType: "json",
                async: false,
                success: function() {
                    window.location.href = '?path=exchange.export';
                }

            });
        }
        else
            alert(locale.select_no);
    });


    // Управление деревом категорий

    $('.title-icon .glyphicon-chevron-down').on('click', function() {
        $('#tree').treeview('expandAll', {silent: true});
    });

    $('.title-icon .glyphicon-chevron-up').on('click', function() {
        $('#tree').treeview('collapseAll', {silent: true});
    });

    // Изменение данных из списка (цена, склад)
    $('body').on('change', '.editable', function() {
        var data = [];
        data.push({name: $(this).attr('data-edit'), value: escape($(this).val())});
        data.push({name: 'rowID', value: $(this).attr('data-id')});
        data.push({name: 'editID', value: 1});
        data.push({name: 'ajax', value: 1});
        data.push({name: 'actionList[editID]', value: 'actionUpdate.catalog.edit'});
        $.ajax({
            mimeType: 'text/html; charset=windows-1251', // ! Need set mimeType only when run from local file
            url: '?path=product&id=' + $(this).attr('data-id'),
            type: 'post',
            data: data,
            dataType: "json",
            async: false,
            success: function(json) {
                if (json['success'] == 1) {
                    showAlertMessage(locale.save_done);
                } else
                    showAlertMessage(locale.save_false, true);
            }
        });
    });

    // Ссылка в Node
    $('#tree').on('nodeSelected', function(event, data) {
        if (data['href'])
            window.location.href = './admin.php' + data['href'];
    });

    // Поиск категорий
    var search = function(e) {
        var pattern = $('#input-category-search').val();
        var options = {
            ignoreCase: true, // case insensitive
            exactMatch: false, // like or equals
            revealResults: true // reveal matching nodes
        };
        var results = $('#tree').treeview('search', [pattern, options]);
    };
    $('#btn-search').on('click', search);

    $('#show-category-search').on('click', function() {
        $('#category-search').slideToggle('slow');
    });

    $('#input-category-search').keyup(function(event) {
        if (event.keyCode == '13') {
            event.preventDefault();
            search();
        }
        return false;
    });

    // Создать новый из списка
    $("button[name=addNew]").on('click', function() {
        var cat = $(this).attr('data-cat');
        var href = '?path=product&return=catalog&action=new';
        if (cat > 0)
            href += '&cat=' + cat;
        window.location.href = href;
        action = true;
    });


    // Создать копию из списка
    $(".select-action .copy").on('click', function(event) {
        event.preventDefault();
        window.location.href = '?path=product&return=catalog&action=new&id=' + $('input[name=rowID]').val();
    });

    // Создать копию из списка dropdown
    $(".data-row .copy").on('click', function(event) {
        event.preventDefault();
        window.location.href = '?path=product&return=catalog&action=new&id=' + $(this).attr('data-id');
    });


    if ($('#dop_cat_new').length)
        $('#dop_cat_new').tagsInput({
            'height': '100px',
            'width': '100%',
            'interactive': true,
            'defaultText': locale.enter,
            'removeWithBackspace': true,
            'minChars': 0,
            'delimiter': ['#'],
            'maxChars': 0, // if not provided there is no limit
            'placeholderColor': '#666666'
        });

    // Активация из списка dropdown
    $('.data-row, .data-tree').hover(
            function() {
                $(this).find('#dropdown_action').show();
                $(this).find('.editable').removeClass('input-hidden');
                $(this).find('.media-object').addClass('image-shadow');
            },
            function() {
                $(this).find('#dropdown_action').hide();
                $(this).find('.editable').addClass('input-hidden');
                $(this).find('.media-object').removeClass('image-shadow');
            });
});