<nav class="visible-xs">
    <ul class="pager">
        <li class="previous"><a href="/shop/CID_@catalogId@.html"><span aria-hidden="true">&larr;</span> @catalogCategory@</a></li>
    </ul>
</nav>
<ol class="breadcrumb hidden-xs">
    @breadCrumbs@
</ol>
<div class="page-header hidden-xs">
    <a href="/price/CAT_SORT_@productId@.html" class="pull-right" title="Прайс-лист @catalogCategory@"><span class="glyphicon glyphicon-list-alt"></span>Прайс-лист каталога</a>
    <h1>@catalogCategory@</h1>
</div>

<div class="hidden-xs">@catalogContent@</div>

<div class="well hidden-xs" id="filter-well">
    <div class="row">
        <div class="col-md-6">
            Вывод товаров:

            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-sm btn-default glyphicon glyphicon-th-list @gridSetAactive@" data-toggle="tooltip" data-placement="top" title="Товары списком">
                    <input type="radio" name="gridChange" value="1">
                </label>
                <label class="btn btn-sm btn-default glyphicon glyphicon-th @gridSetBactive@" data-toggle="tooltip" data-placement="top" title="Товары сеткой">
                    <input type="radio" name="gridChange" value="2">
                </label>
            </div>

        </div>
        <div class="col-md-6 text-right">


            Сортировка:
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-sm btn-default glyphicon glyphicon glyphicon glyphicon-signal @sSetCactive@" data-toggle="tooltip" data-placement="top" title="По умолчанию">
                    <input type="radio" name="s" value="3">
                </label>
                <label class="btn btn-sm btn-default glyphicon glyphicon-sort-by-alphabet @sSetAactive@" data-toggle="tooltip" data-placement="top" title="Наименование">
                    <input type="radio" name="s" value="1">
                </label>
                <label class="btn btn-sm btn-default glyphicon glyphicon glyphicon-sort-by-order @sSetBactive@" data-toggle="tooltip" data-placement="top" title="Цена">
                    <input type="radio" name="s" value="2">
                </label>

            </div>

            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-sm btn-default glyphicon glyphicon-sort-by-attributes @fSetAactive@" data-toggle="tooltip" data-placement="top" title="По возрастанию">
                    <input type="radio" name="f" value="1">
                </label>
                <label class="btn btn-sm btn-default glyphicon glyphicon-sort-by-attributes-alt @fSetBactive@" data-toggle="tooltip" data-placement="top" title="По убыванию">
                    <input type="radio" name="f" value="2">
                </label>
            </div>


        </div>
    </div>
    <a id="sort"></a>
    <form method="post" action="/shop/CID_@productId@@nameLat@.html" name="sort" id="sorttable" class="hide">
        <table><tr>@vendorDisp@ <td>&nbsp;</td><td>@vendorSelectDisp@</td><tr></table>
    </form>

</div>

<div class="template-product-list">@productPageDis@</div>

<div id="ajaxInProgress"></div>
<div class="product-scroll-init"></div>


@productPageNav@


<script type="text/javascript">

    var max_page = new Number('@max_page@');
    var current = '@productPageThis@';
    if (current !== 'ALL')
        var count = new Number('@productPageThis@');
    else
        var count = max_page;

    // Функция подгрузки товаров
    function scroll_loader() {

        if (count < max_page) {

            // Анимация загрузки
            $('#ajaxInProgress').addClass('progress-scroll');

            var next_page = new Number(count) + 1;
            url = "/shop/CID_@pcatalogId@@page_prefix@" + next_page + "@seomod@.html?@page_postfix@" + window.location.hash.split('#').join('').split(']').join('][]');

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    ajax: true
                },
                success: function(data)
                {
                    // Анимация загрузки
                    $('#ajaxInProgress').removeClass('progress-scroll');

                    // Добавляем товары в общему списку
                    $(".template-product-list").append(data);

                    // Выравнивание ячеек товара
                    setEqualHeight($(".thumbnail .description"));

                    count = next_page;
                    $('.pagination li').removeClass('active');
                    $('#paginator-' + count).addClass('active');

                    Waypoint.refreshAll();
                },
                error: function() {
                    $('#ajaxInProgress').removeClass('progress-scroll');
                }
            });
        }
    }

    // Блокировка вывода штатной пагинации [1-10]
    if (AJAX_SCROLL_HIDE_PAGINATOR) {
        $(".pagination").hide();
    }

    $(document).ready(function() {

        var inview = new Waypoint.Inview({
            element: $('.product-scroll-init'),
            enter: function(direction) {
                if (AJAX_SCROLL)
                    scroll_loader();
            }
        });


        $("#slider-range").slider({
            range: true,
            step: 5,
            min: new Number('@price_min@'),
            max: new Number('@price_max@'),
            values: [new Number('@price_min@'), new Number('@price_max@')],
            slide: function(event, ui) {
                $("input[name=min]").val(ui.values[ 0 ]);
                $("input[name=max]").val(ui.values[ 1 ]);
            }
        });
    });
</script>
