
// добавление товара в корзину
function addToCartList(product_id, num, parent, addname) {

    if (num === undefined)
        num = 1;

    if (addname === undefined)
        addname = '';

    if (parent === undefined)
        parent = 0;

    $.ajax({
        url: ROOT_PATH + '/phpshop/ajax/cartload.php',
        type: 'post',
        data: 'xid=' + product_id + '&num=' + num + '&type=json&addname=' + addname + '&xxid=' + parent,
        dataType: 'json',
        success: function (json) {
            if (json['success']) {
                showAlertMessage(json['message']);
                $("#num, #mobilnum").html(json['num']);
                $("#sum").html(json['sum']);
                $("#bar-cart, #order").addClass('active');
            }
        }
    });
}

// добавление товара в корзину
function addToCompareList(product_id) {

    $.ajax({
        url: ROOT_PATH + '/phpshop/ajax/compare.php',
        type: 'post',
        data: 'xid=' + product_id + '&type=json',
        dataType: 'json',
        success: function (json) {
            if (json['success']) {
                showAlertMessage(json['message']);
                $("#numcompare").html(json['num']);
            }
        }
    });
}


// Фотогалерея
function fotoload(xid, fid) {

    $.ajax({
        url: ROOT_PATH + '/phpshop/ajax/fotoload.php',
        type: 'post',
        data: 'xid=' + xid + '&fid=' + fid + '&type=json',
        dataType: 'json',
        success: function (json) {
            if (json['success']) {
                $("#fotoload").fadeOut('slow', function () {
                    $("#fotoload").html(json['foto']);
                    $("#fotoload").fadeIn('slow');
                });
            }
        }
    });
}

// оформление кнопок
$(".ok").addClass('btn btn-default btn-sm');
$("input:button").addClass('btn btn-default btn-sm');
$("input:submit").addClass('btn btn-primary');
$("input:text,input:password, textarea").addClass('form-control');


// Активная кнопка
function ButOn(Id) {
    Id.className = 'imgOn';
}

function ButOff(Id) {
    Id.className = 'imgOff';
}

function ChangeSkin() {
    document.SkinForm.submit();
}

// Смена валюты
function ChangeValuta() {
    document.ValutaForm.submit();
}

// Создание ссылки для сортировки
function ReturnSortUrl(v) {
    var s, url = "";
    if (v > 0) {
        s = document.getElementById(v).value;
        if (s != "")
            url = "v[" + v + "]=" + s + "&";
    }
    return url;
}

// Проверка наличия файла картинки, прячем картинку
function NoFoto2(obj) {
    obj.height = 0;
    obj.width = 0;
}

// Проверка наличия файла картинки, вставляем заглушку
function NoFoto(obj, pathTemplate) {
    obj.src = pathTemplate + '/images/shop/no_photo.gif';
}

// Сортировка по всем фильтрам
function GetSortAll() {
    var url = ROOT_PATH + "/shop/CID_" + arguments[0] + ".html?";

    var i = 1;
    var c = arguments.length;

    for (i = 1; i < c; i++)
        if (document.getElementById(arguments[i]))
            url = url + ReturnSortUrl(arguments[i]);

    location.replace(url.substring(0, (url.length - 1)) + "#sort");

}

// Инициализируем таблицу перевода на русский
var trans = [];
for (var i = 0x410; i <= 0x44F; i++)
    trans[i] = i - 0x350; // А-Яа-я
trans[0x401] = 0xA8;    // Ё
trans[0x451] = 0xB8;    // ё

// Таблица перевода на украинский
/*
 trans[0x457] = 0xBF;    // ї
 trans[0x407] = 0xAF;    // Ї
 trans[0x456] = 0xB3;    // і
 trans[0x406] = 0xB2;    // І
 trans[0x404] = 0xBA;    // є
 trans[0x454] = 0xAA;    // Є
 */

// Сохраняем стандартную функцию escape()
var escapeOrig = window.escape;

// Переопределяем функцию escape()
window.escape = function (str)
{

    if (locale.charset == 'utf-8')
        return str;

    else {
        var str = String(str);
        var ret = [];
        // Составляем массив кодов символов, попутно переводим кириллицу
        for (var i = 0; i < str.length; i++)
        {
            var n = str.charCodeAt(i);
            if (typeof trans[n] != 'undefined')
                n = trans[n];
            if (n <= 0xFF)
                ret.push(n);
        }
        return escapeOrig(String.fromCharCode.apply(null, ret));
    }
};

// Перевод раскладки в русскую
function auto_layout_keyboard(str) {
    replacer = {
        "q": "й", "w": "ц", "e": "у", "r": "к", "t": "е", "y": "н", "u": "г",
        "i": "ш", "o": "щ", "p": "з", "[": "х", "]": "ъ", "a": "ф", "s": "ы",
        "d": "в", "f": "а", "g": "п", "h": "р", "j": "о", "k": "л", "l": "д",
        ";": "ж", "'": "э", "z": "я", "x": "ч", "c": "с", "v": "м", "b": "и",
        "n": "т", "m": "ь", ",": "б", ".": "ю", "/": "."
    };

    return str.replace(/[A-z/,.;\'\]\[]/g, function (x) {
        return x == x.toLowerCase() ? replacer[ x ] : replacer[ x.toLowerCase() ].toUpperCase();
    });
}


