/** Ð?Ð·Ð¼ÐµÐ½Ð¸Ðµ Ð²Ð¸Ð´Ð° Ñ€ÐµÐ¹Ñ‚Ð¸Ð½Ð³Ð° Ñ‚Ð¾Ð²Ð°Ñ€Ð° Ð½Ð°Ñ‡Ð°Ð»Ð¾ **/
function changeOfProductRatingView() {
    var raitingWidth = $('#raiting_votes').outerWidth();
    var raitingstarZero = ('<i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>')
    var raitingstarOne = ('<i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>');
    var raitingstarTwo = ('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>');
    var raitingstarThree = ('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>');
    var raitingstarFour = ('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i>');
    var raitingstarFive = ('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>');

    if (raitingWidth == 0) {
        $('#raiting_star').remove();
        $('.rating').append(raitingstarZero);
    }
    if (raitingWidth > 1 && raitingWidth <= 16) {
        $('#raiting_star').remove();
        $('.rating').append(raitingstarOne);
    }
    if (raitingWidth > 17 && raitingWidth <= 32) {
        $('#raiting_star').remove();
        $('.rating').append(raitingstarTwo);
    }
    if (raitingWidth > 33 && raitingWidth <= 48) {
        $('#raiting_star').remove();
        $('.rating').append(raitingstarThree);
    }
    if (raitingWidth > 49 && raitingWidth <= 64) {
        $('#raiting_star').remove();
        $('.rating').append(raitingstarFour);
    }
    if (raitingWidth > 65 && raitingWidth <= 80) {
        $('#raiting_star').remove();
        $('.rating').append(raitingstarFive);
    }
}
/** Ð?Ð·Ð¼ÐµÐ½Ð¸Ðµ Ð²Ð¸Ð´Ð° Ñ€ÐµÐ¹Ñ‚Ð¸Ð½Ð³Ð° Ñ‚Ð¾Ð²Ð°Ñ€Ð° ÐºÐ¾Ð½ÐµÑ† **/

