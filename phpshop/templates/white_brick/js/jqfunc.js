// Инициализируем таблицу перевода
var trans = [];
for (var i = 0x410; i <= 0x44F; i++)
    trans[i] = i - 0x350; // А-Яа-я
trans[0x401] = 0xA8;    // Ё
trans[0x451] = 0xB8;    // ё


// Переопределяем функцию escape()
function escaperus(str) {
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
    return escape(String.fromCharCode.apply(null, ret));
}

// Перевод раскладки в русскую
function auto_layout_keyboard(str) {
    replacer = {
        "q": "й", "w": "ц", "e": "у", "r": "к", "t": "е", "y": "н", "u": "г",
        "i": "ш", "o": "щ", "p": "з", "[": "х", "]": "ъ", "a": "ф", "s": "ы",
        "d": "в", "f": "а", "g": "п", "h": "р", "j": "о", "k": "л", "l": "д",
        ";": "ж", "'": "э", "z": "я", "x": "ч", "c": "с", "v": "м", "b": "и",
        "n": "т", "m": "ь", ",": "б", ".": "ю", "/": "."
    };

    return str.replace(/[A-z/,.;\'\]\[]/g, function(x) {
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
        success: function(data)
        {
            if (data) {
                $("#product-scroll").html(data);
                $('#price-filter-val-max').removeClass('has-error');
                $('#price-filter-val-min').removeClass('has-error');

                // Сброс Waypoint
                Waypoint.refreshAll();
            }
        },
        error: function(data) {
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

    var hash = window.location.hash.split('min=' + $.cookie('slider-range-min') + '&').join('');
    hash = hash.split('max=' + $.cookie('slider-range-max') + '&').join('');
    hash += 'min=' + min + '&max=' + max + '&';
    window.location.hash = hash;

    filter_load(hash, obj);

    $.cookie('slider-range-min', min);
    $.cookie('slider-range-max', max);

    $(".pagination").hide();

}

// Ajax фильтр
function faset_filter_click(obj) {

    if (AJAX_SCROLL) {

        $(".pagination").hide();

        if ($(obj).prop('checked')) {
            window.location.hash += $(obj).attr('data-url') + '&';

        }
        else {
            window.location.hash = window.location.hash.split($(obj).attr('data-url') + '&').join('');
            if (window.location.hash == '')
                $('html, body').animate({scrollTop: $("a[name=sort]").offset().top - 100}, 500);

        }

        filter_load(window.location.hash.split(']').join('][]'), obj);
    }
    else {

        var href = window.location.href.split('?')[1];

        if (href == undefined)
            href = '';


        if ($(obj).prop('checked')) {

            var last = href.substring((href.length - 1), href.length);
            if (last != '&' && last != '')
                href += '&';

            href += $(obj).attr('data-url').split(']').join('][]');

        }
        else {
            href = href.split($(obj).attr('data-url').split(']').join('][]') + '&').join('');
        }

        window.location.href = '?' + href;
    }
}

$(document).ready(function() {

    // Анимация по время подгрузки товаров
    $('#ajaxInProgress')
            .ajaxStart(function() {
        $(this).addClass('progress');
    })
            .ajaxStop(function() {
        $(this).removeClass('progress');
    });

    // Направление сортировки в брендах
    $('#filter-selection-well button').on('click', function() {
        window.location.href = $(this).attr('data-url');
    });

    // Направление сортировки
    $('#filter-well button').on('click', function() {
        if (AJAX_SCROLL) {

            count = current;

            window.location.hash = window.location.hash.split($(this).attr('name') + '=1&').join('');
            window.location.hash = window.location.hash.split($(this).attr('name') + '=2&').join('');
            window.location.hash += $(this).attr('name') + '=' + $(this).attr('value') + '&';

            filter_load(window.location.hash);
            $('#' + $(this).attr('data-name')).removeClass('active');
            $(this).addClass('active');


        }
        else {

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
            success: function(data)
            {
                if (data) {
                    $("#faset-filter-body").html(data);
                    $("#faset-filter-body").html($("#faset-filter-body").find('td').html());
                }
            }
        });
    }

    // Ajax фильтр
    $('#faset-filter-body').on('change', 'input:checkbox', function() {

        // Сброс текущей страницы
        count = current;

        faset_filter_click($(this));
    });

    // Сброс фильтра
    $('#faset-filter-reset').on('click', function(event) {
        if (AJAX_SCROLL) {
            event.preventDefault();
            $("#faset-filter-body").html($("#sorttable table td").html());
            filter_load('');
            $('html, body').animate({scrollTop: $("a[name=sort]").offset().top - 100}, 500);
            window.location.hash = '';
            $.removeCookie('slider-range-min');
            $.removeCookie('slider-range-max');
            $(".pagination").show();

            // Сброс текущей страницы
            count = current;
        }

    });

    // Пагинация товаров
    $('.pagination a').on('click', function(event) {
        if (AJAX_SCROLL) {
            event.preventDefault();
            window.location.href = $(this).attr('href') + window.location.hash;
        }
    });

    // Ценовой слайдер
    $("#slider-range").on("slidestop", function(event, ui) {

        if (AJAX_SCROLL) {

            // Сброс текущей страницы
            count = current;

            price_slider_load(ui.values[ 0 ], ui.values[ 1 ]);
        }
        else {
            $('#price-filter-form').submit();
        }
    });

    // Фасетный фильтр
    if (FILTER && $("#sorttable table td").html()) {
        $("#faset-filter-body").html($("#sorttable table td").html());
        $("#faset-filter").removeClass('hide');
    }
    else {
        $("#faset-filter").hide();
        $("#sorttable").removeClass('hide');
    }

    // Подсказки 
    $('[rel="tooltip"]').tooltip({container: 'body'});

    // Повторная авторизация
    if ($('#usersError').html()) {
        $('form[name=user_forma] input[name=login]').addClass('reqActiv');
        $('form[name=user_forma] input[name=password]').addClass('reqActiv');
        $('#light').toggle();
        $('#fade').toggle();

        setTimeout(function() {

            $('#light').animate({paddingLeft: '+=15px'}, 100);
            $('#light').animate({paddingRight: '+=15px'}, 100);
            $('#light').animate({paddingLeft: '+=10px'}, 100);
            $('#light').animate({paddingRight: '+=10px'}, 100);

        }, 500);



    }


    // смена оформления
    $(".bootstrap-theme").on('click', function() {
        skin = $(this).attr('data-skin');

        $('.bod').fadeOut('slow', function() {
            $('#bootstrap_theme').attr('href', ROOT_PATH +'/phpshop/templates/white_brick' + skin + '.css');
        });

        setTimeout(function() {
            $('.bod').fadeIn();
        }, 1000);

        $.cookie('white_brick_theme', skin, {
            path: '/'
        });
    });



    // сохранение оформления
    $(".saveTheme").on('click', function() {

        $.ajax({
            url: ROOT_PATH + '/phpshop/ajax/skin.php',
            type: 'post',
            data: 'template=white_brick&type=json',
            dataType: 'json',
            success: function(json) {
                if (json['success']) {
                    showAlertMessage(json['status']);
                }
            }
        });
    });

    $('#cart > .heading a').on('mouseover', function() {
        if ($('#cart .content div').html()) {
            $('#cart').addClass('active');
            $('.ajaxsearch').removeClass('active');
        }
        $('#cart').on('mouseleave', function() {
            $(this).removeClass('active');
        });
    });

    // Ajax search
    $(".search").on('input', function() {

        var words = $(this).val();

        if (words.length > 2) {
            $.ajax({
                type: "POST",
                url: ROOT_PATH + "/search/",
                data: {
                    words: escaperus(words + ' ' + auto_layout_keyboard(words)),
                    set: 2,
                    ajax: true
                },
                success: function(data)
                {

                    // Результат поиска
                    if (data != 'false') {
                        $('.ajaxsearch-content').html(data);
                        $('.ajaxsearch').addClass('active');
                    } else
                        $('.ajaxsearch').removeClass('active');
                }
            });
        }
        else
            $('.ajaxsearch').removeClass('active');
    });

    $().UItoTop({easingType: 'easeOutQuart'});

    // добавление в корзину
    $('body').on('click', '.addToCartList', function() {
        AddToCart($(this).attr('data-uid'));
    });

    // добавление в wishlist
    $('body').on('click', '.addToWishList', function() {
        addToWishList($(this).attr('data-uid'));
    });

    // добавление в compare
    $("body").on('click', '.addToCompareList', function() {
        AddToCompare($(this).attr('data-uid'));
    });

    $('.links .showUserMenu, .links .user-forma').on('mouseover', function() {
        $('.links .user-forma').show();
    }).live('mouseleave', function() {
        $('.links .user-forma').hide();
    });


    $("input[type=radio]#parentIdNt").click(function() {
        $("input[type=hidden]#parentId").val($(this).val());
    });

    $(".goTOtabs").click(function() {
        $("div.htabs a").removeClass("selected");
        $("div.htabs a.tab-feedback").addClass("selected");
        $("section#product-information div.tab-content").hide();
        $("section#product-information  div#tab-feedback").fadeIn();
    });
    $('#accordion-1').dcAccordion({
        disableLink: false,
        menuClose: false,
        autoClose: true,
        autoExpand: true,
        saveState: false
    });


    //image zoom
    $('.jqzoom').jqzoom({
        zoomType: 'reverse',
        lens: true,
        preloadImages: false,
        preloadText: 'загрузка...',
        zoomWidth: 372,
        zoomHeight: 335,
        showEffect: 'fadein',
        //hideEffect: 'fadeout',
        //fadeoutSpeed: 100,
        xOffset: 335,
        yOffset: -8,
        title: true,
        alwaysOn: false
    })

    // формат ввода телефона
    $("form[name='forma_order'], input[name=returncall_mod_tel],input[name=tel]").on('click', function() {
        if (PHONE_FORMAT && PHONE_MASK && $('.hidden-phone').is(":visible")) {
            $('input[name=tel_new], input[name=returncall_mod_tel],input[name=tel]').mask(PHONE_MASK);
        }
    });
});

function opendcAccordion(par) {
    $("a.leftCatNt" + par).addClass('active');
}