

var lineChartData = {
    datasets: [
        {
            label: "Dataset",
            fillColor: "rgba(151,187,205,0.2)",
            strokeColor: "rgba(151,187,205,1)",
            pointColor: "rgba(151,187,205,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(151,187,205,1)"
        }
    ]
};

$().ready(function() {


    // Экспорт данных
    $(".select-action .export").on('click', function(event) {
        event.preventDefault();

        var data = [];

        if ($("#export").length) {
            $(JSON.parse($("#export").attr('data-export'))).each(function(i, val) {
                data.push({name: 'select[' + val + ']', value: val});
            });
        }

        data.push({name: 'selectID', value: 1});
        data.push({name: 'ajax', value: 1});
        data.push({name: 'actionList[selectID]', value: 'actionSelect'});
        $.ajax({
            mimeType: 'text/html; charset=windows-1251',
            url: '?path=' + $("#export").attr('data-path'),
            type: 'post',
            data: data,
            dataType: "json",
            async: false,
            success: function() {
                window.location.href = '?path=' + $("#export").attr('data-path');
            }

        });

    });

    // Поиск заказа
    $(".btn-order-search").on('click', function() {
        $('#order_search').submit();
    });

    // Поиск заказа - очистка
    $(".btn-order-cancel").on('click', function() {
        window.location.replace('?path=report.statorder');
    });
    
    // Поиск товаров - очистка
    $(".btn-product-cancel").on('click', function() {
        window.location.replace('?path=report.statproduct');
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


    // Круговая диаграмма
    if ($('#chart-area').length) {

        var pieData = [];

        $('#data-value > span').each(function() {
            eval($(this).attr('data-value'));
        });

        eval($("#test").attr('data-value'));
        var ctx = $("#chart-area").get(0).getContext("2d");
        pieChart = new Chart(ctx).Pie(pieData, {
            animation: false,
            responsive: true
        });

        $('.progress').toggle();
    }

    // Линейный график
    if ($('#canvas').length) {
        lineChartData.datasets[0].data = JSON.parse($("#canvas").attr('data-value'));
        lineChartData.labels = JSON.parse($("#canvas").attr('data-label'));
        var currency = $("#canvas").attr('data-currency');

        var ctx = $("#canvas").get(0).getContext("2d");
        lineChart = new Chart(ctx).Line(lineChartData, {
            animation: false,
            responsive: true,
            tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %> " + currency
        });
        $('.progress').toggle();
    }

    $('.canvas-bar').on('click', function(event) {
        event.preventDefault();
        lineChart.destroy();
        $('.progress').toggle();

        lineChart = new Chart(ctx).Bar(lineChartData, {
            animation: false,
            responsive: true,
            tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %> " + currency
        });

        $('ul.select-action > li').removeClass('disabled');
        $(this).parent('li').addClass('disabled');
        $('.progress').toggle();
    });


    $('.canvas-line').on('click', function(event) {
        event.preventDefault();
        lineChart.destroy();
        $('.progress').toggle();

        lineChart = new Chart(ctx).Line(lineChartData, {
            animation: false,
            responsive: true,
            tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %> " + currency
        });

        $('ul.select-action > li').removeClass('disabled');
        $(this).parent('li').addClass('disabled');
        $('.progress').toggle();
    });

    $('.canvas-radar').on('click', function(event) {
        event.preventDefault();
        lineChart.destroy();
        $('.progress').toggle();

        lineChart = new Chart(ctx).Radar(lineChartData, {
            animation: false,
            responsive: true,
            tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %> " + currency
        });

        $('ul.select-action > li').removeClass('disabled');
        $(this).parent('li').addClass('disabled');
        $('.progress').toggle();
    });

    // Добавление в переадресацию с отмеченными
    $('.select-action .add-search-base').on('click', function(event) {
        event.preventDefault();
        var name = new String;
        if ($('input:checkbox:checked').length) {

            $('input:checkbox:checked').each(function() {
                var add = $(this).closest('.data-row').find('td:nth-child(2)>a').html();
                name += add + ',';
            });
            window.location.href = '?path=report.searchreplace&action=new&data[name]=' + name.substring(0, (name.length - 1));
        }
        else
            alert(locale.select_no);
    });

    // Добавление в переадресацию из списка
    $(".data-row .add-search-base").on('click', function(event) {
        event.preventDefault();
        window.location.href = '?path=report.searchreplace&action=new&data[name]=' + $(this).closest('.data-row').find('td:nth-child(2)>a').html();
    });



});