<div class="breadcrumb">
    <div> @breadCrumbs@ </div>
</div>
<div class="header_rel" style="padding-right:0px">
    <h1>@catalogCategory@</h1>
    <a href="/price/CAT_SORT_@pcatalogId@.html" title="Прайс-лист каталога @catalogCategory@" class="abs_link">Прайс-лист каталога</a> </div>
<div class="category-info" id="banfx"> @catalogContent@ </div>
<NOINDEX>

    <div class="product-filter" id="filter-well">
        <div class="display">Вывод товаров:&nbsp;&nbsp; 

            <div class="btn-group pull-right">
                <button class="btn @gridSetAactive@" id="g_1" name="gridChange" data-name="g_2" value="1" data-original-title="Товары списком"  rel="tooltip" ><span class="icon-th-list"></span></button>
                <button class="btn @gridSetBactive@" id="g_2"  name="gridChange" data-name="g_1" value="2" data-original-title="Товары сеткой"  rel="tooltip"><span class="icon-th"></span></button>
            </div>
        </div>
        <div class="product-compare" style="display:@compare Enabled@; "><a href="/compare/" id="compare-total">Сравнить товары (<span id="numcompare">@numcompare@</span> шт.)</a></div>
        <div class="sort"> Сортировка:&nbsp;&nbsp; 
            <div class="btn-group pull-right">
                <button class="btn @sSetAactive@" id="s_1"  name="s" data-name="s_2" value="1" data-original-title="Наименование"  rel="tooltip"><span class="fa fa-sort-alpha-asc"></span></button>
                <button class="btn @sSetBactive@" id="s_2"  name="s" data-name="s_1" value="2" data-original-title="Цена"  rel="tooltip"><span class="fa fa-sort-numeric-asc"></span></button>

                <button class="btn @fSetAactive@" id="f_1"  name="f" data-name="f_2" value="1" data-original-title="По возрастанию"  rel="tooltip"><span class="fa fa-sort-amount-asc"></span></button>
                <button class="btn @fSetBactive@" id="f_2"  name="f" data-name="f_1" value="2" data-original-title="По убыванию"  rel="tooltip"><span class="fa fa-sort-amount-desc"></span></button>
            </div>
        </div>
        <div style="clear:both"></div>
        <a name="sort"></a>
        <table cellpadding="0" cellspacing="0" border="0" class="vendorDisp hide" id="sorttable">
            <tr>
                <td><table border="0" cellspacing="0" cellpadding="0">
                        <tr> @vendorDisp@ </tr>
                    </table></td>

            </tr>
            <tr><td >@vendorSelectDisp@</td></tr>
        </table>
    </div>

</NOINDEX>
<div class="product-grid">
    <table width="100%"  border="0" cellspacing="0" cellpadding="0" id="product-scroll">
        @productPageDis@
    </table>
</div>
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
                    // Добавляем товары в общему списку
                    $("#product-scroll").append(data);

                    count = next_page;

                    $('.pagination li').removeClass('active');
                    $('#paginator-' + count).addClass('active');

                    Waypoint.refreshAll();
                }
            });
        }
    }

    // Блокировка вывода штатной пагинации [1-10]
    if (AJAX_SCROLL_HIDE_PAGINATOR) {
        $(".navi").hide();
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

