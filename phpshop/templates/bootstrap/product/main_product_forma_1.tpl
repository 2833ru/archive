<div class="panel panel-default">
    <div class="panel-body">
        <div class="media">
            <span class="sale-icon-content">
                @specIcon@
                @newtipIcon@
            </span>

            <a class="media-left highslide" href="@productImgBigFoto@" title="@productName@"><img src="@productImg@" alt="@productName@"></a>
            <div class="media-body">
                <h3 class="media-heading"><a href="/shop/UID_@productUid@.html" title="@productName@">@productName@</a></h3>
                <p>@productDes@</p>
                <h3>@productPrice@ <span class="rubznak">@productValutaName@</span></h3>
                <h5>@productPriceRub@</h5>
                <div class="pull-right">
                    <a class="btn btn-primary addToCartList @elementCartOptionHide@" href="/shop/UID_@productUid@.html">@productSale@</a>
                    <button class="btn btn-primary addToCartList @elementCartHide@" data-uid="@productUid@" role="button">@productSale@</button>
                    <button class="btn btn-default addToWishList" role="button" data-uid="@productUid@">{Отложить}</button>
                </div>
            </div>
        </div>
    </div>
</div>