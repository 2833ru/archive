
<div class="col-xs-12">
    <div class="thumbnail">
       <a class="highslide" onclick="return hs.expand(this);" href="@productImgBigFoto@" target="_blank" title="@productName@"><img src="@productImg@" alt="@productName@"></a>
        <div class="caption">
            <h5><a href="/shop/UID_@productUid@.html" title="@productName@">@productName@</a></h5>
            <h4>@productPrice@ <span class="rubznak">@productValutaName@</span><sup class="text-muted">@productPriceRub@</sup></h4>
            <div>@ComStartCart@<button class="btn btn-primary btn-sm addToCartList btn-block" role="button" data-num="1" data-uid="@productUid@">@productSale@</button>@ComEndCart@ <button class="btn btn-default btn-sm addToWishList btn-block" role="button" onclick="addToWishList('@productUid@');">Отложить</button></div>
        </div>
    </div>
   
</div>