// Ajax фильтр обновление данных
function filter_load(filter_str, obj) {

    $.ajax({
        type: "POST",
        url: '?' + filter_str.split('#').join(''),
        data: {
            ajax: true
        },
        success: function (data) {
            $(".template-product-list").html(data);
            $('#price-filter-val-max').removeClass('has-error');
            $('#price-filter-val-min').removeClass('has-error');

            // Выравнивание ячеек товара
            setEqualHeight('.product-description');
            setEqualHeight('.product-name-fix');
            setEqualHeight('.product-block-wrapper .description-content');
            setEqualHeight('.product-col:not(.product-day, .page)');
            setEqualHeight(".caption .description-content");
            setEqualHeight('.stock');
            // lazyLoad
            setTimeout(function () {
                $(window).lazyLoadXT();
            }, 50);
            // Сброс Waypoint
            Waypoint.refreshAll();
        },
        error: function (data) {
            $(obj).attr('checked', false);
            //$(obj).attr('disabled', true);

            if ($(obj).attr('name') == 'max')
                $('#price-filter-val-max').addClass('has-error');
            if ($(obj).attr('name') == 'min')
                $('#price-filter-val-min').addClass('has-error');

            window.location.hash = window.location.hash.split($(obj).attr('data-url') + '&').join('');
        }


    });
}

// Ценовой слайдер
function price_slider_load(min, max, obj) {

    if (Number(min) === 0) {
        min = 1;
    }

    var hash = window.location.hash.split('min=' + $.cookie('slider-range-min') + '&').join('');
    hash = hash.split('max=' + $.cookie('slider-range-max') + '&').join('');
    hash += 'min=' + min + '&max=' + max + '&';
    window.location.hash = hash;

    filter_load(hash, obj);

    $.cookie('slider-range-min', min);
    $.cookie('slider-range-max', max);

    $(".pagination").hide();

}

// Ajax фильтр событие клика
function faset_filter_click(obj) {

    if (AJAX_SCROLL) {

        $(".pagination").hide();

        if ($(obj).prop('checked')) {
            window.location.hash += $(obj).attr('data-url') + '&';

        } else {
            window.location.hash = window.location.hash.split($(obj).attr('data-url') + '&').join('');
            if (window.location.hash == '')
                $('html, body').animate({scrollTop: $("a[name=sort]").offset().top - 100}, 500);

        }

        filter_load(window.location.hash.split(']').join('][]'), obj);
    } else {

        var href = window.location.href.split('?')[1];

        if (href == undefined)
            href = '';


        if ($(obj).prop('checked')) {
            var last = href.substring((href.length - 1), href.length);
            if (last != '&' && last != '')
                href += '&';

            href += $(obj).attr('data-url').split(']').join('][]') + '&';

        } else {
            href = href.split($(obj).attr('data-url').split(']').join('][]') + '&').join('');
        }

        window.location.href = '?' + href;
    }

}

// Выравнивание ячеек товара
function setEqualHeight(columns) {

    $(columns).closest('.row ').each(function () {
        var tallestcolumn = 0;

        $(this).find(columns).each(function () {
            var currentHeight = $(this).height();
            if (currentHeight > tallestcolumn) {
                tallestcolumn = currentHeight;
            }
        });

        if (tallestcolumn > 0) {
            $(this).find(columns).css('min-height', tallestcolumn);
        }
    });

}

