
var AJAX_SCROLL_HIDE_PAGINATOR = true;

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
        data: 'xid=' + product_id + '&num=' + num + '&xxid=0&type=json&addname=' + addname + '&xxid=' + parent,
        dataType: 'json',
        success: function(json) {
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
        success: function(json) {
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
        success: function(json) {
            if (json['success']) {
                $("#fotoload").fadeOut('slow', function() {
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
trans[0x401] = 0xA8; // Ё
trans[0x451] = 0xB8; // ё

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
window.escape = function(str) {

    if (locale.charset == 'utf-8')
        return str;

    else {
        var str = String(str);
        var ret = [];
        // Составляем массив кодов символов, попутно переводим кириллицу
        for (var i = 0; i < str.length; i++) {
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
        "q": "й",
        "w": "ц",
        "e": "у",
        "r": "к",
        "t": "е",
        "y": "н",
        "u": "г",
        "i": "ш",
        "o": "щ",
        "p": "з",
        "[": "х",
        "]": "ъ",
        "a": "ф",
        "s": "ы",
        "d": "в",
        "f": "а",
        "g": "п",
        "h": "р",
        "j": "о",
        "k": "л",
        "l": "д",
        ";": "ж",
        "'": "э",
        "z": "я",
        "x": "ч",
        "c": "с",
        "v": "м",
        "b": "и",
        "n": "т",
        "m": "ь",
        ",": "б",
        ".": "ю",
        "/": "."
    };

    return str.replace(/[A-z/,.;\'\]\[]/g, function(x) {
        return x == x.toLowerCase() ? replacer[x] : replacer[x.toLowerCase()].toUpperCase();
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
        success: function(data) {
            $(".template-product-list").html(data);
            $('#price-filter-val-max').removeClass('has-error');
            $('#price-filter-val-min').removeClass('has-error');

            // Выравнивание ячеек товара
            setEqualHeight(".product-description");
            setEqualHeight(".product-name-fix");
            setTimeout(function() {
                //setEqualHeight(".thumbnail");
                setEqualHeight(".caption img");
            }, 600);
            setEqualHeight(".caption img");
            // lazyLoad
            setTimeout(function() { $(window).lazyLoadXT(); }, 50);

            // Сброс Waypoint
            Waypoint.refreshAll();
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

function productPageSelect() {
    $(".table-optionsDisp select").each(function() {
        var selectID = $(this).attr("id");
        $(".product-page-option-wrapper").append(
            '<div class="product-page-select ' + selectID + '""></div>'
        );
        $(this)
            .children("option")
            .each(function() {
                var optionValue = $(this).attr("value");
                var optionHtml = $(this).html();
                $("." + selectID + "").append(
                    '<div class="select-option" value="' +
                    optionValue +
                    '">' +
                    optionHtml +
                    "</div>"
                );
            });
    });

    $(".select-option").on("click", function() {
        if ($(this).hasClass("active")) {
            $(this).removeClass("active");
            var optionInputValue = [];
            $(".product-page-select .select-option.active").each(function() {
                optionInputValue.unshift($(this).attr("value"));
            });
            var optionInputNewValue = optionInputValue.join();
            $(".product-page-option-wrapper input").attr(
                "value",
                optionInputNewValue
            );
        } else {
            $(this)
                .siblings()
                .removeClass("active");
            $(this).addClass("active");
            var optionInputValue = [];
            $(".product-page-select .select-option.active").each(function() {
                optionInputValue.unshift($(this).attr("value"));
            });
            var optionInputNewValue = optionInputValue.join("");
            $(".product-page-option-wrapper input").attr(
                "value",
                optionInputNewValue
            );
        }
    });
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
                $('html, body').animate({ scrollTop: $("a[name=sort]").offset().top - 100 }, 500);

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

    $(columns).closest('.row ').each(function() {
        var tallestcolumn = 0;

        $(this).find(columns).each(function() {
            var currentHeight = $(this).height();
            if (currentHeight > tallestcolumn) {
                tallestcolumn = currentHeight;
            }
        });

        if (tallestcolumn > 0) {
            $(this).find(columns).height(tallestcolumn);
        }
    });

}

function mainNavMenuFix() {
    var body_width = $('body').width();

    if (body_width < 768) {
        $('.mobile-menu .sub-marker').removeClass('sub-marker')


    }
    if (body_width > 992) {
        var nav_weight = $('.main-navbar-top').width();
        var full_weight = 0;
        $('.main-navbar-top > li').each(function() {
            full_weight += $(this).width();
        });
        var menu_content = ('<div class="additional-nav-menu"><a href="#" class="dropdown-toggle link" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-bars"></i></a><ul class="dropdown-menu dropdown-menu-right aditional-link animated fadeIn" role="menu"></ul></div>');
        if ($('.header-menu-wrapper').find('.additional-nav-menu')) {
            var nav_weight_fix = nav_weight - 46;
        }
        if (nav_weight < full_weight) {
            var nav_weight_fix = nav_weight - 46;
            if ($('.header-menu-wrapper').find('.additional-nav-menu')) {
                $('.header-menu-wrapper .add-menu').append(menu_content);
            }

            while (nav_weight_fix < full_weight) {
                $('.main-navbar-top > li:last-child').prependTo('.aditional-link');
                var full_weight = 0;
                $('.main-navbar-top > li').each(function() {
                    full_weight += $(this).width();
                });
            }

        }
        /* $('.main-navbar-top').addClass('active');*/
    }
}

function productPageSliderImgFix() {
    var block_height = $('.bx-wrapper .bx-viewport').height();
    var block_height_fix = block_height + 'px';
    $('.bx-wrapper .bx-viewport .bxslider > div > a').css('line-height', block_height_fix);

}


var body_height = $(window).height() - 200;
var body_height2 = $(window).height() - 330;
$(document).ajaxStop(function() {

    setTimeout(function() {
        //setEqualHeight(".thumbnail");
        //setEqualHeight(".caption img");
    }, 900);
    setEqualHeight(".caption h5");
    setEqualHeight(".thumbnail .description");
    setEqualHeight(".prod-photo");
    setEqualHeight(".product-price");
    setEqualHeight(".stock");
});
$(document).ready(function() {


	$('.rating-group .btn').bind(' mouseover click', function () {
		$(this).prevAll('.btn').addClass('hover')
		$(this).addClass('hover')
		$(this).nextAll('.btn').removeClass('hover')
		
	})
	$('.rating-group .btn').on('click', function() {
		$(this).addClass('active')
	})
	/*$('.rating-group').bind(' mouseover click', function () {
		
		if($('.rating-group .btn.active').length < 1 ) {$('.rating-group .btn').removeClass('hover')}
	})*/
	
$(".product-name a").text(function(i, text) {

  if (text.length >= 60) {
    text = text.substring(0, 60);
    var lastIndex = text.lastIndexOf(" ");       // позиция последнего пробела
    text = text.substring(0, lastIndex) + '...'; // обрезаем до последнего слова
  }
  
  $(this).text(text);
  
});

 $(window).scroll(function() {
  var currentScroll = $(this).scrollTop();
  if (currentScroll  >100) {
   $('.menu-container').addClass('fix-nav')
     $('.drop').addClass('fix')
  } else {
   $('.menu-container').removeClass('fix-nav')
    $('.drop').removeClass('fix')
  }

});
	var info = $('.more').text().length;
	if (info < 550) {$('.read-more').remove()
		$('.more').addClass('no-fon')
	}
	else{
    $('.read-more').on('click', function() {

        $('.more').toggleClass('open')
        $(this).toggleClass('open')

    })
	}
    $('.catalog-list .row .catalog-wrap').unwrap()
  
	
	
	   $(".mobile-menu >li:nth-child(n+2)").bind(' mouseover click', function () {
		   
   $('.mobile-menu >li:first-child').removeClass('first')
  
	   })
	  $('.drop-shadow .container').mouseleave(function(){
 $('.mobile-menu >li:first-child').addClass('first')
});
	   
	   
 $('.drop-fon, .drop-shadow').on('click', function() {
        $('header').toggleClass('active')
        $('.drop').toggleClass('drop-open')
        $('.drop').toggleClass('drop-menu')
        $('.drop-fon').toggleClass('active')});
    $(' .link-mobile .category-btn').on('click', function() {
		$('.mobile-fix-menu').addClass('active')
		$(' .m-menu > li >a').addClass('sub-marker')
	})
    $(' .m-menu > li >a').on('click', function() {
		$(this).parent('li').addClass('active')
		$(this).addClass('no-visible')
		$( '.m-menu ').addClass('open')
	})
    $(' .link-desktop .category-btn').on('click', function() {
		$('.mobile-menu >li:first-child').addClass('first')
        $('.category-icon').toggleClass('active')

        $('header').toggleClass('active')
        $('.drop').toggleClass('drop-open')
        $('.drop').toggleClass('drop-menu')
        $('.drop-fon').toggleClass('active')

       // height = $('.mobile-menu').height() + 117
        height_menu = $('.mobile-menu').height() - 170 
      //  $('.mobile-menu').css('height', height)
        $('.height-menu').css('height', height_menu)
        $('.height-menu ul.height-menu').addClass('height-menu2')
        $('.height-menu ul.height-menu').removeClass('height-menu')
    })
	
	
	 
    $("body").on("click", ".fastView", function (e) {
        e.preventDefault();
        var url = $(this).attr("data-role");

        if (url.length > 2) {
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    ajax: true
                },
                success: function (data) {
                    $(".fastViewContent").html(data);
                    //$('body').addClass('fix');

                    productPageSelect();
                    $(".btn-number").click(function (e) {
                        e.preventDefault();

                        fieldName = $(this).attr("data-field");
                        type = $(this).attr("data-type");
                        var input = $("input[name='" + fieldName + "']");
                        var currentVal = parseInt(input.val());
                        if (!isNaN(currentVal)) {
                            if (type == "minus") {
                                if (currentVal > input.attr("min")) {
                                    input.val(currentVal - 1).change();
                                }
                                if (parseInt(input.val()) == input.attr("min")) {
                                    $(this).attr("disabled", true);
                                }
                            } else if (type == "plus") {
                                if (currentVal < input.attr("max")) {
                                    input.val(currentVal + 1).change();
                                }
                                if (parseInt(input.val()) == input.attr("max")) {
                                    $(this).attr("disabled", true);
                                }
                            }
                        } else {
                            input.val(0);
                        }
                    });
                    $(".input-number").change(function () {
                        var num = parseInt($(this).val());

                        $(this)
                                .closest(".addToCart")
                                .children(".addToCartFull")
                                .attr("data-num", num);
                    });

                    //image zoom
                    //JQueryZoom();
                    $(window).lazyLoadXT();
                }
            });
            /* $('.product-img-modal > img').load(function() {
             if ($('.bxslider').length) {
             $('.bxslider-pre').addClass('hide');
             $('.bxslider').removeClass('hide');
             slider = $('.bxslider').bxSlider({
             mode: 'fade',
             pagerCustom: '.bx-pager'
             });
             }
             });*/
        }
    });
    setTimeout(function() {
		
		
       
        $('.main-slider').css('opacity', '1')
        $('.swiper-slider-wrapper').css('opacity', '1')
    }, 400)
	setTimeout(function() {
	    if($('.geolocation-need-select').length > 0) {
            $('.reg-tooltip').css('opacity', '1')
        }
	    }, 900)
	   $(' .local-button').on('click', function() {
		      $('.reg-tooltip').css('opacity', '0')
			setTimeout(function() {  $('.header-block').css('z-index', '99') }, 400)
	   })
	   $(' .reg-tooltip-wrap  >a').on('click', function() {
		   
		      $('.reg-tooltip').css('opacity', '1')
			    $('.header-block').css('z-index', '1000')
	   })
	
    if ($('.bigThumb').length < 1) { $('.controlHolder').hide() }
    productPageSelect();
    $('.filter-menu').on('click', function() {

        $(".filter-menu").toggleClass('active');

    });
    $('.filter-menu label:nth-child(n+2)').on('click', function() {

        //  parent.location.hash = ''

    });
    $('.head-catalog').appendTo('.head-block')
    $('.owl-carousel').owlCarousel({
        loop: true,
        margin: 17,
        nav: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1000: {
                items: 5
            }
        }
    })
    $('.navbar-toggle').on('click', function() {
        $('body').toggleClass('overflow')
    })


    $('.sidebar-nav > li').removeClass('dropdown');
    $('.sidebar-nav > li > ul').removeClass('dropdown-menu');

    $('.sidebar-nav  li  a').on('click', function() {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $(this).siblings('ul').removeClass('active');
        } else {

            $(this).addClass('active');
            $(this).siblings('ul').addClass('active');
            $(this).siblings('ul').removeClass('fadeIn animated');
        }
    });
    var pathname = self.location.pathname;
    //Р°РєС‚РёРІР°С†РёСЏ РјРµРЅСЋ
    $(".sidebar-nav li").each(function(index) {

        if ($(this).attr("data-cid") == pathname) {
            $(this).children("ul").addClass("active");
            var cid = $(this).attr("data-cid-parent");
            $("#cid" + cid).addClass("active");
            $("#cid" + cid).attr("aria-expanded", "false");
            $("#cid-ul" + cid).addClass("active");
            $(this).addClass("active");
            $(this).parents("ul").addClass("active");
            $(this).parents("ul").siblings('a').addClass("active");
            $(this).find("a").addClass("active");
        }
    });

    //Активация левого меню каталога на странице продукта
    $('.breadcrumb > li > a').each(function() {
        var linkHref = $(this).attr('href');
        $('.sidebar-nav li').each(function() {
            if ($(this).attr('data-cid') == linkHref) {
                $(this).addClass("active");
                $(this).parent("ul").addClass("active");
                $(this).parent("ul").siblings('a').addClass("active");
                $(this).find("a").addClass("active");
            }
        });
        $('.sidebar-nav ul').each(function() {
            if ($(this).hasClass('active')) {
                $(this).parent('li').removeClass('active');
            }
        });
    });
    //setEqualHeight(".caption img");	 
    mainNavMenuFix();
    $(".filter-btn").on('click', function() {

        $("#faset-filter").fadeIn();


    });
    $(".filter-close").click(function() {
        $("#faset-filter").fadeOut();

    });



    $(".faset-filter-name .close").on('click', function() {
        $("#faset-filter").fadeOut();


    });
    if ($(".carousel-inner .item+.item").length) {

        $(".carousel-control, .carousel-indicators").css("visibility", "visible")
    }

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
     
     
     }, 1000);
     */


    var pathname = self.location.pathname;

    $(".left-block-list  li").each(function(index) {

        if ($(this).attr("data-cid") == pathname) {
            $(this).children("ul").addClass("active");
            $(this).find("i").toggleClass("fa-chevron-down")
            $(this).find("i").toggleClass("fa-chevron-up")
            var cid = $(this).attr("data-cid-parent");

            $("#cid" + cid).addClass("active");
            $("#cid" + cid).attr("aria-expanded", "false");
            $("#cid-ul" + cid).addClass("active");
            $(this).addClass("active");
            $(this).parent("ul").addClass("active");
            $(this).parent("ul").siblings('a').addClass("active");
            $(this).find("a").addClass("active");

        }
    });
    $('.left-block-list > li').removeClass('dropdown');

    $('.left-block-list > li > ul').removeClass('dropdown-menu');
    $('.left-block-list > li > a').on('click', function() {
        $(this).find("i").toggleClass("fa-chevron-down")
        $(this).find("i").toggleClass("fa-chevron-up")
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $(this).siblings('ul').removeClass('active');
        } else {
            $(this).addClass('active');
            $(this).siblings('ul').addClass('active');
            //  $(this).siblings('ul').addClass('fadeIn animated');
        }
    });

    //Активация левого меню каталога на странице продукта
    $('.breadcrumb > li > a').each(function() {
        var linkHref = $(this).attr('href');
        $('.left-block li').each(function() {
            if ($(this).attr('data-cid') == linkHref) {
                $(this).addClass("active");
                $(this).parent("ul").addClass("active");
                $(this).parent("ul").siblings('a').addClass("active");
                $(this).find("a").addClass("active");
            }
        });
        $('.left-block ul').each(function() {
            if ($(this).hasClass('active')) {
                $(this).parent('li').removeClass('active');
            }
        });
    });
    /*$(document).mouseup(function (e) {
     var container = $('.popover');
     if (container.has(e.target).length === 0){
     container.hide();
     }
     });*/
    $("#cartlink").popover({
        delay: { show: 0, hide: 800 }
    });


    setTimeout(function() {
        $('.header-menu-wrapper li').removeClass('active');
       $('.header-menu-wrapper li:not(.catalog-link):not(.action-link)').css("opacity", "1")
        //$('.header-menu-wrapper').css("height", "auto")
    }, 400);
    // логика кнопки оформления заказа 
    $("button.orderCheckButton").on("click", function(e) {
        e.preventDefault();
        OrderChekJq();
    });

    // Выравнивание ячеек товара
    setEqualHeight(".thumbnail .description");
    setEqualHeight(".prod-photo");

    setTimeout(function() {
        //   setEqualHeight(".thumbnail");
        setEqualHeight(".stock");
        //setEqualHeight(".caption img");

    }, 900);




    setEqualHeight(".caption h5");
    // Корректировка стилей меню
    $('.mega-more-parent').each(function() {
        if ($(this).hasClass('hide') || $(this).hasClass('hidden'))
            $(this).prev().removeClass('template-menu-line');
    });
    $(".swiper-container > .swiper-wrapper> .row >.product-block-wrapper-fix").unwrap();
    $(".swiper-container.catalog-slider2 > .swiper-wrapper> .row >.catalog-wrap").unwrap();
    $(".swiper-container.last-slider .swiper-wrapper .product-block-wrapper-fix").addClass("swiper-slide");
    $(".swiper-container:not(.last-slider):not(.catalog-slider) > .swiper-wrapper > div").addClass("swiper-slide");
    $(".brands-slider > .swiper-wrapper > li").addClass("swiper-slide");
    var context = $('.catalog-list');
    /*  while (context.children('.catalog-wrap').length) {
          context.children('div:lt(9)').wrapAll('<div class="swiper-slide">');
      }*/
	  
	  var body_width = $('body').width();
	  if (body_width < 992){
		  $('.main-tabs').appendTo('.mobile-tabs')
		  $('.template-product-list .row .column-5').unwrap()
	  }
	  if (body_width > 1319){
		  
    if ($(".catalog-list .catalog-wrap").length) {
		
        var col_count = 9;
        var $e = $('.catalog-list');
        while ($e.children('.catalog-wrap').not('.row').length) {
            $e.children('.catalog-wrap').not('.row').filter(':lt(' + col_count + ')').wrapAll('<div class="swiper-slide">');
        }
	  }
	  
	    if ($(".catalog-list .swiper-slide:nth-child(2)").children(".catalog-wrap").length > 8) {
        console.log($(this).children(".catalog-wrap").length)
        $('.catalog-list .swiper-slide:nth-child(2)>div:first-child').addClass('big')
    }
    if ($(".catalog-list .swiper-slide:first-child").children(".catalog-wrap").length > 8) {
        console.log($(this).children(".catalog-wrap").length)
        $('.catalog-list .swiper-slide:first-child>div:first-child').addClass('big')
    }
	else{$(".catalog-list .swiper-slide .catalog-wrap:last-child").css('margin','0')
		
		
	}
	  }
	  if ((body_width < 1320)&& (body_width > 991)){
    if ($(".catalog-list .catalog-wrap").length) {
        var col_count = 7;
        var $e = $('.catalog-list');
        while ($e.children('.catalog-wrap').not('.row').length) {
            $e.children('.catalog-wrap').not('.row').filter(':lt(' + col_count + ')').wrapAll('<div class="swiper-slide">');
        }
	  }
	  
	    if ($(".catalog-list .swiper-slide:nth-child(2)").children(".catalog-wrap").length > 6) {
        console.log($(this).children(".catalog-wrap").length)
        $('.catalog-list .swiper-slide:nth-child(2)>div:first-child').addClass('big')
    }
    if ($(".catalog-list .swiper-slide:first-child").children(".catalog-wrap").length > 6) {
        console.log($(this).children(".catalog-wrap").length)
        $('.catalog-list .swiper-slide:first-child>div:first-child').addClass('big')
    }
	  
	  }	 
	  if (body_width > 991) {  window.onload = function() {
        
		if($(".airSticky").length)
		$(".airSticky").airStickyBlock({
            debug: true,
            stopBlock: ".airSticky_stop-block",
			
        });
    };}
  if (body_width < 991){
	  
	    $(".swiper-container.catalog-slider > .swiper-wrapper> .catalog-wrap").addClass("swiper-slide");
  }
  

    if ($(".swiper-container").length) {
        var swiper1 = new Swiper(".compare-slider", {
            slidesPerView: 3,
            speed: 800,
            nextButton: ".btn-next10",
            prevButton: ".btn-prev10",
            preventClicks: false,
            effect: "slide",

            preventClicksPropagation: false,
            breakpoints: {
                450: {
                    slidesPerView: 2
                },
                610: {
                    slidesPerView: 2
                },
                850: {
                    slidesPerView: 3
                },
                1000: {
                    slidesPerView: 4
                },
                1080: {
                    slidesPerView: 3
                },
                1200: {
                    slidesPerView: 3
                },
                1500: {
                    slidesPerView: 3
                }
            }
        });
        var swiper2 = new Swiper(".spec-slider", {
            slidesPerView: 6,
            speed: 800,
            nextButton: ".btn-next2",
            prevButton: ".btn-prev2",
            preventClicks: false,
            effect: "slide",
            preventClicksPropagation: false,
            breakpoints: {
                550: {
                    slidesPerView: 2
                },
                730: {
                    slidesPerView: 2
                },
                950: {
                    slidesPerView: 3
                },
                1180: {
                    slidesPerView: 4
                },
                1300: {
                    slidesPerView: 5
                }
            }
        });
        var swiper3 = new Swiper(".specMain-slider", {
            slidesPerView: 6,
            speed: 800,
            nextButton: ".btn-next3",
            prevButton: ".btn-prev3",
            preventClicks: false,
            effect: "slide",
            preventClicksPropagation: false,
            breakpoints: {
                550: {
                    slidesPerView: 2
                },
                730: {
                    slidesPerView: 2
                },
                950: {
                    slidesPerView: 3
                },
                1180: {
                    slidesPerView: 4
                },
                1300: {
                    slidesPerView: 5
                }
            }
        });
        var swiper4 = new Swiper(".nowBuy-slider", {
            slidesPerView: 6,
            speed: 800,
            nextButton: ".btn-next4",
            prevButton: ".btn-prev4",
            preventClicks: false,
            effect: "slide",
            preventClicksPropagation: false,
            breakpoints: {
                550: {
                    slidesPerView: 2
                },
                730: {
                    slidesPerView: 2
                },
                950: {
                    slidesPerView: 3
                },
                1180: {
                    slidesPerView: 4
                },
                1300: {
                    slidesPerView: 5
                }
            }
        });
        var swiper5 = new Swiper(".brands-slider", {
            slidesPerView: 6,
            speed: 800,
            nextButton: ".btn-next5",
            prevButton: ".btn-prev5",
            preventClicks: false,
            effect: "slide",
            preventClicksPropagation: false,
            breakpoints: {
                550: {
                    slidesPerView: 2
                },
                800: {
                    slidesPerView: 4
                },
                1000: {
                    slidesPerView: 4
                },
                1080: {
                    slidesPerView: 4
                },
                1200: {
                    slidesPerView: 4
                },
                1500: {
                    slidesPerView: 5
                }
            }
        });
        var swiper6 = new Swiper(".list-slider", {
            slidesPerView: 6,
            speed: 800,
            nextButton: ".btn-next6",
            prevButton: ".btn-prev6",
            preventClicks: false,
            effect: "slide",
            preventClicksPropagation: false,
            breakpoints: {
                550: {
                    slidesPerView: 1
                },
                730: {
                    slidesPerView: 1
                },
                950: {
                    slidesPerView: 3
                },
                1180: {
                    slidesPerView: 4
                },
                1300: {
                    slidesPerView: 5
                }
            }
        });
        var swiper7 = new Swiper(".last-slider", {
            slidesPerView: 5,
            speed: 800,
            nextButton: ".btn-next7",
            prevButton: ".btn-prev7",
            preventClicks: false,
            effect: "slide",
            preventClicksPropagation: false,
            breakpoints: {
                550: {
                    slidesPerView: 1
                },
                730: {
                    slidesPerView: 1
                },
                950: {
                    slidesPerView: 3
                },
                1180: {
                    slidesPerView: 4
                },
                1300: {
                    slidesPerView: 5
                }
            }
        });
        var swiper8 = new Swiper(".last-slider2", {
            slidesPerView: 5,
            speed: 800,
            nextButton: ".btn-next8",
            prevButton: ".btn-prev8",
            preventClicks: false,
            effect: "slide",
            preventClicksPropagation: false,
            breakpoints: {
                550: {
                    slidesPerView: 1
                },
                730: {
                    slidesPerView: 1
                },
                950: {
                    slidesPerView: 3
                },
                1180: {
                    slidesPerView: 4
                },
                1300: {
                    slidesPerView: 5
                }
            }
        });
        var swiper9 = new Swiper(".action-slider", {
            slidesPerView: 3,
            speed: 800,
            nextButton: ".btn-next9",
            prevButton: ".btn-prev9",
            preventClicks: false,
            effect: "slide",
            preventClicksPropagation: false,
            breakpoints: {
                550: {
                    slidesPerView: 3
                },
                730: {
                    slidesPerView: 1
                },
                950: {
                    slidesPerView: 3
                },
                1180: {
                    slidesPerView: 4
                },
                1300: {
                    slidesPerView: 5
                }
            }
        });
        var swiper10 = new Swiper(".news-slider", {
            slidesPerView: 5,
            speed: 800,
            nextButton: ".btn-next10",
            prevButton: ".btn-prev10",
            preventClicks: false,
            effect: "slide",
            preventClicksPropagation: false,
            breakpoints: {
                550: {
                    slidesPerView: 1
                },
                730: {
                    slidesPerView: 1
                },
                950: {
                    slidesPerView: 3
                },
                1180: {
                    slidesPerView: 4
                },
                1300: {
                    slidesPerView: 5
                }
            }
        });
        var swiper11 = new Swiper(".pages-slider", {
            slidesPerView: 5,
            speed: 800,
            nextButton: ".btn-next11",
            prevButton: ".btn-prev11",
            preventClicks: false,
            effect: "slide",
            preventClicksPropagation: false,
            breakpoints: {
                550: {
                    slidesPerView: 1
                },
                730: {
                    slidesPerView: 1
                },
                950: {
                    slidesPerView: 3
                },
                1180: {
                    slidesPerView: 4
                },
                1300: {
                    slidesPerView: 5
                }
            }
        });
        var swiper12 = new Swiper(".catalog-slider", {
            slidesPerView: 1,
            speed: 800,
            nextButton: ".btn-next12",
            prevButton: ".btn-prev12",
            preventClicks: false,
            effect: "slide",
            preventClicksPropagation: false,
            breakpoints: {
                550: {
                    slidesPerView:2,
				
					slidesPerColumn:3
                },
                730: {
                    slidesPerView:2,
				
					slidesPerColumn:3
                },
                991: {
                    slidesPerView:3,
				
					slidesPerColumn:2
                },
                1180: {
                    slidesPerView: 1
                },
                1300: {
                    slidesPerView: 1
                }
            }
        });
        var swiper12 = new Swiper(".catalog-slider2", {
            slidesPerView: 6,
            speed: 800,
            nextButton: ".btn-next12",
            prevButton: ".btn-prev12",
            preventClicks: false,
            effect: "slide",
            preventClicksPropagation: false,
            breakpoints: {
                550: {
                    slidesPerView: 1
                },
                730: {
                    slidesPerView: 1
                },
                950: {
                    slidesPerView: 3
                },
                1180: {
                    slidesPerView: 4
                },
                1300: {
                    slidesPerView: 5
                }
            }
        });
    }

    setEqualHeight(".prod-title");
    setEqualHeight(".prod-photo");
    setEqualHeight(".product-name");


    setEqualHeight(".prod-desc");
    setEqualHeight(".prod-sort");
    // Вывод всех категорий в мегаменю
    $('.mega-more').on('click', function(event) {
        event.preventDefault();
        $(this).hide();
        $(this).closest('.mega-menu-block').find('.template-menu-line').removeClass('hide');
    });


    // Направление сортировки в брендах
    $('#filter-selection-well input:radio').on('change', function() {
        window.location.href = $(this).attr('data-url');
    });

    $('#price-filter-body input').on('change', function() {
        if (AJAX_SCROLL) {
            price_slider_load($('#price-filter-body input[name=min]').val(), $('#price-filter-body input[name=max]').val(), $(this));
        } else {
            $('#price-filter-form').submit();
        }

    });


    // Ценовой слайдер
    $("#slider-range").on("slidestop", function(event, ui) {

        if (AJAX_SCROLL) {

            // Сброс текущей страницы
            count = current;

            price_slider_load(ui.values[0], ui.values[1]);
        } else {
            $('#price-filter-form').submit();
        }
    });

    // Фасетный фильтр
    if (FILTER && $("#sorttable table td").html()) {
        $("#faset-filter-body").html($("#sorttable table td").html());
        $("#faset-filter").removeClass('hide');
        $('.filter-btn').addClass('visible-filter')
    } else {
        $("#faset-filter").hide();

    }

    if (!FILTER) {
        $("#faset-filter").hide();
        $("#sorttable").removeClass('hide');
    }


    // Направление сортировки
    $('#filter-well input:radio').on('change', function() {
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
            success: function(data) {
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
            $('html, body').animate({ scrollTop: $("a[name=sort]").offset().top - 100 }, 500);
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


    // toTop
    $('#toTop').on('click', function(event) {
        event.preventDefault();
        $('html, body').animate({ scrollTop: $("header").offset().top - 100 }, 500);
    });

    // закрепление навигации
    $('.col-xs-12.main').waypoint(function() {
        if (FIXED_NAVBAR)
        //  $('#navigation').toggleClass('navbar-fixed-top');

        // toTop          
            $('#toTop').fadeToggle();
    });

    // быстрый переход
    $(document).on('keydown', function(e) {
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
                success: function(json) {
                    if (json['success']) {
                        confirm(json['info']);
                    }
                }
            });
        }
    });


    // выбор каталога поиска
    $(".cat-menu-search").on('click', function() {
        $('#cat').val($(this).attr('data-target'));
        $('#catSearchSelect').html($(this).html());
    });

    hs.registerOverlay({ html: '<div class="closebutton" onclick="return hs.close(this)" title="Закрыть"></div>', position: 'top right', fade: 2 });
    hs.graphicsDir = ROOT_PATH + '/java/highslide/graphics/';
    hs.wrapperClassName = 'borderless';


    // увеличение изображения товара
    $("body").on('click', '.highslide', function() {
        return hs.expand(this);
    });

    // ошибка загрузки изображения
    $('.highslide img').on('error', function() {
        $(this).attr('src', '/phpshop/templates/bootstrap/images/shop/no_photo.gif');
        return true;
    });


    // подгрузка комментариев
    $("body").on('click', '#commentLoad', function() {
        commentList($(this).attr('data-uid'), 'list');
    });

    // убираем пустые закладки подробного описания
    if ($('#files').html() != 'Нет файлов')
        $('#filesTab').addClass('show');

    if ($('#vendorenabled').html() != '')
        $('#settingsTab').addClass('show');

    if ($('#pages').html() != '')
        $('#pagesTab').addClass('show');


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
    $('body').on('click', 'button.addToCartList', function() {
        addToCartList($(this).attr('data-uid'), $(this).attr('data-num'));
        $(this).attr('disabled', 'disabled');
        $(this).addClass('btn-success');
        $(this).html('В корзине')
        $('#order').addClass('active');
    });

    // изменение количества товара для добавления в корзину
    $('body').on('change', '.addToCartListNum', function() {
        var num = (Number($(this).val()) || 1);
        var id = $(this).attr('data-uid');
        /*
         if (num > 0 && $('.addToCartList').attr('data-uid') === $(this).attr('data-uid'))
         $('.addToCartList').attr('data-num', num);*/
        if (num > 0) {
            $(".addToCartList").each(function() {
                if ($(this).attr('data-uid') === id)
                    $('.addToCartList[data-uid=' + id + ']').attr('data-num', num);
            });
        }

    });

    // добавление в корзину подтипа
    $(".addToCartListParent").on('click', function() {
        addToCartList($(this).attr('data-uid'), $(this).attr('data-num'), $(this).attr('data-parent'));
        $('[itemprop="price"]').html($(this).attr('data-price'));
    });

    // добавление в корзину опции
    $(".addToCartListOption").on('click', function() {
        addToCartList($(this).attr('data-uid'), $(this).attr('data-num'), $(this).attr('data-uid'), $('#allOptionsSet' + $(this).attr('data-uid')).val());
    });

    // добавление в wishlist
    $('body').on('click', '.addToWishList', function() {
        addToWishList($(this).attr('data-uid'));
    });

    // добавление в compare
    $('body').on('click', '.addToCompareList', function() {
        addToCompareList($(this).attr('data-uid'));
    });

    // отправка сообщения администратору из личного кабинета
    $("#CheckMessage").on('click', function() {
        if ($("#message").val() != '')
            $("#forma_message").submit();
    });

    // Визуальная корзина
    if ($("#cartlink").attr('data-content') == "") {
        $("#cartlink").attr('href', '/order/');
    }
    $('[data-toggle="popover"]').popover();
    $('a[data-toggle="popover"]').on('show.bs.popover', function() {
        $('a[data-toggle="popover"]').attr('data-content', $("#visualcart_tmp").html());
    });

    // Подсказки 
    $('[data-toggle="tooltip"]').tooltip({ container: 'body' });

    // Стилизация select
    $('.selectpicker').selectpicker({
        width: "100%"
    });

    // Переход из прайса на форму с описанием
    $('#price-form').on('click', function(event) {
        event.preventDefault();
        if ($(this).attr('data-uid') != "" && $(this).attr('data-uid') != "ALL")
            window.location.replace("../shop/CID_" + $(this).attr('data-uid') + ".html");
    });

    // Ajax поиск
    $("#search").on('input', function() {
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
                success: function(data) {

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
        $('#userModal').on('shown.bs.modal', function() {

        });
    }

    // Проверка синхронности пароля регистрации
    $("form[name=user_forma_register] input[name=password_new2]").on('blur', function() {
        if ($(this).val() != $("form[name=user_forma_register] input[name=password_new]").val()) {
            $('form[name=user_forma_register] #check_pass').addClass('has-error has-feedback');
            $('form[name=user_forma_register] .glyphicon').removeClass('hide');
        } else {
            $('form[name=user_forma_register] #check_pass').removeClass('has-error has-feedback');
            $('form[name=user_forma_register] .glyphicon').addClass('hide');
        }
    });

    // Регистрация пользователя
    $("form[name=user_forma_register]").on('submit', function() {
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
    $("form[name='forma_order'], input[name=returncall_mod_tel],input[name=tel],input[name=oneclick_mod_tel]").on("click", function() {
        if (PHONE_FORMAT && PHONE_MASK) {
            $("input[name=tel_new], input[name=returncall_mod_tel],input[name=tel],input[name=oneclick_mod_tel]").mask(PHONE_MASK);
        }
    });

    /*
     setTimeout(function () {
     $('input[name=tel_new]').mask("+7 (999) 999-99-99");
     
     $('input[name=tel_new]').on('keyup', function (event) {
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
     }, 2500);
     $('input[name=returncall_mod_tel],input[name=tel],input[name=oneclick_mod_tel]').mask("+7 (999) 999-99-99");
     
     $('input[name=returncall_mod_tel],input[name=tel],input[name=oneclick_mod_tel]').on('keyup', function (event) {
     reserveVal = $(this).cleanVal();
     phone = $(this).cleanVal().slice(0, 10);
     $(this).val($(this).masked(phone));
     if ($(this).cleanVal()[1] == '9') {
     if ($(this).cleanVal()[0] == '8' || $(this).cleanVal()[0] == '7') {
     phone = reserveVal.slice(1);
     $(this).val($(this).masked(phone));
     }
     }
     })
     */




    // Фотогалерея в по карточке товара с большими изображениями
    $(document).on('click', '.bxslider a', function(event) {
        event.preventDefault();
        $('#sliderModal').modal('show');
        $('.bxsliderbig').html($('.bxsliderbig').attr('data-content'));
        $('.modal .modal-body img').css("max-height", $(window).height() * 0.65);
        setTimeout(function() {



            $('.modal .modal-body .bx-viewport').css("max-height", $(window).height() * 0.65);
            $('.modal .modal-body .bx-viewport').css("opacity", "1")

        }, 600);
        sliderbig = $('.bxsliderbig').bxSlider({
            mode: 'fade',
            pagerCustom: '.bx-pager-big'
        });


        if ($('.bx-pager-big').length == 0) {
            $('.modal-body').append('<div class="bx-pager-big">' + $('.bxsliderbig').attr('data-page') + '</div>');
            sliderbig.reloadSlider();
        }

        sliderbig.goToSlide(slider.getCurrentSlide());

    });

    // Закрытие модального окна фотогарелерии, клик по изображению
    $(document).on('click', '.bxsliderbig a', function(event) {
        event.preventDefault();
        slider.goToSlide(sliderbig.getCurrentSlide());
        $('#sliderModal').modal('hide');
    });

    // Закрытие модального окна фотогарелерии
    $('#sliderModal').on('hide.bs.modal', function() {
        slider.goToSlide(sliderbig.getCurrentSlide());
        sliderbig.destroySlider();
        delete sliderbig;
    });

    // Сворачиваемый блок 
    $('.collapse').on('show.bs.collapse', function() {
        $(this).prev('h4').find('i').removeClass('fa fa-angle-up');
        $(this).prev('h4').find('i').addClass('fa fa-angle-down ');
        $(this).prev('h4').attr('title', locale.hide);
    });
    $('.collapse').on('hidden.bs.collapse', function() {
        $(this).prev('h4').find('i').removeClass('fa fa-angle-down');
        $(this).prev('h4').find('i').addClass('fa fa-angle-up');
        $(this).prev('h4').attr('title', locale.show);
    });


    // добавление в корзину подробное описание
    $("body").on('click', ".addToCartFull", function() {

        // Подтип
        if ($('#parentSizeMessage').html()) {

            // Размер
            if ($('input[name="parentColor"]').val() === undefined && $('input[name="parentSize"]:checked').val() !== undefined) {
                addToCartList($('input[name="parentSize"]:checked').val(), $('input[name="quant[2]"]').val(), $('input[name="parentSize"]:checked').attr('data-parent'));
                $(this).html('В корзине')
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
                    success: function(json) {
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
            $('.optionsDisp select').each(function() {
                if ($(this).hasClass('req') && optionValue === '')
                    optionCheck = false;
            });

            if (optionCheck) {
                addToCartList($(this).attr('data-uid'), $('input[name="quant[2]"]').val(), $(this).attr('data-uid'), optionValue);
                $(this).html('В корзине')
            } else
                showAlertMessage($('#optionMessage').html());
        }
        // Обычный товар
        else {
            addToCartList($(this).attr('data-uid'), $('input[name="quant[1]"]').val());
            $(this).html('В корзине')
        }

    });

    // выбор цвета 
    $('body').on('change', 'input[name="parentColor"]', function() {

        $('input[name="parentColor"]').each(function() {
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
            success: function(json) {
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

                        $(".bx-pager img").each(function(index, el) {
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
    $('body').on('change', 'input[name="parentSize"]', function() {
        var id = this.value;

        $('input[name="parentSize"]').each(function() {
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

                $(".bx-pager img").each(function(index, el) {
                    if ($(this).attr('src') == parent_img) {
                        slider.goToSlide(index);
                    }

                });
            }

            // Смена склада
            $('#items').html($(this).attr('data-items'));
        }

        $('.selectCartParentColor').each(function() {
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

    // FlipClock
    if ($('.clock').length) {
        var now = new Date();
        var night = new Date(
            now.getFullYear(),
            now.getMonth(),
            now.getDate(),
            $('.clock').attr('data-hour'), 0, 0
        );
        var msTillMidnight = night.getTime() / 1000 - now.getTime() / 1000;
        var clock = $('.clock').FlipClock({
            language: 'russian',
            coundown: true

        });
        clock.setTime(msTillMidnight);
        clock.setCountdown(true);
        clock.start();
    }

    // plugin bootstrap minus and plus http://jsfiddle.net/laelitenetwork/puJ6G/
    $('.btn-number').click(function(e) {
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



    // Подсказки DaData.ru
    var DADATA_TOKEN = $('#body').attr('data-token');
    if (DADATA_TOKEN) {

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
            token: DADATA_TOKEN,
            partner: "PHPSHOP",
            type: "NAME",
            params: {
                parts: ["NAME"]
            },
            count: 5
        });
        $('[type="email"]').suggestions({
            token: DADATA_TOKEN,
            partner: "PHPSHOP",
            type: "EMAIL",
            suggest_local: false,
            count: 5
        });
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
    $('.cookie-message a').on('click', function(e) {
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

    //mobile-menu 
    $('.mobile-menu .dropdown-parent').on('click', function() {
        $(this).children('ul').slideToggle()
    })

    $(window).resize(function() {

        mainNavMenuFix()
    })
    if ($('#productSlider').length > 0) {
        if ($('.heroSlide img').attr('src').indexOf('no_photo.png') + 1) {
            var src = $('.heroSlide img').attr('src');
            TouchNSwipe.remove("productSlider");
            $('#productSlider').append('<img  src="' + src + '"/>');
        }
        $('.heroSlide img').each(function(index, element) {
            $(element).removeClass('hide');
        });

        var tns = TouchNSwipe.get('productSlider');
        tns.slider.on(ElemZoomSlider.INDEX_CHANGE, function(event) {
            $(event.currentTarget.getSlideElemAt(event.currentTarget._index)).find('img').removeClass('hide');
        });

    }
    if($('.popular-brands .brand-element').length === 0) {
        $('.popular-brands').css('display', 'none');
    }
});

// reCAPTCHA
if ($("#recaptcha_default").length || $("#recaptcha_returncall").length || $("#recaptcha_oneclick").length) {
    var ga = document.createElement('script');
    ga.type = 'text/javascript';
    ga.async = true;
    ga.defer = true;
    ga.src = '//www.google.com/recaptcha/api.js?onload=recaptchaCreate&render=explicit';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(ga, s);
}
recaptchaCreate = function() {

    if ($("#recaptcha_default").length)
        grecaptcha.render("recaptcha_default", { "sitekey": $("#recaptcha_default").attr('data-key'), "size": $("#recaptcha_default").attr('data-size') });

    if ($("#recaptcha_returncall").length)
        grecaptcha.render("recaptcha_returncall", { "sitekey": $("#recaptcha_returncall").attr('data-key'), "size": $("#recaptcha_returncall").attr('data-size') });

    if ($("#recaptcha_oneclick").length)
        grecaptcha.render("recaptcha_oneclick", { "sitekey": $("#recaptcha_oneclick").attr('data-key'), "size": $("#recaptcha_oneclick").attr('data-size') });
};