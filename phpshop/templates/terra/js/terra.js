function searchOpen() {
    $('.search-open-button').on('click', function() {
        $('.search-big-block').addClass("active");
    });
    $('.search-close').on('click', function() {
        $('.search-big-block').removeClass("active");
        $('.header-search-form').trigger("reset");
    });
}
$(document).ready(function() {
    searchOpen();
        $('.visible-lg').each(function() {
        if ($(this).attr('style') == 'clear: both; width:100%')
            $(this).addClass('copyright');
    });
    
    
    $(window).on('scroll', function() {

        if ($(window).scrollTop() >= $('.header-top').offset().top) {
            //$('#main-menu').addClass('navbar-fixed-top');
            // toTop          
            $('#toTop').fadeIn();
        } else {
            //$('#main-menu').removeClass('navbar-fixed-top');
            $('#toTop').fadeOut();

        }
    });
    

    var pathname = self.location.pathname;
  
    $(".sidebar-nav li").each(function(index) {
        if ($(this).attr("data-cid") == pathname) {
            var cid = $(this).attr("data-cid-parent");
            $("#cid" + cid).addClass("active");
            $("#cid" + cid).attr("aria-expanded", "false");
            $("#cid-ul" + cid).addClass("active");
            $(this).addClass("active");
            $(this).parent("ul").addClass("active");
            $(this).parent("ul").siblings('a').addClass("active");
        }
    });

    //��������� ������ ���� �������� �� �������� ��������

    $('.breadcrumb > li > a').each(function() {
        var linkHref = $(this).attr('href');
        $('.sidebar-nav li').each(function() {
            if ($(this).attr('data-cid') == linkHref) {
                $(this).addClass("active");
                $(this).parent("ul").addClass("active");
                $(this).parent("ul").siblings('a').addClass("active");
            }
        });
        $('.sidebar-nav ul').each(function() {
            if ($(this).hasClass('active')) {
                $(this).parent('li').removeClass('active');
            }
        });
    });


    //��������� ��������� �� ������� ��������
    if (!$('.editor_var').length) {
        $('.spec-main-icon-slider > .swiper-wrapper .col-md-3.col-sm-6').unwrap();
        $('.spec-main-icon-slider > .swiper-wrapper > div').addClass('swiper-slide');
        var swiper = new Swiper('.spec-main-icon-slider', {
            slidesPerView: 3,
            speed: 800,
            nextButton: '.btn-next1',
            prevButton: '.btn-prev1',
            preventClicks: false,
            effect: 'slide',
            preventClicksPropagation: false,
            breakpoints: {
                768: {
                    slidesPerView: 1
                },
                991: {
                    slidesPerView: 2
                },
                1200: {
                    slidesPerView: 3
                }
            }
        });

        $('.spec-main-slider > .swiper-wrapper .col-md-3.col-sm-6').unwrap();
        $('.spec-main-slider > .swiper-wrapper > div').addClass('swiper-slide');
        var swiper = new Swiper('.spec-main-slider', {
            slidesPerView: 3,
            speed: 800,
            nextButton: '.btn-next2',
            prevButton: '.btn-prev2',
            preventClicks: false,
            effect: 'slide',
            preventClicksPropagation: false,
            breakpoints: {
                768: {
                    slidesPerView: 1
                },
                991: {
                    slidesPerView: 2
                },
                1200: {
                    slidesPerView: 3
                }
            }
        });
    }

// -------------------- matchHeight --------------------

    $('.newitems-list .product-col').matchHeight();
    $('.spec-list .product-colr').matchHeight();
    $('.nowbuy-list .product-col').matchHeight();
    $('.recomend_products .product-block').matchHeight();


});

// -------------------- Slick slider (brands) --------------------

$(window).load(function() {
    $('.brand-list').slick({
        slidesToShow: 8,
        slidesToScroll: 1,
        infinite: false,
        focusOnSelect: false,
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 6,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            }
        ]
    });
});

