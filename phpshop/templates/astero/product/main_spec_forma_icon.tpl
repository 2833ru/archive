<div class="col-md-3 col-sm-6 product-block-wrapper">
    <div class="product-col">
        <div class="image product-img-centr">
            <a href="/shop/UID_@productUid@.html" title="@productName@"> <img src="@productImg@"></a>
        </div>
        <div class="caption">
            <h4 class="product-name-fix"><a href="/shop/UID_@productUid@.html" title="@productName@">@productName@</a></h4>
            <div class="description product-description">
                <div class="description-content">
                    @productDes@
                </div>
                <div class="description-product-height-fix"></div>
            </div>
            <div class="price">
                <span class="price-new">@productPrice@ <span class="rubznak">@productValutaName@</span></span> 
                <span class="price-old">@productPriceRub@</span>
            </div>
            <div class="cart-button button-group">
                @ComStartCart@
                <button type="button" class="btn btn-cart addToCartList" role="button" data-num="1" data-uid="@productUid@" data-cart="@productSaleReady@">
                    <i class="fa fa-shopping-cart"></i>                     
                    <span>@productSale@</span>
                </button>
                @ComEndCart@
                <button class="btn btn-wishlist addToWishList" role="button" data-uid="@productUid@" data-title="{Отложить}" data-placement="top" data-toggle="tooltip"><i class="fa fa-heart"></i></button>
                <button class="btn btn-wishlist addToCompareList" role="button" data-uid="@productUid@" data-title="{Сравнить}" data-placement="top" data-toggle="tooltip"><i class="fa fa-bar-chart-o"></i></button>

                @ComStartNotice@
                <a class="btn btn-cart" href="/users/notice.html?productId=@productUid@" title="@productNotice@">
                    <i class="fa fa-envelope-o" aria-hidden="true"></i>                            
                    {Уведомить}
                </a>                                   
                @ComEndNotice@ 

            </div>
        </div>
    </div>
</div>