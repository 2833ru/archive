<!-- Вывод Товара дня productDay modules/productday/templates -->
<div class="bg-img-hero rounded-lg min-h-450rem p-4 p-sm-8" style="background-image: url(@productDayPicBig@);">
    <span class="d-block small text-danger font-weight-bold text-cap">Товар дня</span>
    <h2>@productDayName@</h2>
    <h2 class="display-4 mb-3">@productDayPrice@ <span class="rubznak">@productValutaName@</span></h2>  
    <h3><strike >@productDayPriceN@<span class="rubznak">@productDayCurrency@</span></strike></h3>

    <!-- Countdown -->
    <div class="w-sm-60">
        <div class="row mx-n2 mb-3">
            <div class="col-4 text-center px-2">
                <div class="border border-dark rounded p-2 mb-1">
                    <span class="js-cd-hours d-block text-dark font-size-2 font-weight-bold">@productDayHourGood@</span>
                </div>
                <span class="d-block text-dark">{Часов}</span>
            </div>
            <div class="col-4 text-center px-2">
                <div class="border border-dark rounded p-2 mb-1">
                    <span class="js-cd-minutes d-block text-dark font-size-2 font-weight-bold">@productDayMinuteGood@</span>
                </div>
                <span class="d-block text-dark">{Минут}</span>
            </div>
            <div class="col-4 text-center px-2">
                <div class="border border-dark rounded p-2 mb-1">
                    <span class="js-cd-seconds d-block text-dark font-size-2 font-weight-bold">@productDayMinuteGood@</span>
                </div>
                <span class="d-block text-dark">{Секунд}</span>
            </div>
        </div>
    </div>
    <!-- End Countdown -->

    <a class="btn btn-sm btn-primary btn-pill transition-3d-hover px-5" href="/shop/UID_@productDayId@.html">Купить</a>
</div>
<script>

    setInterval(function () {
        var h = $(".js-cd-hours").html();
        var m = $(".js-cd-minutes").html();
        var s = parseInt($(".js-cd-seconds").html());

        if (m != "") {
            if (s == 0) {
                if (m == 0) {
                    if (h == 0) {
                        return;
                    }
                    h--;
                    m = 60;
                    if (h < 10)
                        h = "0" + h;
                }
                m--;
                if (m < 10)
                    m = "0" + m;
                s = 59;
            } else
                s--;
            if (s < 10)
                s = "0" + s;

            $(".js-cd-hours").html(h);
            $(".js-cd-minutes").html(m);
            $(".js-cd-seconds").html(s);
        }
    }, 1000);
</script>
<!-- / Товар дня -->