// Коррекция знака рубля
function setRubznak() {
    $('.rubznak').each(function () {
        if ($(this).html() == 'руб.' || $(this).html() == 'руб' || $('this').html() == 'p') {
            $(this).html('p');
        }
    });
}
function fasetFilterFix() {
    $('#faset-filter-body').on('click', 'h4', function () {
        if ($(this).parents('.faset-filter-block-wrapper').hasClass('active')) {
            $(this).parents('.faset-filter-block-wrapper').removeClass('active');
        } else {
            $(this).parents('.faset-filter-block-wrapper').addClass('active');
        }
    });
    $('.filter-title').on('click', function () {
        if ($('.filter-body-fix').hasClass('active')) {
            $('.filter-body-fix').removeClass('active');
        } else {
            $('.filter-body-fix').addClass('active');
            $('.filter-body-fix').addClass('active');
        }
    });
    $('#price-filter-body h4').on('click', function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $('#price-filter-form').removeClass('active');
            $('#price-filter-body').removeClass('active');
        } else {
            $(this).addClass('active');
            $('#price-filter-form').addClass('active');
            $('#price-filter-body').addClass('active');
        }
    });
}
function productPageSelect() {
    $('.table-optionsDisp select').each(function () {
        var selectID = $(this).attr('id');
        $('.product-page-option-wrapper').append('<div class="product-page-select ' + selectID + '""></div>')
        $(this).children('option').each(function () {
            var optionValue = $(this).attr('value');
            var optionHtml = $(this).html();
            $('.' + selectID + '').append('<div class="select-option" value="' + optionValue + '">' + optionHtml + '</div>')
        })
    });

    $('.select-option').on('click', function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            var optionInputValue = [];
            $('.product-page-select .select-option.active').each(function () {
                optionInputValue.unshift($(this).attr('value'));
            });
            var optionInputNewValue = optionInputValue.join();
            $('.product-page-option-wrapper input').attr('value', optionInputNewValue);
        } else {

            $(this).siblings().removeClass('active');
            $(this).addClass('active');
            var optionInputValue = [];
            $('.product-page-select .select-option.active').each(function () {
                optionInputValue.unshift($(this).attr('value'));
            });
            var optionInputNewValue = optionInputValue.join('');
            $('.product-page-option-wrapper input').attr('value', optionInputNewValue);
        }
    });
}
function mainNavMenuFix() {
    var body_width = $('body').width();
    if (body_width > 768) {
        var nav_weight = $('.main-navbar-top').width();
        var full_weight = 0;
        $('.main-navbar-top > li').each(function () {
            full_weight += $(this).width();
        });
        var menu_content = ('<div class="additional-nav-menu"><a href="#" class="dropdown-toggle link" data-toggle="dropdown" role="button" aria-expanded="false"><i class="icon icon-menu"></i></a><ul class="dropdown-menu dropdown-menu-right aditional-link" role="menu"></ul></div>');
        if ($('.header-menu-wrapper').find('.additional-nav-menu')) {
            var nav_weight_fix = nav_weight - 46;
        }
        if (nav_weight < full_weight) {
            var nav_weight_fix = nav_weight - 46;
            if ($('.header-menu-wrapper').find('.additional-nav-menu')) {
                $('.header-menu-wrapper > .row').append(menu_content);
            }

            while (nav_weight_fix < full_weight) {
                $('.main-navbar-top > li:last-child').appendTo('.aditional-link');
                var full_weight = 0;
                $('.main-navbar-top > li').each(function () {
                    full_weight += $(this).width();
                });
            }

        }
        $('.main-navbar-top').addClass('active');
    }
}
function productPageSliderImgFix() {
    var block_height = $('.bx-wrapper .bx-viewport').height();
    var block_height_fix = block_height + 'px';
    $('.bx-wrapper .bx-viewport .bxslider > div > a').css('line-height', block_height_fix);

}
function productPageModalImgFix() {
    var block_height = $('.bx-wrapper .bx-viewport').height();
    var block_height_fix = block_height + 'px';
    $('.bxsliderbig  a').css('line-height', block_height_fix);

}
$(document).ajaxStop(function () {


    $('[data-toggle="tooltip"]').tooltip({container: 'body'});

    setEqualHeight('.stock');
    setTimeout(function () {
        setEqualHeight('.product-name-fix');

    }, 600)
    setEqualHeight('.product-block-wrapper .description-content');
    setEqualHeight('.product-col:not(.product-day, .page)');
    setEqualHeight(".caption .description-content");


});

