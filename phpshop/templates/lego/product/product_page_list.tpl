<span class="text-center banner">
    @sticker_banner@
</span><style>.spec{display:none}</style>

@ProductCatalogContent@

<!-- Виртуальный каталог -->
@vendorCatDisp@
<!--/ Виртуальный каталог -->
@catalogOption1@

<!-- Filter -->
@filter@
<!--/ Filter -->

<div class="template-product-list">@productPageDis@</div>

<div id="ajaxInProgress"></div>
<div class="product-scroll-init"></div>
@productPageNav@

<script type="text/javascript">
    $(".pagination").hide();

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
                success: function (data)
                {
                    // Анимация загрузки
                    $('#ajaxInProgress').removeClass('progress-scroll');

                    // Добавляем товары в общему списку
                    $(".template-product-list").append(data);

                    // Выравнивание ячеек товара
                    setEqualHeight(".thumbnail .description");
                    // Коррекция знака рубля
                    //setRubznak();

                    count = next_page;
                    $('.pagination li').removeClass('active');
                    $('#paginator-' + count).addClass('active');

                    $(window).lazyLoadXT();
                    Waypoint.refreshAll();
                },
                error: function () {
                    $('#ajaxInProgress').removeClass('progress-scroll');
                }
            });
        }
    }

    var price_min = new Number('@price_min@');
    var price_max = new Number('@price_max@');

    $(document).ready(function () {

        // Блокировка вывода штатной пагинации [1-10]
        if (AJAX_SCROLL_HIDE_PAGINATOR == false) {
            $(".pagination").show();
        }

        var inview = new Waypoint.Inview({
            element: $('.product-scroll-init'),
            enter: function (direction) {
                if (AJAX_SCROLL)
                    scroll_loader();
            }
        });


        $("#slider-range").slider({
            range: true,
            step: 5,
            min: new Number('@price_min@'),
            max: new Number('@price_max@'),
            values: [price_min, price_max],
            slide: function (event, ui) {
                $("input[name=min]").val(ui.values[ 0 ]);
                $("input[name=max]").val(ui.values[ 1 ]);
            }
        });
    });
</script>
