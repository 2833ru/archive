
$().ready(function() {

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
                url: '?path=order.select',
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
        data.push({name: 'actionList[selectID]', value: 'actionOption'});

        $.ajax({
            mimeType: 'text/html; charset=windows-1251',
            url: '?path=order.select',
            type: 'post',
            data: data,
            dataType: "html",
            async: false,
            success: function(data) {
                $('#selectModal .modal-dialog').removeClass('modal-lg');
                $('#selectModal .modal-title').html(locale.option_title);
                $('#selectModal .modal-footer .btn-primary').addClass('option-send');
                $('#selectModal .modal-footer .btn-delete').addClass('hidden');
                $('#selectModal .modal-body').html(data);
                $('#selectModal').modal('show');
            }
        });
    });

    // Обзор товара в корзине
    $('body').on('click', ".media-heading > a", function(event) {
        if ($('.bar-tab').is(":visible")) {
            event.preventDefault();
            $(this).closest(".data-row").find(".cart-value-edit").click();

        }
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
                url: '?path=order.select',
                type: 'post',
                data: data,
                dataType: "json",
                async: false,
                success: function() {
                    window.location.href = '?path=order.select';
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
                url: '?path=order.select',
                type: 'post',
                data: data,
                dataType: "html",
                async: false,
                success: function(data) {
                    $('#selectModal .modal-title').html(locale.select_title);
                    $('#selectModal .modal-footer .btn-primary').html(locale.select_edit);
                    $('#selectModal .modal-footer .btn-primary').addClass('edit-select-send');
                    $('#selectModal .modal-body').html(data);
                    $('#selectModal').modal('show');
                }
            });
        }
        else
            alert(locale.select_no);
    });


    // datetimepicker
    if ($(".date").length) {
        $(".date").datetimepicker({
            format: 'dd-mm-yyyy',
            language: 'ru',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0
        });
    }

    // Поиск заказа - очистка
    $(".btn-order-cancel").on('click', function() {
        window.location.replace('?path=order');
    });

    // Поиск заказа
    $(".btn-order-search").on('click', function() {
        $('#order_search').submit();
    });

    // Сделать копию из списка заказов
    $(".dropdown-menu .copy").on('click', function() {
        $(this).attr('href', '?path=order&action=new&id=' + $(this).attr('data-id'));
    });

    // Связь e-mail из списка заказов
    $(".dropdown-menu .email").on('click', function() {
        $(this).attr('href', 'mailto:' + $('#order-' + $(this).attr('data-id') + '-email').html());
    });

    // Печатные бланки
    $(".btn-print-order").on('click', function() {
        window.open($(this).attr('data-option'));
    });

    // Изменить скидку заказа
    $(".discount").on('click', function() {

        var order_id = $('#footer input[name=rowID]').val();
        var data = [];
        data.push({name: 'selectID', value: $('.discount-value').val()});
        data.push({name: 'selectAction', value: 'discount'});
        data.push({name: 'actionList[selectID]', value: 'actionCartUpdate.order.edit'});

        $.ajax({
            mimeType: 'text/html; charset=windows-1251',
            url: '?path=order&id=' + order_id,
            type: 'post',
            data: data,
            dataType: "html",
            async: false,
            success: function() {
                window.location.reload();
            }

        });
    });

    // Добавить в корзину - Поиск
    $("body").on('click', "#selectModal .search-action", function(event) {
        event.preventDefault();

        var data = [];
        data.push({name: 'selectID', value: 1});
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

    // Добавить в корзину товар -  2 шаг
    $("body").on('click', "#selectModal .modal-footer .cart-add-send", function(event) {
        event.preventDefault();
        var count = 0;
        var order_id = $('#footer input[name=rowID]').val();

        // Progress 
        $('#selectModal .modal-footer .btn').addClass('hidden');
        $('.progress').removeClass('hidden');

        // Всего элементов
        $('.cart-list input:text').each(function() {
            if (this.value > 0 || $(this).attr('data-cart') == "true")
                count++;
        });
        var total = $('.progress').width() / count;

        $('.cart-list input:text').each(function() {
            if (this.value > 0 || $(this).attr('data-cart') == "true") {
                var data = [];
                data.push({name: 'selectID', value: $(this).attr('data-id')});
                data.push({name: 'selectNum', value: this.value});
                data.push({name: 'ajax', value: 1});
                data.push({name: 'selectAction', value: 'add'});
                data.push({name: 'actionList[selectID]', value: 'actionCartUpdate.order.edit'});

                $.ajax({
                    mimeType: 'text/html; charset=windows-1251',
                    url: '?path=order&id=' + order_id,
                    type: 'post',
                    data: data,
                    dataType: "html",
                    async: false,
                    success: function() {

                        // Progress 
                        var progress = parseInt($('.progress-bar').css('width').split('px').join(''));
                        $('.progress-bar').css('width', (progress + total) + 'px');

                    }
                });
            }
        });

        $('#selectModal').modal('show');
        $('.progress-bar').css('width', '100%');
        window.location.reload();
    });

    // Управление полем корзины в поиске товара
    $("body").on('click', ".item-minus", function() {
        var id = $(this).attr('data-id');
        var current = $('#select_id_' + id).val();
        current--;
        if (current < 0)
            current = 0;
        else if (isNaN(current))
            current = 0;
        $('#select_id_' + id).val(parseInt(current));
    });

    $("body").on('click', ".item-plus", function() {
        var id = $(this).attr('data-id');
        var current = $('#select_id_' + id).val();
        current++;
        if (isNaN(current))
            current = 1;
        $('#select_id_' + id).val(parseInt(current));
    });


    // Добавить в корзину товар - 1 шаг
    $(".cart-add").on('click', function(event) {
        event.preventDefault();

        var data = [];
        data.push({name: 'selectID', value: 1});
        data.push({name: 'ajax', value: 1});
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
                $('#selectModal .modal-footer .btn-primary').addClass('cart-add-send');
                $('#selectModal .modal-footer .btn-delete').addClass('hidden');
                $('#selectModal .modal-body').css('max-height', ($(window).height() - 200) + 'px');
                $('#selectModal .modal-body').css('overflow-y', 'auto');
                $('#selectModal .modal-body').html(data);
                $('#selectModal').modal('show');
            }
        });
    });

    // Удаление из списка товара заказа корзины
    $(".data-row .cart-value-remove").on('click', function(event) {
        event.preventDefault();

        var order_id = $('#footer input[name=rowID]').val();
        var product_id = $(this).attr('data-id');

        if (confirm(locale.confirm_delete)) {

            var data = [];
            data.push({name: 'selectID', value: product_id});
            data.push({name: 'selectAction', value: 'delete'});
            data.push({name: 'actionList[selectID]', value: 'actionCartUpdate.order.edit'});

            $('#modal-form').attr('action', '?path=order&id=' + order_id);
            $('#modal-form').ajaxSubmit({
                data: data,
                dataType: "json",
                success: function(json) {
                    $('#selectModal').modal('hide');
                    window.location.reload();
                }
            });
        }
    });

    // Удаление товара из заказа модальное окно
    $("body").on('click', "#selectModal .modal-footer .value-delete", function(event) {
        event.preventDefault();

        var product_id = $('.modal-body input[name=rowID]').val();
        var order_id = $('.modal-body input[name=orderID]').val();

        if (confirm(locale.confirm_delete)) {

            var data = [];
            data.push({name: 'selectID', value: product_id});
            data.push({name: 'selectAction', value: 'delete'});
            data.push({name: 'actionList[selectID]', value: 'actionCartUpdate.order.edit'});

            $('#modal-form').attr('action', '?path=order&id=' + order_id);
            $('#modal-form').ajaxSubmit({
                data: data,
                dataType: "json",
                success: function(json) {
                    $('#selectModal').modal('hide');
                    window.location.reload();
                }
            });
        }
    });

    // Редактировать корзину - 2 шаг
    $("body").on('click', "#selectModal .modal-footer .value-edit-send", function(event) {
        event.preventDefault();

        var product_id = $('#modal-form input[name=rowID]').val();
        var order_id = $('#modal-form input[name=orderID]').val();

        var data = [];
        data.push({name: 'selectID', value: product_id});
        data.push({name: 'actionList[selectID]', value: 'actionCartUpdate.order.edit'});
        $('#modal-form .form-control, #modal-form .hidden-edit, #modal-form input:radio:checked, #modal-form input:checkbox:checked').each(function() {
            if ($(this).attr('name') !== undefined) {
                data.push({name: $(this).attr('name'), value: escape($(this).val())});
            }
        });

        $('#modal-form').attr('action', '?path=order&id=' + order_id);
        $('#modal-form').ajaxSubmit({
            data: data,
            dataType: "json",
            success: function(json) {
                $('#selectModal').modal('hide');
                if (json['success'] == 1) {
                    window.location.reload();
                } else
                    showAlertMessage(locale.save_false, true);
            }

        });

    });

    // Редактировать корзину - 1 шаг
    $("body").on('click', ".data-row .cart-value-edit", function(event) {
        event.preventDefault();

        var data = [];
        var id = $(this).attr('data-id');
        var order_id = $('#footer input[name=rowID]').val();
        var parent = $(this).closest('.data-row').attr('data-row');
        data.push({name: 'selectID', value: id});
        data.push({name: 'ajax', value: 1});
        data.push({name: 'actionList[selectID]', value: 'actionValueEdit'});
        data.push({name: 'parentID', value: parent});

        $.ajax({
            mimeType: 'text/html; charset=windows-1251',
            url: '?path=order&id=' + order_id,
            data: data,
            dataType: "html",
            async: false,
            success: function(data) {
                $('#selectModal .modal-dialog').removeClass('modal-lg');
                $('#selectModal .modal-title').html(locale.edit_cart_value);
                $('#selectModal .modal-footer .btn-primary').removeClass('edit-select-send');
                $('#selectModal .modal-footer .btn-primary').addClass('value-edit-send');
                $('#selectModal .modal-footer .btn-delete').removeClass('hidden');
                $('#selectModal .modal-footer .btn-delete').addClass('value-delete');
                $('#selectModal .modal-body').html(data);
                $('#selectModal').modal('show');
            }

        });
    });

    // Экспортировать с выбранными
    $(".select-action .export-select").on('click', function(event) {
        event.preventDefault();

        if ($('input:checkbox:checked').length) {
            var data = [];
            $('input:checkbox:checked').each(function() {
                if (this.value != 'all')
                    data.push({name: 'select[' + $(this).attr('data-id') + ']', value: $(this).attr('data-id')});
            });

            data.push({name: 'selectID', value: 1});
            data.push({name: 'ajax', value: 1});
            data.push({name: 'actionList[selectID]', value: 'actionSelect'});
            $.ajax({
                mimeType: 'text/html; charset=windows-1251',
                url: '?path=exchange.export.order',
                type: 'post',
                data: data,
                dataType: "json",
                async: false,
                success: function() {
                    window.location.href = '?path=exchange.export.order';
                }

            });
        }
        else
            alert(locale.select_no);
    });

    // Настройка bootstrap-select
    $('.selectpicker').selectpicker({
        dropdownAlignRight: true
    });

    // Обновление данных
    $("button[name=editID]").on('click', function() {
        $('#user-data-1 .sidebar-data-0').text($('#product_edit input[name=fio_new]').val());
        $('#user-data-1 .sidebar-data-2').text($('#product_edit input[name=tel_new]').val());
        $('#user-data-2 .sidebar-data-0').text($('#product_edit input[name=fio_new]').val());
        $('#user-data-2 .sidebar-data-1').text($('#product_edit input[name=tel_new]').val());
    });

    // Карта
    if ($('#map').length) {
        ymaps.ready(init);
    }
    function init() {
        ymaps.geocode($('#map').attr('data-geocode'), {results: 1}).then(function(res) {
            var firstGeoObject = res.geoObjects.get(0);
            //res.geoObjects.get(0).properties.set('balloonContentHeader', 'Доставка');
            res.geoObjects.get(0).properties.set('balloonContentBody', $('#map').attr('data-title'));
            window.myMap = new ymaps.Map("map", {
                center: firstGeoObject.geometry.getCoordinates(),
                zoom: 10
            });
            myMap.controls.add('mapTools', {left: 5, top: 5});
            firstGeoObject.options.set('preset', 'twirl#buildingsIcon');
            myMap.geoObjects.add(firstGeoObject);
        });
    }

});