$(document).ready(function () {
    // Скрыть пустые блоки в описании товара
    $('.empty-check').each(function () {
        if ($(this).find('panel-smart').html() === undefined && $(this).find('.vendorenabled').html() === undefined) {
            $(this).fadeOut('slow');
        }
    });
    if ($(".carousel-inner .item+.item").length) {

        $(".carousel-control, .carousel-indicators").css("visibility", "visible")
    }
    $('#sliderModal').on('show.bs.modal', function () {
        $('.modal .modal-body').css('overflow-y', 'hidden');
        setTimeout(function () {


            $('.modal .modal-body img').css("max-height", $(window).height() * 0.66);
            $('.modal .modal-body .bx-viewport').css("max-height", $(window).height() * 0.66);
            $('.modal .modal-body .bx-viewport').css("opacity", "1")

        }, 600);
        $('.modal .modal-body').css('max-height', $(window).height() * 0.8);
    });

    mainNavMenuFix();


    // формат ввода телефона
    $( "form[name='forma_order'], input[name=returncall_mod_tel],input[name=tel],input[name=oneclick_mod_tel]").on("click", function () {
        if (PHONE_FORMAT && PHONE_MASK) {
            $("input[name=tel_new], input[name=returncall_mod_tel],input[name=tel],input[name=oneclick_mod_tel]").mask(PHONE_MASK);
        }
    });

    /*
     setTimeout(function () {
     $('input[name="tel_new"]').mask("+7 (999) 999-99-99");
     
     $('input[name="tel_new"]').on('keyup', function (event) {
     reserveVal = $(this).cleanVal();
     phone = $(this).cleanVal().slice(0, 10);
     $(this).val($(this).masked(phone));
     if ($(this).cleanVal()[1] == '9') {
     if ($(this).cleanVal()[0] == '8' || $(this).cleanVal()[0] == '7') {
     phone = reserveVal.slice(1);
     $(this).val($(this).masked(phone));
     }
     }
     });
     }, 2000);*/

    setEqualHeight('.product-name-fix');
    setEqualHeight('.stock');
    setTimeout(function () {

        setEqualHeight(".prod-title");
        $('.prod-title').css('opacity', '1')
        setEqualHeight(".prod-photo");
        setEqualHeight(".product-name");

    }, 600)
    setTimeout(function () {
        setEqualHeight(".prod-desc");
        $('.compare-slider').css('opacity', '1')
        $('.prod-desc').css('opacity', '1')
        setEqualHeight(".prod-sort");
    }, 600);

    setEqualHeight('.product-block-wrapper .description-content');
    setEqualHeight('.product-col:not(.product-day, .page)');
    setEqualHeight(".caption .description-content");

    productPageSelect();
    // Коррекция знака рубля
    //setRubznak();

    // логика кнопки оформления заказа 
    $("button.orderCheckButton").on("click", function (e) {
        e.preventDefault();
        OrderChekJq();
    });


    // Корректировка стилей меню
    $('.mega-more-parent').each(function () {
        if ($(this).hasClass('hide') || $(this).hasClass('hidden'))
            $(this).prev().removeClass('template-menu-line');
    });

    // Вывод всех категорий в мегаменю
    $('.mega-more').on('click', function (event) {
        event.preventDefault();
        $(this).hide();
        $(this).closest('.mega-menu-block').find('.template-menu-line').removeClass('hide');
    });


    // Направление сортировки в брендах
    $('#filter-selection-well input:radio').on('change', function () {
        window.location.href = $(this).attr('data-url');
    });

    $('#price-filter-body input').on('change', function () {
        if (AJAX_SCROLL) {
            price_slider_load($('#price-filter-body input[name=min]').val(), $('#price-filter-body input[name=max]').val(), $(this));
        } else {
            $('#price-filter-form').submit();
        }
    });

    // Ценовой слайдер
    $("#slider-range").on("slidestop", function (event, ui) {

        if (AJAX_SCROLL) {

            // Сброс текущей страницы
            count = current;

            price_slider_load(ui.values[ 0 ], ui.values[ 1 ]);
        } else {
            $('#price-filter-form').submit();
        }
    });

    // Фасетный фильтр
    if (FILTER && $("#sorttable table td").html()) {
        $("#faset-filter-body").html($("#sorttable table td").html());
        $("#faset-filter").removeClass('hide');
    } else {

        $("#faset-filter").hide();
    }

    if (!FILTER) {
        $("#faset-filter").hide();
        $("#sorttable").removeClass('hide');
    }

    // Направление сортировки
    $('#filter-well input:radio').on('change', function () {
        if (AJAX_SCROLL) {

            count = current;

            window.location.hash = window.location.hash.split($(this).attr('name') + '=1&').join('');
            window.location.hash = window.location.hash.split($(this).attr('name') + '=2&').join('');
            window.location.hash += $(this).attr('name') + '=' + $(this).attr('value') + '&';

            filter_load(window.location.hash);
        } else {

            var href = window.location.href.split('?')[1];

            if (href == undefined)
                href = '';

            var last = href.substring((href.length - 1), href.length);
            if (last != '&' && last != '')
                href += '&';

            href = href.split($(this).attr('name') + '=1&').join('');
            href = href.split($(this).attr('name') + '=2&').join('');
            href += $(this).attr('name') + '=' + $(this).attr('value');
            window.location.href = '?' + href;
        }
    });

    // Загрузка результата отбора при переходе
    if (window.location.hash != "" && $("#sorttable table td").html()) {

        var filter_str = window.location.hash.split(']').join('][]');

        // Загрузка результата отборки
        filter_load(filter_str);

        // Проставление чекбоксов
        $.ajax({
            type: "POST",
            url: '?' + filter_str.split('#').join(''),
            data: {
                ajaxfilter: true
            },
            success: function (data)
            {
                if (data) {
                    $("#faset-filter-body").html(data);
                    $("#faset-filter-body").html($("#faset-filter-body").find('td').html());
                }
            }
        });
    }

    // Ajax фильтр
    $('#faset-filter-body').on('change', 'input:checkbox', function () {

        // Сброс текущей страницы
        count = current;

        faset_filter_click($(this));
    });

    // Сброс фильтра
    $('#faset-filter-reset').on('click', function (event) {
        if (AJAX_SCROLL) {
            event.preventDefault();
            $("#faset-filter-body").html($("#sorttable table td").html());
            filter_load('');
            $('html, body').animate({scrollTop: $("a[name=sort]").offset().top - 100}, 500);
            window.location.hash = '';
            $.removeCookie('slider-range-min');
            $.removeCookie('slider-range-max');
            $(".pagination").show();
            $("#slider-range").slider("option", "values", [price_min, price_max]);

            // Сброс текущей страницы
            count = current;
            $('#faset-filter-body > .faset-filter-block-wrapper:first-child').addClass('active');
        }
    });

    // Пагинация товаров
    $('.pagination a').on('click', function (event) {
        if (AJAX_SCROLL) {
            event.preventDefault();
            window.location.href = $(this).attr('href') + window.location.hash;
        }
    });

    // toTop
    $('#toTop').on('click', function (event) {
        event.preventDefault();
        $('html, body').animate({scrollTop: $("header").offset().top - 100}, 500);
    });

    // закрепление навигации
    /*$('.breadcrumb, .slider').waypoint(function() {
     if (FIXED_NAVBAR){
     $('#navigation').toggleClass('navbar-fixed-top');
     }
     
     // toTop          
     $('#toTop').fadeToggle();
     });*/

    // быстрый переход
    $(document).on('keydown', function (e) {
        if (e == null) { // ie
            key = event.keyCode;
            var ctrl = event.ctrlKey;
        } else { // mozilla
            key = e.which;
            var ctrl = e.ctrlKey;
        }
        if ((key == '123') && ctrl)
            window.location.replace(ROOT_PATH + '/phpshop/admpanel/');
        if (key == '120') {
            $.ajax({
                url: ROOT_PATH + '/phpshop/ajax/info.php',
                type: 'post',
                data: 'type=json',
                dataType: 'json',
                success: function (json) {
                    if (json['success']) {
                        confirm(json['info']);
                    }
                }
            });
        }
    });

    // выбор каталога поиска
    $(".cat-menu-search").on('click', function () {
        $('#cat').val($(this).attr('data-target'));
        $('#catSearchSelect').html($(this).html());
    });

    // увеличение изображения товара
    $("body").on('click', '.highslide', function () {
        return hs.expand(this);
    });

    // ошибка загрузки изображения
    $('.image').on('error', function () {
        $(this).attr('src', '/phpshop/templates/astero/images/shop/no_photo.gif');
        return true;
    });

    // подгрузка комментариев
    $("body").on('click', '#commentLoad', function () {
        commentList($(this).attr('data-uid'), 'list');
    });

    // убираем пустые закладки подробного описания
    if ($('#files').html() != 'Нет файлов')
        $('#filesTab').addClass('show');

    if ($('#vendorenabled').html() != '')
        $('#settingsTab').addClass('show');

    if ($('#pages').html() != '')
        $('#pagesTab').addClass('show');

    /*
     if ($('#vendorActionButton').val() == 'Применить') {
     $('#sorttable').addClass('show');
     }*/

    // Иконки в основном меню категорий
    if (MEGA_MENU_ICON === false) {
        $('.mega-menu-block img').hide();
    }

    // убираем меню брендов
    if (BRAND_MENU === false) {
        $('#brand-menu').hide();
    }

    if (CATALOG_MENU === false) {
        $('#catalog-menu').hide();
    } else {
        $('#catalog-menu').removeClass('hide');
    }

    // добавление в корзину
    $('body').on('click', '.addToCartList', function () {
        addToCartList($(this).attr('data-uid'), $(this).attr('data-num'));
        $(this).attr('disabled', 'disabled');
        $(this).addClass('btn-success');
        $('#order').addClass('active');
        if ($(this).attr('data-cart') !== undefined)
            $(this).find('.fa-shopping-cart').next('span').text($(this).attr('data-cart'));
    });

    // изменение количества товара для добавления в корзину
    $('body').on('change', '.addToCartListNum', function () {
        var num = (Number($(this).val()) || 1);
        var id = $(this).attr('data-uid');
        /*
         if (num > 0 && $('.addToCartList').attr('data-uid') === $(this).attr('data-uid'))
         $('.addToCartList').attr('data-num', num);*/
        if (num > 0) {
            $(".addToCartList").each(function () {
                if ($(this).attr('data-uid') === id)
                    $('.addToCartList[data-uid=' + id + ']').attr('data-num', num);
            });
        }

    });

    // добавление в корзину подтипа
    $(".addToCartListParent").on('click', function () {
        addToCartList($('input[name="parentIdNt"]:checked').val(), $(this).attr('data-num'), $(this).attr('data-parent'));

    });

    $('input[name="parentIdNt"]').on('change', function () {
        $('[itemprop="price"]').html($(this).attr('data-price'));
    });

    // добавление в корзину опции
    $(".addToCartListOption").on('click', function () {
        addToCartList($(this).attr('data-uid'), $(this).attr('data-num'), $(this).attr('data-uid'), $('#allOptionsSet' + $(this).attr('data-uid')).val());
    });

    // добавление в wishlist
    $('body').on('click', '.addToWishList', function () {
        addToWishList($(this).attr('data-uid'));
    });

    // добавление в compare
    $('body').on('click', '.addToCompareList', function () {
        addToCompareList($(this).attr('data-uid'));
    });

    // отправка сообщения администратору из личного кабинета
    $("#CheckMessage").on('click', function () {
        if ($("#message").val() != '')
            $("#forma_message").submit();
    });

    // Визуальная корзина
    $("#cartlink").on('click', function () {
        if ($(this).attr('data-content') == "") {
            window.location.href = $('#body').attr('data-dir') + '/order/';
        }
    });

    $('[data-toggle="popover"]').popover();
    $('a[data-toggle="popover"]').on('show.bs.popover', function () {
        $('a[data-toggle="popover"]').attr('data-content', $("#visualcart_tmp").html());
    });


    $("[data-source]").on('click', function (event) {
        if (event.ctrlKey) {
            event.preventDefault();
            window.open('/phpshop/admpanel/admin.php?path=tpleditor&name=bootstrap&option=pro&file=/' + $(this).attr('data-source'));
        }
    });

    // Подсказки 
    $('[data-toggle="tooltip"]').tooltip({container: 'body'});

    // Стилизация select
    $('.selectpicker').selectpicker({
        width: "auto"
    });

    // Переход из прайса на форму с описанием
    $('#price-form').on('click', function (event) {
        event.preventDefault();
        if ($(this).attr('data-uid') != "" && $(this).attr('data-uid') != "ALL")
            window.location.replace("../shop/CID_" + $(this).attr('data-uid') + ".html");
    });

    // Ajax поиск
    $("#search").on('input', function () {
        var words = $(this).val();
        if (words.length > 2) {
            $.ajax({
                type: "POST",
                url: ROOT_PATH + "/search/",
                data: {
                    words: escape(words + ' ' + auto_layout_keyboard(words)),
                    set: 2,
                    ajax: true
                },
                success: function (data)
                {

                    // Результат поиска
                    if (data != 'false') {

                        if (data != $("#search").attr('data-content')) {
                            $("#search").attr('data-content', data);

                            $("#search").popover('show');
                        }
                    } else
                        $("#search").popover('hide');
                }
            });
        } else {
            $("#search").attr('data-content', '');
            $("#search").popover('hide');
        }
    });

    // Повторная авторизация
    if ($('#usersError').html()) {
        $('form[name=user_forma] .form-group').addClass('has-error has-feedback');
        $('form[name=user_forma] .glyphicon').removeClass('hide');
        $('#userModal').modal('show');
        $('#userModal').on('shown.bs.modal', function () {

        });
    }

    // Проверка синхронности пароля регистрации
    $("form[name=user_forma_register] input[name=password_new2]").on('blur', function () {
        if ($(this).val() != $("form[name=user_forma_register] input[name=password_new]").val()) {
            $('form[name=user_forma_register] #check_pass').addClass('has-error has-feedback');
            $('form[name=user_forma_register] .glyphicon').removeClass('hide');
        } else {
            $('form[name=user_forma_register] #check_pass').removeClass('has-error has-feedback');
            $('form[name=user_forma_register] .glyphicon').addClass('hide');
        }
    });

    // Регистрация пользователя
    $("form[name=user_forma_register]").on('submit', function () {
        if ($(this).find("input[name=password_new]").val() != $(this).find("input[name=password_new2]").val()) {
            $(this).find('#check_pass').addClass('has-error has-feedback');
            $(this).find('.glyphicon').removeClass('hide');
            return false;
        } else
            $(this).submit();
    });

    // Ошибка регистрации
    if ($("#user_error").html()) {
        $("#user_error").find('.list-group-item').addClass('list-group-item-warning');
    }

    // формат ввода телефона
    /*  $("body").on('click', "form[name=forma_order], input[name=returncall_mod_tel],input[name=tel],input[name=oneclick_mod_tel]", function() {
     if (PHONE_FORMAT && PHONE_MASK && $('.bar-padding-fix').is(":hidden")) {
     $('input[name=tel_new],input[name=returncall_mod_tel],input[name=tel],input[name=oneclick_mod_tel]').mask(PHONE_MASK);
     }
     });*/

    // Фотогалерея в по карточке товара
    if ($('.bxslider').length) {
        $('.bxslider-pre').addClass('hide');
        $('.bxslider').removeClass('hide');
        slider = $('.bxslider').bxSlider({
            mode: 'fade',
            pagerCustom: '.bx-pager'
        });
    }

    // Фотогалерея в по карточке товара с большими изображениями
    $(document).on('click', '.bxslider a', function (event) {
        event.preventDefault();

        $('#sliderModal').modal('show');
        $('.bxsliderbig').html($('.bxsliderbig').attr('data-content'));

        sliderbig = $('.bxsliderbig').bxSlider({
            mode: 'fade',
            pagerCustom: '.bx-pager-big'
        });
        productPageModalImgFix()

        if ($('.bx-pager-big').length == 0) {
            $('.modal-body').append('<div class="bx-pager-big">' + $('.bxsliderbig').attr('data-page') + '</div>');
            sliderbig.reloadSlider();
        }

        sliderbig.goToSlide(slider.getCurrentSlide());

    });
    productPageSliderImgFix()

    // Закрытие модального окна фотогарелерии, клик по изображению
    $(document).on('click', '.bxsliderbig a', function (event) {
        event.preventDefault();
        slider.goToSlide(sliderbig.getCurrentSlide());
        $('#sliderModal').modal('hide');
    });

    // Закрытие модального окна фотогарелерии
    $('#sliderModal').on('hide.bs.modal', function () {
        slider.goToSlide(sliderbig.getCurrentSlide());
        sliderbig.destroySlider();
        delete sliderbig;
    });
    productPageModalImgFix()


    // Сворачиваемый блок 
    $('.collapse').on('show.bs.collapse', function () {
        $(this).prev('h4').find('i').removeClass('fa-chevron-down');
        $(this).prev('h4').find('i').addClass('fa-chevron-up');
        $(this).prev('h4').attr('title', 'Скрыть');
    });
    $('.collapse').on('hidden.bs.collapse', function () {
        $(this).prev('h4').find('i').removeClass('fa-chevron-up');
        $(this).prev('h4').find('i').addClass('fa-chevron-down');
        $(this).prev('h4').attr('title', 'Показать');
    });

    // добавление в корзину подробное описание
    $("body").on('click', ".addToCartFull", function () {

        // Подтип
        if ($('#parentSizeMessage').html()) {

            // Размер
            if ($('input[name="parentColor"]').val() === undefined && $('input[name="parentSize"]:checked').val() !== undefined) {
                addToCartList($('input[name="parentSize"]:checked').val(), $('input[name="quant[2]"]').val(), $('input[name="parentSize"]:checked').attr('data-parent'));
            }
            // Размер  и цвет
            else if ($('input[name="parentSize"]:checked').val() > 0 && $('input[name="parentColor"]:checked').val() > 0) {

                var color = $('input[name="parentColor"]:checked').attr('data-color');
                var size = $('input[name="parentSize"]:checked').attr('data-name');
                var parent = $('input[name="parentColor"]:checked').attr('data-parent');

                $.ajax({
                    url: ROOT_PATH + '/phpshop/ajax/option.php',
                    type: 'post',
                    data: 'color=' + escape(color) + '&parent=' + parent + '&size=' + escape(size),
                    dataType: 'json',
                    success: function (json) {
                        if (json['id'] > 0) {
                            if ($('input[name="parentSize"]:checked').val() > 0 && $('input[name="parentColor"]:checked').val() > 0)
                                addToCartList(json['id'], $('input[name="quant[2]"]').val(), $('input[name="parentColor"]:checked').attr('data-parent'));
                            else
                                showAlertMessage($('#parentSizeMessage').html());
                        }
                    }
                });
            } else
                showAlertMessage($('#parentSizeMessage').html());
        }
        // Опции характеристики
        else if ($('#optionMessage').html()) {
            var optionCheck = true;
            var optionValue = $('#allOptionsSet' + $(this).attr('data-uid')).val();
            $('.optionsDisp select').each(function () {
                if ($(this).hasClass('req') && optionValue === '')
                    optionCheck = false;
            });

            if (optionCheck)
                addToCartList($(this).attr('data-uid'), $('input[name="quant[2]"]').val(), $(this).attr('data-uid'), optionValue);
            else
                showAlertMessage($('#optionMessage').html());
        }
        // Обычный товар
        else {
            addToCartList($(this).attr('data-uid'), $('input[name="quant[2]"]').val());
        }

    });

    // выбор цвета 
    $('body').on('change', 'input[name="parentColor"]', function () {

        $('input[name="parentColor"]').each(function () {
            this.checked = false;
            $(this).parent('label').removeClass('label_active');
        });

        this.checked = true;
        $(this).parent('label').addClass('label_active');


        var color = $('input[name="parentColor"]:checked').attr('data-color');
        var size = $('input[name="parentSize"]:checked').attr('data-name');
        var parent = $('input[name="parentColor"]:checked').attr('data-parent');

        $.ajax({
            url: ROOT_PATH + '/phpshop/ajax/option.php',
            type: 'post',
            data: 'color=' + escape(color) + '&parent=' + parent + '&size=' + escape(size),
            dataType: 'json',
            success: function (json) {
                if (json['id'] > 0) {

                    // Смена цены
                    $('[itemprop="price"]').html(json['price']);

                    // Смена старой цены
                    if (json['price_n'] != "")
                        $('[itemscope] .price-old').html(json['price_n'] + '<span class="rubznak">' + $('[itemprop="priceCurrency"]').html() + '</span>');
                    else
                        $('[itemscope] .price-old').html('');

                    // Смена картинки
                    var parent_img = json['image'];
                    if (parent_img != "") {

                        $(".bx-pager img").each(function (index, el) {
                            if ($(this).attr('src') == parent_img) {
                                slider.goToSlide(index);
                            }

                        });
                    }

                    // Смена склада
                    $('#items').html(json['items']);
                }
            }
        });

    });

    // выбор размера
    $('body').on('change', 'input[name="parentSize"]', function () {
        var id = this.value;

        $('input[name="parentSize"]').each(function () {
            this.checked = false;
            $(this).parent('label').removeClass('label_active');
        });

        this.checked = true;
        $(this).parent('label').addClass('label_active');

        // Если нет цветов меняем сразу цену и картинку
        if ($('input[name="parentColor"]').val() === undefined) {

            // Смена цены
            $('[itemprop="price"]').html($(this).attr('data-price'));

            // Смена старой цены
            if ($(this).attr('data-priceold') != "")
                $('[itemscope] .price-old').html($(this).attr('data-priceold') + '<span class=rubznak>' + $('[itemprop="priceCurrency"]').html() + '</span>');
            else
                $('[itemscope] .price-old').html('');

            // Смена картинки
            var parent_img = $(this).attr('data-image');
            if (parent_img != "") {

                $(".bx-pager img").each(function (index, el) {
                    if ($(this).attr('src') == parent_img) {
                        slider.goToSlide(index);
                    }

                });
            }

            // Смена склада
            $('#items').html($(this).attr('data-items'));
        }

        $('.selectCartParentColor').each(function () {
            $(this).parent('label').removeClass('label_active');
            if ($(this).hasClass('select-color-' + id)) {
                $(this).parent('label').removeClass('not-active');
                $(this).parent('label').attr('title', $(this).attr('data-color'));

                $(this).val(id);
            } else {
                $(this).parent('label').addClass('not-active');
                $(this).parent('label').attr('title', 'Нет');
            }
        });
    });

    //  Social Button
    $('.social-button').on('click', function (e) {
        e.preventDefault();

        var u = location.href;
        var t = encodeURIComponent(document.title);
        var h = document.location.host;
        var d = encodeURIComponent($('meta[name="description"]').attr('content'));

        if ($(this).find("i").hasClass('fa-facebook'))
            path = '//www.facebook.com/sharer/sharer.php?u=' + u;
        else if ($(this).find("i").hasClass('fa-vk'))
            path = '//vk.com/share.php?url=' + u + '&title=' + t + '&description=' + d + '&image=//' + h + $('#logo img').attr('src');
        else if ($(this).find("i").hasClass('fa-odnoklassniki'))
            path = '//ok.ru/dk?st.cmd=addShare&st._surl=' + u + '&title=' + t;

        if (path)
            window.open(path, '_blank', 'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=550, height=440, toolbar=0, status=0');
    });

    // plugin bootstrap minus and plus http://jsfiddle.net/laelitenetwork/puJ6G/
    $('.btn-number').click(function (e) {
        e.preventDefault();

        fieldName = $(this).attr('data-field');
        type = $(this).attr('data-type');
        var input = $("input[name='" + fieldName + "']");
        var currentVal = parseInt(input.val());
        if (!isNaN(currentVal)) {
            if (type == 'minus') {

                if (currentVal > input.attr('min')) {
                    input.val(currentVal - 1).change();
                }
                if (parseInt(input.val()) == input.attr('min')) {
                    $(this).attr('disabled', true);
                }

            } else if (type == 'plus') {

                if (currentVal < input.attr('max')) {
                    input.val(currentVal + 1).change();
                }
                if (parseInt(input.val()) == input.attr('max')) {
                    $(this).attr('disabled', true);
                }

            }
        } else {
            input.val(0);
        }
    });

    $('#faset-filter-body > .faset-filter-block-wrapper:first-child').addClass('active');
    fasetFilterFix();
    $('.order-page-num-input-fix').removeClass('hide');

    $(window).resize(function () {
        setEqualHeight('.product-description');
        setEqualHeight('.product-name-fix');
        setEqualHeight('.product-block-wrapper .description-content');
        setEqualHeight('.product-col');
        setEqualHeight(".caption .description-content");
        setEqualHeight('.outStock');
    });



    // Подсказки DaData.ru
    var DADATA_TOKEN = $('#body').attr('data-token');
    if (DADATA_TOKEN) {

        /*
         $('[name="name_new"]').suggestions({
         token: DADATA_TOKEN,
         partner: "PHPSHOP",
         type: "NAME",
         params: {
         parts: ["NAME"]
         },
         count: 5
         });
         $('[name="name"]').suggestions({
         token: DADATA_TOKEN,
         partner: "PHPSHOP",
         type: "NAME",
         params: {
         parts: ["NAME"]
         },
         count: 5
         });
         $('[name="name_person"]').suggestions({
         token: DADATA_TOKEN,
         partner: "PHPSHOP",
         type: "NAME",
         params: {
         parts: ["NAME"]
         },
         count: 5
         });
         */
        $('[name="oneclick_mod_name"]').suggestions({
            token: DADATA_TOKEN,
            partner: "PHPSHOP",
            type: "NAME",
            params: {
                parts: ["NAME"]
            },
            count: 5
        });
        $('[name="returncall_mod_name"]').suggestions({
            token: "",
            partner: "PHPSHOP",
            type: "NAME",
            params: {
                parts: ["NAME"]
            },
            count: 5
        });
        /*
         $('[type="email"]').suggestions({
         token: DADATA_TOKEN,
         partner: "PHPSHOP",
         type: "EMAIL",
         suggest_local: false,
         count: 5
         });
         */
        $('[name="org_name"]').suggestions({
            token: DADATA_TOKEN,
            partner: "PHPSHOP",
            type: "PARTY",
            count: 5
        });
        $('[name="company"]').suggestions({
            token: DADATA_TOKEN,
            partner: "PHPSHOP",
            type: "PARTY",
            count: 5
        });
    }

    //  Согласие на использование cookie
    $('.cookie-message a').on('click', function (e) {
        e.preventDefault();
        $.cookie('usecookie', 1, {
            path: '/',
            expires: 365
        });
        $(this).parent().slideToggle("slow");
    });
    var usecookie = $.cookie('usecookie');
    if (usecookie == undefined && COOKIE_AGREEMENT) {
        $('.cookie-message p').html(locale.cookie_message);
        $('.cookie-message').removeClass('hide');
    }

    $('div:contains("- PHPShop")').parents('.visible-lg').css('position', 'absolute');
    $('div:contains("- PHPShop")').parents('.visible-lg').css('bottom', '0');
    
    // reCAPTCHA
    $('[data-toggle="modal"]').on('click', function () {
        $.getScript("https://www.google.com/recaptcha/api.js?render=explicit")
                .done(function () {
                    if (typeof grecaptcha !== "undefined") {

                        grecaptcha.ready(function () {

                            if ($("#recaptcha_returncall").length)
                                grecaptcha.render("recaptcha_returncall", {"sitekey": $("#recaptcha_returncall").attr('data-key'), "size": $("#recaptcha_returncall").attr('data-size')});

                            if ($("#recaptcha_oneclick").length)
                                grecaptcha.render("recaptcha_oneclick", {"sitekey": $("#recaptcha_oneclick").attr('data-key'), "size": $("#recaptcha_oneclick").attr('data-size')});

                        });
                    }
                });
    });

    if ($("#recaptcha_default").length) {
        $.getScript("https://www.google.com/recaptcha/api.js?render=explicit")
                .done(function () {
                    if (typeof grecaptcha !== "undefined") {
                        grecaptcha.ready(function () {

                            if ($("#recaptcha_default").length)
                                grecaptcha.render("recaptcha_default", {"sitekey": $("#recaptcha_default").attr('data-key'), "size": $("#recaptcha_default").attr('data-size')});

                        });
                    }
                });
    }
});

