// Переопределение функции
var TABLE_EVENT = true;
var ajax_path = "./catalog/ajax/";

$().ready(function() {

    // Id каталога
    var cat = $.getUrlVar('cat');

    // Предпросмотр
    $(".cat-view").on('click', function(event) {
        event.preventDefault();
        window.open('../../shop/CID_' + $.cookie('cat') + '.html');
    });

    // Назад к категории из товаров
    $("#btnBackProduct").on('click', function(event) {
        if ($.cookie('cat') != 'undefined') {
            event.preventDefault();
            window.location.href = '?path=catalog&id=' + $.cookie('cat');
        }
    });

    // Дерево категорий
    $('#tree [role="progressbar"]').css('width', '90%');
    if ($('#tree').length) {
        
        $.ajax({
            type: "GET",
            url: ajax_path + "tree.ajax.php",
            data: "id=" + $.getUrlVar('id') + '&cat=' + $.getUrlVar('cat') + '&action=' + $.getUrlVar('action')+'&path='+$.getUrlVar('path'),
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
                    collapseIcon: 'glyphicon glyphicon-triangle-bottom',
                    onNodeSelected: function(event, data) {

                        $("#data").DataTable().search("");

                        if (typeof(table) != 'undefined') {
                            cat = data['tags'];
                            table.api().ajax.url(ajax_path + "product.ajax.php?cat=" + cat).load();

                            $('#select_all').prop('checked', false);
                            $('[name="addNew"]').attr('data-cat', cat);
                            $.cookie('cat', cat);
                            $('.cat-select, .cat-view').removeClass('hide');

                            $('#btnBackProduct').removeClass('disabled');
                        }
                        else
                            window.location.href = '?path=catalog&id=' + data['tags'];
                    }
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
                mimeType: 'text/html; charset='+locale.charset,
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
            mimeType: 'text/html; charset='+locale.charset,
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

    // Расширенный поиск товара - 1 шаг
    $(".search").on('click', function(event) {
        event.preventDefault();

        var data = [];
        data.push({name: 'selectID', value: 1});
        data.push({name: 'ajax', value: 1});
        data.push({name: 'cat', value: $.cookie('cat')});
        data.push({name: 'actionList[selectID]', value: 'actionAdvanceSearch'});

        $.ajax({
            mimeType: 'text/html; charset='+locale.charset,
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
                $("#data").DataTable().search("");
            }
        });
    });

    // Расширенный поиск товара - 2 шаг
    $("body").on('click', ".search-send", function(event) {
        event.preventDefault();
        var push = '?from=search&';
        $('#modal-form .form-control,  #modal-form input:radio:checked, #modal-form input:checkbox:checked').each(function() {
            if ($(this).attr('name') !== undefined) {
                push += $(this).attr('name') + '=' + escape($(this).val()) + '&';

            }
        });
        table.api().ajax.url(ajax_path + 'product.ajax.php' + push).load();
        $('#selectModal').modal('hide');

    });

    // Переход на страницу из списка
    $("#dropdown_action  .url").on('click', function(event) {
        event.preventDefault();
        window.open('../../shop/UID_' + $(this).attr('data-id') + '.html');
    });

    // Редактировать с выбранными - 2 шаг
    $("body").on('click', "#selectModal .modal-footer .edit-select-send", function(event) {
        event.preventDefault();
        
        if (typeof cat != 'undefined')
            var current_cat = '&cat=' + cat;
        else var current_cat='';

        if ($('#selectModal input:checkbox:checked').length) {
            var data = [];
            $('#selectModal input:checkbox:checked').each(function() {
                data.push({name: 'select_col[' + $(this).attr('name') + ']', value: $(this).attr('name')});

            });

            data.push({name: 'selectID', value: 1});
            data.push({name: 'ajax', value: 1});
            data.push({name: 'actionList[selectID]', value: 'actionSelectEdit'});
            $.ajax({
                mimeType: 'text/html; charset='+locale.charset,
                url: '?path=catalog.select',
                type: 'post',
                data: data,
                dataType: "json",
                async: false,
                success: function() {
                    window.location.href = '?path=catalog.select' + current_cat;
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
                mimeType: 'text/html; charset='+locale.charset,
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
                mimeType: 'text/html; charset='+locale.charset,
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
            mimeType: 'text/html; charset='+locale.charset,
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

    // Создать новый товар из списка
    $("button[name=addNew]").on('click', function() {
        //var cat = $(this).attr('data-cat');
        var href = '?path=product&return=catalog&action=new';
        if (cat > 0)
            href += '&cat=' + cat;
        window.location.href = href;
        action = true;
    });

    // Создать новый каталог из списка
    $("button[name=addNewCat]").on('click', function() {
        var href = '?path=catalog&action=new';
        window.location.href = href;
        action = true;
    });


    // Создать копию из списка
    $(".select-action .copy").on('click', function(event) {
        event.preventDefault();
        window.location.href = '?path=product&return=catalog&action=new&id=' + $('input[name=rowID]').val();
    });

    // Создать копию из списка dropdown
    $("body").on('click', ".data-row .copy", function(event) {
        event.preventDefault();
        window.location.href = '?path=product&return=catalog&action=new&id=' + $(this).attr('data-id');
    });


    // Активация из списка dropdown
    $("body").on('mouseenter', '.data-row', function() {
        $(this).find('#dropdown_action').show();
        $(this).find('.editable').removeClass('input-hidden');
        $(this).find('.media-object').addClass('image-shadow');
    });
    $("body").on('mouseleave', '.data-row', function() {
        $(this).find('#dropdown_action').hide();
        $(this).find('.editable').addClass('input-hidden');
        $(this).find('.media-object').removeClass('image-shadow');
    });

    // Таблица данных
    if (typeof($.cookie('data_length')) == 'undefined')
        var data_length = [10, 25, 50, 75, 100, 500, 1000];
    else
        var data_length = [parseInt($.cookie('data_length')), 10, 25, 50, 75, 100, 500, 1000];

    if ($('#data').html()) {
        var table = $('#data').dataTable({
            "ajax": {
                "type": "GET",
                "url": ajax_path + 'product.ajax.php' + window.location.search,
                "dataSrc": function(json) {
                    $('#catname').text(json.catname);
                    $('#select_all').prop('checked', false);
                    return json.data;
                }
            },
            "processing": true,
            "serverSide": true,
            "paging": true,
            "ordering": true,
            "order": [[3, "desc"]],
            "info": false,
            "searching": true,
            "lengthMenu": data_length,
            "language": locale.dataTable,
            "stripeClasses": ['data-row', 'data-row'],
            "aoColumnDefs": [{
                    'bSortable': false,
                    'aTargets': ['sorting-hide']
                }]
        });
    }
    $('select[name="fix_products"]').on('change', function () {
        var msg = locale.transfer;
        if($('select[name="fix_products"]').val() == 2) {
            msg = locale.delete;
        }
        $('.fix-products').html('<span class="glyphicon glyphicon-ok"></span> ' + msg);
    });

    $('.fix-products').on('click', function () {
        $.MessageBox({
            buttonDone: "OK",
            buttonFail: locale.cancel,
            message: locale.create_db_dump
        }).done(function() {
            $.ajax({
                mimeType: 'text/html; charset='+locale.charset,
                url: '?path=catalog.list',
                type: 'post',
                data: {
                    'actionList[rowID]': 'actionDeleteProducts',
                    'rowID': 1,
                    'mode': $('select[name="fix_products"]').val(),
                    'ajax': 1
                },
                dataType: "json",
                async: false,
                success: function(json) {
                    if (json['success'] == 1) {
                        showAlertMessage(locale.products_completed + ' ' + json['count']);
                    } else
                        showAlertMessage(locale.save_false, true);
                }
            });
        });
    });
});