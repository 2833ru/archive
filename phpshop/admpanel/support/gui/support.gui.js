
$().ready(function() {

    // Ответ на заявку
    $(".send-message").on('click', function(event) {
        event.preventDefault();

        if ($('textarea[name=message]').val() != "") {

            var data = [];
            var id = $.getUrlVar('id');
            data.push({name: 'selectID', value: 2});
            data.push({name: 'ajax', value: 1});
            data.push({name: 'actionList[selectID]', value: 'actionReplies'});
            data.push({name: 'name', value: escape($(this).attr('data-name'))});
            data.push({name: 'message', value: escape($('textarea[name=message]').val())});
            data.push({name: 'attachment', value: escape($('input[name=attachment]').val())});

            $.ajax({
                mimeType: 'text/html; charset='+locale.charset,
                url: '?path=support&id=' + id,
                type: 'post',
                data: data,
                dataType: "html",
                async: false,
                success: function() {
                    window.location.reload();
                }

            });
        }
    });

    $("textarea[name=message]").on('click', function(event) {
        $(".send-message").removeClass('disabled');
    });

    $("#attachment-disp").on('click', function(event) {
        $(this).toggleClass('hide');
        $("#attachment").toggleClass('hide');
    });

    $("button[name=noSupport]").on('click', function() {
        window.open('https://help.phpshop.ru/new/');
    });


    // Закрыть заявку
    $(".support-close").on('click', function(event) {
        event.preventDefault();

        $.MessageBox({
            buttonDone: "OK",
            buttonFail: locale.cancel,
            message: locale.confirm_support_close
        }).done(function() {

            var data = [];
            var id = $.getUrlVar('id');
            data.push({name: 'selectID', value: 2});
            data.push({name: 'ajax', value: 1});
            data.push({name: 'actionList[selectID]', value: 'actionClose'});

            $.ajax({
                mimeType: 'text/html; charset='+locale.charset,
                url: '?path=support&id=' + id,
                type: 'post',
                data: data,
                dataType: "html",
                async: false,
                success: function() {
                    window.location.href = '?path=support';
                }

            });
        })
    });

    // закрепление навигации
    if ($('#fix-check').length && typeof(WAYPOINT_LOAD) != 'undefined')
        var waypoint = new Waypoint({
            element: document.getElementById('fix-check'),
            handler: function(direction) {
                $('.navbar-action').toggleClass('navbar-fixed-top');
            },
            offset: '10%'
        });

});