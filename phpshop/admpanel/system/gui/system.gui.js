
$().ready(function() {

    var theme_new = false;

    // Настройка центрирования
    $('[name="option[watermark_center_enabled]"]').prop('checked', function(_, checked) {
        if (checked) {
            $('[name="option[watermark_right]"]').attr('disabled', true);
            $('[name="option[watermark_bottom]"]').attr('disabled', true);
        }
    });

    $('[name="option[watermark_center_enabled]"]').click(function() {
        $('[name="option[watermark_right]"]').attr('disabled', this.checked);
        $('[name="option[watermark_bottom]"]').attr('disabled', this.checked);
    });


    // Настройка почты
    $('[name="option[mail_smtp_enabled]"]').prop('checked', function(_, checked) {
        if (!checked) {
            $('[name="option[mail_smtp_auth]"]').attr('disabled', true);
            $('[name="option[mail_smtp_host]"]').attr('disabled', true);
            $('[name="option[mail_smtp_port]"]').attr('disabled', true);
            $('[name="option[mail_smtp_user]"]').attr('disabled', true);
            $('[name="option[mail_smtp_pass]"]').attr('disabled', true);
        }
    });

    $('[name="option[mail_smtp_enabled]"]').click(function() {
        var smtp_disabled = this.checked;
        $('[name="option[mail_smtp_auth]"]').attr('disabled', !smtp_disabled);
        $('[name="option[mail_smtp_host]"]').attr('disabled', !smtp_disabled);
        $('[name="option[mail_smtp_port]"]').attr('disabled', !smtp_disabled);
        $('[name="option[mail_smtp_user]"]').attr('disabled', !smtp_disabled);
        $('[name="option[mail_smtp_pass]"]').attr('disabled', !smtp_disabled);
    });

    // Настройка кэширования
    $('[name="option[filter_cache_enabled]"]').prop('checked', function(_, checked) {
        if (!checked) {
            $('[name="option[filter_cache_period]"]').attr('disabled', true);
        }
    });

    $('[name="option[filter_cache_enabled]"]').click(function() {
        var cache_disabled = this.checked;
        $('[name="option[filter_cache_period]"]').attr('disabled', !cache_disabled);
    });


    // Применение темы оформления
    $('#theme_new').on('changed.bs.select', function() {
        theme_new = true;
    });

    // Перезагрузка страницы при смене темы
    $("button[name=editID]").on('click', function(event) {
        event.preventDefault();
        if (theme_new === true) {
            setTimeout(function() {
                window.location.reload();
            }, 5000);
        }
    });

    // закрепление навигации
    if ($('#fix-check:visible').length && typeof(WAYPOINT_LOAD) != 'undefined')
        var waypoint = new Waypoint({
            element: document.getElementById('fix-check'),
            handler: function(direction) {
                $('.navbar-action').toggleClass('navbar-fixed-top');
            },
            offset: '10%'
        });

    $(".tree a[data-view]").on('click', function(event) {
        event.preventDefault();

        $('html, body').animate({scrollTop: $("a[name=" + $(this).attr('data-view') + "]").offset().top - 100}, 500);
    });

    // Продление поддержки
    $(".pay-support").on('click', function(event) {
        event.preventDefault();
        $('[name=product_upgrade]').submit();
    });
});