<div class="block hidden-xs visible-lg visible-md product-day">
  <div class="block-heading">
    <h3 class="block-title">{Товар дня}</h3>
  </div>
  <div class="block-body">
     <a href="/shop/UID_@productDayId@.html" class="product-day-link">
            <img class="media-object" src="@productDayPicSmall@" alt="@productDayName@">
        </a>
        <div>
            <h4><a href="/shop/UID_@productDayId@.html">@productDayName@</a></h4>
            @productDayDescription@
        </div>
        <h3 class="product-price">@productDayPrice@<span class="rubznak">@productValutaName@</span> <span class="price-old">@productDayPriceN@ <span class="rubznak">@productDayCurrency@</span></span></h3>
        <br>
        <div class="clock" data-hour="@productDayTimeGood@"></div>
  </div>
</div>

<link rel="stylesheet" href="@php echo $GLOBALS['SysValue']['dir']['templates'].chr(47).$_SESSION['skin']; php@css/flipclock.css">