/** Ð?Ð·Ð¼ÐµÐ½ÐµÐ½Ð¸Ðµ Ð²Ð¸Ð´Ð° Ñ€ÐµÐ¹Ñ‚Ð¸Ð½Ð³Ð° Ð¾Ñ‚Ð·Ñ‹Ð²Ð° Ð½Ð°Ñ‡Ð°Ð»Ð¾ **/
function changeOfReviewsRatingView() {
    var imgRaitingSrcZero = ('/phpshop/templates/diggi/images/stars/stars1-0.png')
    var imgRaitingSrcOne = ('/phpshop/templates/diggi/images/stars/stars1-1.png')
    var imgRaitingSrcTwo = ('/phpshop/templates/diggi/images/stars/stars1-2.png')
    var imgRaitingSrcThree = ('/phpshop/templates/diggi/images/stars/stars1-3.png')
    var imgRaitingSrcFour = ('/phpshop/templates/diggi/images/stars/stars1-4.png')
    var imgRaitingSrcFive = ('/phpshop/templates/diggi/images/stars/stars1-5.png')
    var raitingstarZero = ('<i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>')
    var raitingstarOne = ('<i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>');
    var raitingstarTwo = ('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>');
    var raitingstarThree = ('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>');
    var raitingstarFour = ('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i>');
    var raitingstarFive = ('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>');
    $('.comments-raiting-wrapper').each(function() {
        var imgRaitingSrc = $(this).children('img').attr('src');
        if ($(this).find('img')) {
            $(this).children('img').remove();
            if (imgRaitingSrc == imgRaitingSrcZero) {
                $(this).append(raitingstarZero);
            }
            if (imgRaitingSrc == imgRaitingSrcOne) {
                $(this).append(raitingstarOne);
            }
            if (imgRaitingSrc == imgRaitingSrcTwo) {
                $(this).append(raitingstarTwo);
            }
            if (imgRaitingSrc == imgRaitingSrcThree) {
                $(this).append(raitingstarThree);
            }
            if (imgRaitingSrc == imgRaitingSrcFour) {
                $(this).append(raitingstarFour);
            }
            if (imgRaitingSrc == imgRaitingSrcFive) {
                $(this).append(raitingstarFive);
            }
        }
    });
}
/** Ð?Ð·Ð¼ÐµÐ½ÐµÐ½Ð¸Ðµ Ð²Ð¸Ð´Ð° Ñ€ÐµÐ¹Ñ‚Ð¸Ð½Ð³Ð° Ð¾Ñ‚Ð·Ñ‹Ð²Ð° ÐºÐ¾Ð½ÐµÑ† **/
$(document).ready(function() {
    $(window).on('scroll', function() {

        if ($(window).scrollTop() >= $('.header-top').offset().top) {
            $('#main-menu').addClass('navbar-fixed-top');
            // toTop          
            $('#toTop').fadeIn();
        } else {
            $('#main-menu').removeClass('navbar-fixed-top');
            $('#toTop').fadeOut();
       
        }
    });
    changeOfProductRatingView();
    setInterval(changeOfReviewsRatingView, 100)
    $(document).on('click', function() {
        changeOfReviewsRatingView();
    })
    $('.sidebar-nav > li').removeClass('dropdown');
    $('.sidebar-nav > li > ul').removeClass('dropdown-menu');
    $('.sidebar-nav > li > a').on('click', function() {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $(this).siblings('ul').removeClass('active');
        } else {
            $(this).addClass('active');
            $(this).siblings('ul').addClass('active');
            $(this).siblings('ul').addClass('fadeIn animated');
        }
    });
    $('.main-navbar-list-catalog-wrapper').children('li').children('ul').removeClass('dropdown-menu');
    $('.main-navbar-list-catalog-wrapper').children('li').children('ul').addClass('main-navbar-list-catalog-hidden');
    $('#nav-catalog-dropdown-link').on('click', function() {
        if ($('.main-navbar-list-catalog-wrapper').hasClass('open')) {
            $('.main-navbar-list-catalog-wrapper').removeClass('open');
            $('#nav-catalog-dropdown-link').removeClass('open');
            $('.main-navbar-list-catalog-wrapper').removeClass('fadeIn animated');
            //$('.main-navbar-list-catalog-wrapper').parents('.container').removeClass('border-fix')
            return false;
        } else {
            //$('.main-navbar-list-catalog-wrapper').parents('.container').addClass('border-fix')
            $('.main-navbar-list-catalog-wrapper').addClass('open');
            $('.main-navbar-list-catalog-wrapper').addClass('fadeIn animated');
            $('#nav-catalog-dropdown-link').addClass('open');
            $('.main-navbar-list-catalog-hidden').removeClass('active');
            return false;
        }
    });
    $('.main-navbar-list-catalog-wrapper > li > a').on('click', function() {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $(this).siblings('ul').removeClass('active');
            $(this).siblings('ul').removeClass('fadeIn animated');
        } else {
            $(this).addClass('active');
            $(this).siblings('ul').addClass('active');
            $(this).siblings('ul').addClass('fadeIn animated');
        }
    });
    var pathname = self.location.pathname;
    //Ð°ÐºÑ‚Ð¸Ð²Ð°Ñ†Ð¸Ñ Ð¼ÐµÐ½ÑŽ
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

    //Àêòèâàöèÿ ëåâîãî ìåíþ êàòàëîãà íà ñòðàíèöå ïðîäóêòà
        
            $('.breadcrumb > li > a').each(function() {
                var linkHref = $(this).attr('href');
                $('.sidebar-nav li').each(function(){
                    if ($(this).attr('data-cid')==linkHref) {
                        $(this).addClass("active");
                        $(this).parent("ul").addClass("active");
                        $(this).parent("ul").siblings('a').addClass("active");
                    }
                });
                $('.sidebar-nav ul').each(function(){
                    if ($(this).hasClass('active')) {
                        $(this).parent('li').removeClass('active');
                    }
                });
            });


        //Àêòèâàöèÿ ñëàéäåðîâ íà ãëàâíîé ñòðàíèöå
	if(!$('.editor_var').length){
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
});
