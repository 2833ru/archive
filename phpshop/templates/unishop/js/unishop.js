
function changeOfProductRatingView() {
    $('.raiting').each(function(){
        var raitingWidth = $(this).find('#raiting_votes').outerWidth();
        var raitingstarZero = ('<i class="feather iconz-star filled"></i><i class="feather iconz-star filled"></i><i class="feather iconz-star filled"></i><i class="feather iconz-star filled"></i><i class="feather iconz-star filled"></i>')
        var raitingstarOne = ('<i class="feather iconz-star"></i><i class="feather iconz-star filled"></i><i class="feather iconz-star filled"></i><i class="feather iconz-star filled"></i><i class="feather iconz-star filled"></i>');
        var raitingstarTwo = ('<i class="feather iconz-star"></i><i class="feather iconz-star"></i><i class="feather iconz-star filled"></i><i class="feather iconz-star filled"></i><i class="feather iconz-star filled"></i>');
        var raitingstarThree = ('<i class="feather iconz-star"></i><i class="feather iconz-star"></i><i class="feather iconz-star"></i><i class="feather iconz-star filled"></i><i class="feather iconz-star filled"></i>');
        var raitingstarFour = ('<i class="feather iconz-star"></i><i class="feather iconz-star"></i><i class="feather iconz-star"></i><i class="feather iconz-star"></i><i class="feather iconz-star filled"></i>');
        var raitingstarFive = ('<i class="feather iconz-star"></i><i class="feather iconz-star"></i><i class="feather iconz-star"></i><i class="feather iconz-star"></i><i class="feather iconz-star"></i>');

        if (raitingWidth == '0') {
            $(this).children('#raiting_star').children('#raiting').empty();
            $(this).children('#raiting_star').children('#raiting').append(raitingstarZero);
        }
        if (raitingWidth > 1 && raitingWidth <= 16) {
            $(this).children('#raiting_star').children('#raiting').empty();
            $(this).children('#raiting_star').children('#raiting').append(raitingstarOne);
        }
        if (raitingWidth > 17 && raitingWidth <= 32) {
            $(this).children('#raiting_star').children('#raiting').empty();
            $(this).children('#raiting_star').children('#raiting').append(raitingstarTwo);
        }
        if (raitingWidth > 33 && raitingWidth <= 48) {
            $(this).children('#raiting_star').children('#raiting').empty();
            $(this).children('#raiting_star').children('#raiting').append(raitingstarThree);
        }
        if (raitingWidth > 49 && raitingWidth <= 64) {
            $(this).children('#raiting_star').children('#raiting').empty();
            $(this).children('#raiting_star').children('#raiting').append(raitingstarFour);
        }
        if (raitingWidth > 65 && raitingWidth <= 80) {
            $(this).children('#raiting_star').children('#raiting').empty();
            $(this).children('#raiting_star').children('#raiting').append(raitingstarFive);
        }
    });
}

