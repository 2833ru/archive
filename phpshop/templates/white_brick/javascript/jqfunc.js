$(document).ready(function() {

    $('#cart > .heading a').live('mouseover', function() {
        if ($('#cart .content div').html()) {
            $('#cart').addClass('active');
        }
        $('#cart').live('mouseleave', function() {
            $(this).removeClass('active');
        });
    });

    $('.links .showUserMenu, .links .user-forma').live('mouseover', function() {
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
});

function opendcAccordion(par) {
    $("a.leftCatNt" + par).addClass('active');
}