<div class="col-xs-12 product-col-1">
    <div class="product-col">

        <div class="image product-img-centr">
            <a href="/shop/UID_@productUid@.html" title="@productName@"> <img data-src="@productImg@" alt="@productName@" class="img-responsive img-center-sm" ></a>
        </div>

        <div class="caption">
            <h4><a href="/shop/UID_@productUid@.html" title="@productName@">@productName@</a></h4>

            <div class="rating">@rateCid@</div>

            <div class="price">
                <span class="price-new">@productPrice@ <span class="rubznak">@productValutaName@</span></span> 
                <span class="price-old">@productPriceRub@</span>
            </div>
            <div class="cart-button button-group">
                @ComStartCart@
                <button type="button" class="btn btn-cart addToCartList" data-num="1" data-uid="@productUid@" data-cart="@productSaleReady@">Купить</button>
                @ComEndCart@

                @ComStartNotice@
                <a class="btn btn-cart" href="/users/notice.html?productId=@productUid@" title="@productNotice@">Уведомить</a>                                   
                @ComEndNotice@ 

                <button class="btn btn-compare addToCompareList" data-uid="@productUid@"><i class="fa fa-sliders" aria-hidden="true"></i>Сравнить</button>
                <button class="btn btn-wishlist addToWishList" data-uid="@productUid@"><i class="fa fa-heart-o" aria-hidden="true"></i>Отложить</button>

            </div>
        </div>
        <div class="sale-icon-content">
            @specIcon@
            @newtipIcon@
            @promotionsIcon@
        </div>
    </div>
</div>