function changeOfReviewsRatingView() {
    var imgRaitingSrcZero = ('/phpshop/templates/astero/images/stars/stars1-0.png')
    var imgRaitingSrcOne = ('/phpshop/templates/astero/images/stars/stars1-1.png')
    var imgRaitingSrcTwo = ('/phpshop/templates/astero/images/stars/stars1-2.png')
    var imgRaitingSrcThree = ('/phpshop/templates/astero/images/stars/stars1-3.png')
    var imgRaitingSrcFour = ('/phpshop/templates/astero/images/stars/stars1-4.png')
    var imgRaitingSrcFive = ('/phpshop/templates/astero/images/stars/stars1-5.png')
    var raitingstarZero = ('<i class="  feather iconz-star filled"></i><i class="  feather iconz-star filled"></i><i class="  feather iconz-star filled"></i><i class="  feather iconz-star filled"></i><i class="  feather iconz-star filled"></i>')
    var raitingstarOne = ('<i class="  feather iconz-star"></i><i class="  feather iconz-star filled"></i><i class="  feather iconz-star filled"></i><i class="  feather iconz-star filled"></i><i class="  feather iconz-star filled"></i>');
    var raitingstarTwo = ('<i class="  feather iconz-star"></i><i class="  feather iconz-star"></i><i class="  feather iconz-star filled"></i><i class="  feather iconz-star filled"></i><i class="  feather iconz-star filled"></i>');
    var raitingstarThree = ('<i class="  feather iconz-star"></i><i class="  feather iconz-star"></i><i class="  feather iconz-star"></i><i class="  feather iconz-star filled"></i><i class="  feather iconz-star filled"></i>');
    var raitingstarFour = ('<i class="  feather iconz-star"></i><i class="  feather iconz-star"></i><i class="  feather iconz-star"></i><i class="  feather iconz-star"></i><i class="  feather iconz-star filled"></i>');
    var raitingstarFive = ('<i class="  feather iconz-star"></i><i class="  feather iconz-star"></i><i class="  feather iconz-star"></i><i class="  feather iconz-star"></i><i class="  feather iconz-star"></i>');
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

$(document).ready(function() {
    /*$(window).on('scroll', function() {

        if ($(window).scrollTop() >= $('.main-container, .slider').offset().top) {
            $('#main-menu').addClass('navbar-fixed-top')
            // toTop          
            $('#toTop'). deIn();
        } else {
            $('#main-menu').removeClass('navbar-fixed-top');
            $('#toTop'). deOut();
       
        }
    });*/
    changeOfProductRatingView();
    setInterval(changeOfReviewsRatingView, 100)
    $(document).on('click', function() {
        changeOfReviewsRatingView();
    })
    $('.sidebar-nav > li').removeClass('dropdown');
    $('.sidebar-nav > li > ul').removeClass('dropdown-menu');
    $('.sidebar-nav > li > a.open-cat').on('click', function() {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $(this).siblings('ul').removeClass('active');
            return  false;
        } else {
            $(this).addClass('active');
            $(this).siblings('ul').addClass('active');
            $(this).siblings('ul').addClass(' deIn animated');
            return  false;
        }
    });
    $('.main-navbar-list-catalog-wrapper').children('li').children('ul').removeClass('dropdown-menu');
    $('.main-navbar-list-catalog-wrapper').children('li').children('ul').addClass('main-navbar-list-catalog-hidden');
    $('#nav-catalog-dropdown-link').on('click', function() {
        if ($('.main-navbar-list-catalog-wrapper').hasClass('open')) {
            $('.main-navbar-list-catalog-wrapper').removeClass('open');
            $('#nav-catalog-dropdown-link').removeClass('open');
            $('.main-navbar-list-catalog-wrapper').removeClass(' deIn animated');
        } else {
            $('.main-navbar-list-catalog-wrapper').addClass('open');
            $('.main-navbar-list-catalog-wrapper').addClass(' deIn animated');
            $('#nav-catalog-dropdown-link').addClass('open');
            $('.main-navbar-list-catalog-hidden').removeClass('active');
        }
    });
    $('.main-navbar-list-catalog-wrapper > li > a').on('click', function() {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $(this).siblings('ul').removeClass('active');
            $(this).siblings('ul').removeClass(' deIn animated');
        } else {
            $(this).addClass('active');
            $(this).siblings('ul').addClass('active');
            $(this).siblings('ul').addClass(' deIn animated');
        }
    });
    var pathname = self.location.pathname;
    //активация меню
    $(".sidebar-nav li").each(function(index) {

        if ($(this).attr("data-cid") == pathname) {
            var cid = $(this).attr("data-cid-parent");
            $("#cid" + cid).addClass("active");
            $("#cid" + cid).attr("aria-expanded", " lse");
            $("#cid-ul" + cid).addClass("active");
            $(this).children('ul').addClass("active");
            $(this).children('a').addClass("active");
            $(this).parent("ul").addClass("active");
            $(this).parents(".sidebar-nav-list-item").addClass("active");
        }
    });

    $('.main-menu-button').on('click', function(){
        if ($('#main-menu').hasClass('main-menu-fix')) {
            $('#main-menu').removeClass('main-menu-fix');
            $('body').removeClass('overflow-fix');
        }else{
            $('#main-menu').addClass('main-menu-fix');
            $('body').addClass('overflow-fix');
        }
    });
});
