
<div class="col-md-3 col-sm-6 product-block-wrapper">
    <div class="product-block">
        <div class="product-block-top">
            <div class="sale-icon-content">
                @newtipIcon@
                @specIcon@
            </div>
            <div class="product-block-img">
                <a class="product-block-img-link" href="/shop/UID_@productUid@.html" title="@productName@"><img data-src="@productImg@" class="image-fix owl-lazy" alt="@productName@"></a>
            </div>
            <div class="product-block-button">
                <a class="btn btn-cart @elementCartOptionHide@" href="/shop/UID_@productUid@.html"  data-title="@productSale@" data-toggle="tooltip">
                <i class="icons-cart"></i>
                </a>
                <button type="button" class="btn btn-cart addToCartList @elementCartHide@" role="button" data-num="1" data-uid="@productUid@" data-cart="@productSaleReady@" data-title="@productSale@" >
                    <i class="icons-cart"></i>
                </button>
                <a href="#" data-role="/shop/UID_@productUid@.html" class="btn btn-cart fastView" data-toggle="modal" data-target="#modalProductView"><i class="icons-view"></i></a>
                <button class="btn btn-wishlist addToWishList" role="button" data-uid="@productUid@" data-title="{Отложить}" data-placement="top" data-toggle="tooltip"><i class="icons-wishlist"></i></button>
                <button class="btn btn-wishlist addToCompareList" role="button" data-uid="@productUid@" data-title="{Сравнить}" data-placement="top" data-toggle="tooltip"><i class="icons-compare"></i></button>

                <a class="btn btn-cart @elementNoticeHide@" href="/users/notice.html?productId=@productUid@" title="@productNotice@"  data-title="@productNotice@" data-placement="top" data-toggle="tooltip">
                    <i class="fa fa-envelope-o" aria-hidden="true"></i>                            
                </a>                               
            </div>
        </div>
        <div class="product-block-bottom">
            <h3 class="product-block-name product-name-fix">
                <a href="/shop/UID_@productUid@.html" title="@productName@">@productName@</a>
            </h3>
            <h4 class="product-block-price">
                <span class="price-old">@productPriceRub@</span>
                <span class="price-new">@productPrice@ <span class="rubznak">@productValutaName@</span></span>
                <span class="product-sklad-list-block">@productSklad@</span>
            </h4>
        </div>
    </div>
</div>