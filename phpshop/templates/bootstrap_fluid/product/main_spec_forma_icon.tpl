
<div class="col-md-12">
    <div class="thumbnail">
        <a class="highslide" onclick="return hs.expand(this);" href="@productImgBigFoto@" target="_blank" getParams="null" title="@productName@"><img src="@productImg@" alt="@productName@"></a>
        <div class="caption">
            <h5><a href="/shop/UID_@productUid@.html" title="@productName@">@productName@</a></h5>
            <h4 class="text-primary">@productPrice@ <span class="rubznak">@productValutaName@</span></h4>
            <h5>@productPriceRub@</h5>
            <div>
                <a class="btn btn-primary btn-sm btn-block addToCartList @elementCartOptionHide@" href="/shop/UID_@productUid@.html">@productSale@</a>
                <button class="btn btn-primary btn-sm btn-block addToCartList @elementCartHide@" data-uid="@productUid@" data-num="1" role="button">@productSale@</button>
                <button class="btn btn-default btn-sm addToWishList btn-block" role="button" onclick="addToWishList('@productUid@');">{Отложить}</button></div>
        </div>
    </div>

</div>
