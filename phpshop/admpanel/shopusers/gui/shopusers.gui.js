
$().ready(function() {
    
    // Расширенный поиск сообщений
    $(".search").on('click', function(event) {
        event.preventDefault();

        var data = [];
        data.push({name: 'selectID', value: 1});
        data.push({name: 'ajax', value: 1});
        data.push({name: 'cat', value: $.cookie('cat')});
        data.push({name: 'actionList[selectID]', value: 'actionAdvanceSearch'});

        $.ajax({
            mimeType: 'text/html; charset='+locale.charset,
            url: '?path=shopusers.messages',
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
    
    // Разослать уведомления автоматически
    $("body").on('click', ".select-action .send-user-all", function(event) {
        event.preventDefault();

        if (confirm(locale.confirm_notice)) {

            var data = [];
            data.push({name: 'saveID', value: 1});
            data.push({name: 'actionList[saveID]', value: 'actionUpdateAuto'});

            $.ajax({
                mimeType: 'text/html; charset='+locale.charset,
                url: '?path=shopusers.notice&id=1',
                type: 'post',
                data: data,
                dataType: "json",
                async: false,
                success: function(json) {
                    if (json['success'] == 1) {
                        showAlertMessage(locale.save_done);
                    }
                    else
                        showAlertMessage(locale.save_false, true);
                }
            });
        }
    });

    // Разослать уведомления с выбранными
    $("body").on('click', ".select-action .send-user-select", function(event) {
        event.preventDefault();
        var result = 1;
        if ($('#data input:checkbox:checked').length) {
            if (confirm(locale.confirm_notice)) {
                $('#data input[name="items"]:checkbox:checked').each(function() {

                    var data = [];
                    var id = $(this).val();
                    data.push({name: 'saveID', value: 1});
                    data.push({name: 'rowID', value: id});
                    data.push({name: 'email', value: $(this).closest('.data-row').find('td:nth-child(5)>a').html()});
                    data.push({name: 'productID', value: $(this).closest('.data-row').find('td:nth-child(4)').html()});

                    data.push({name: 'actionList[saveID]', value: 'actionUpdate'});

                    $.ajax({
                        mimeType: 'text/html; charset='+locale.charset,
                        url: '?path=shopusers.notice&id=' + id,
                        type: 'post',
                        data: data,
                        dataType: "json",
                        async: false,
                        success: function(json) {
                            if (json['success'] != 1) {
                                result = 0;
                                showAlertMessage(locale.save_false, true);
                            }
                        }
                    });
                });

                if (result == 1)
                    showAlertMessage(locale.save_done);
            }
        }
        else
            alert(locale.select_no);

    });

    $("body").on('click', ".send-user", function(event) {
        event.preventDefault();
        var result = 1;
        var data = [];
        var id = $(this).attr('data-id');

        data.push({name: 'saveID', value: 1});
        data.push({name: 'rowID', value: id});
        data.push({name: 'email', value: $(this).closest('.data-row').find('td:nth-child(5)>a').html()});
        data.push({name: 'productID', value: $(this).closest('.data-row').find('td:nth-child(4)').html()});
        data.push({name: 'actionList[saveID]', value: 'actionUpdate'});

        $.ajax({
            mimeType: 'text/html; charset='+locale.charset,
            url: '?path=shopusers.notice&id=' + id,
            type: 'post',
            data: data,
            dataType: "json",
            async: false,
            success: function(json) {
                if (json['success'] != 1) {
                    result = 0;
                    showAlertMessage(locale.save_false, true);
                }
            }
        });
        if (result == 1)
            showAlertMessage(locale.save_done);

    });

    // Сделать новый заказ из списка пользователей
    $(".dropdown-menu .order").on('click', function() {
        $(this).attr('href', '?path=order&action=new&user=' + $(this).attr('data-id'));
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
                mimeType: 'text/html; charset='+locale.charset,
                url: '?path=exchange.export.user',
                type: 'post',
                data: data,
                dataType: "json",
                async: false,
                success: function() {
                    window.location.href = '?path=exchange.export.user';
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
    $(".comment-url").on('click', function(event) {
        event.preventDefault();
        var table = $('#data').DataTable();
        var id = $(this).closest('.data-row').find('td:nth-child(3)>a').html();
        table.search(id).draw();
    });


    // Карта доставки
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
            myMap.controls
                    .add('mapTools', {left: 5, top: 5});
            firstGeoObject.options.set('preset', 'twirl#buildingsIcon');
            myMap.geoObjects.add(firstGeoObject);
        });
    }

    // Таблица данных
    if ($.getUrlVar('path') == 'shopusers') {
        if (typeof($.cookie('data_length')) == 'undefined')
            var data_length = [10, 25, 50, 75, 100, 500];
        else
            var data_length = [parseInt($.cookie('data_length')), 10, 25, 50, 75, 100, 500];

        if ($('#data').html()) {
            var table = $('#data').dataTable({
                "ajax": {
                    "type": "GET",
                    "url": ajax_path + 'shopusers.ajax.php' + window.location.search,
                    "dataSrc": function(json) {
                        $('#stat_sum').text(json.sum);
                        $('#stat_num').text(json.num);
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
    }

});