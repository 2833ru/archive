<div class="col-md-6">
    <div class="thumbnail">
        <span class="sale-icon-content">
            @specIcon@
            @newtipIcon@
        </span>
        <a class="highslide" href="@productImgBigFoto@" title="@productName@"><img data-src="@productImg@" alt="@productName@"></a>
        <div class="caption description">
            <h4><a href="/shop/UID_@productUid@.html" title="@productName@">@productName@</a></h4>
            @productDes@
        </div>
        <div class="btn-sale">
            <h3>@productPrice@ <span class="rubznak">@productValutaName@</span> <sup class="text-muted small">@productPriceRub@</sup></h3>
            <a class="btn btn-primary addToCartList btn-sm @elementCartOptionHide@" href="/shop/UID_@productUid@.html">@productSale@</a>
            <button class="btn btn-primary addToCartList btn-sm @elementCartHide@" data-uid="@productUid@" role="button">@productSale@</button> 
            <button class="btn btn-default addToWishList btn-sm" role="button" data-uid="@productUid@">{Отложить}</button></div>
    </div>
</div>
