
<div class="col-xs-12">
    <div class="product-col list clearfix">
        <div class="image">
            <a href="/shop/UID_@productUid@.html" title="@productName@"><img src="@productImg@" alt="@productName@"></a>
        </div>
        <div class="caption">
            <h4><a href="/shop/UID_@productUid@.html" title="@productName@">@productName@</a></h4>
            <div class="description">
                @productDes@
            </div>
            <div class="price">
                <span class="price-new">@productPrice@ <span class="rubznak">@productValutaName@</span></span> 
                <span class="price-old">@productPriceRub@</span>
            </div>
            <div class="cart-button button-group">
                <button class="btn btn-wishlist addToWishList" role="button" data-uid="@productUid@"><i class="fa fa-heart"></i></button>
                <button class="btn btn-wishlist addToCompareList" role="button" data-uid="@productUid@"><i class="fa fa-bar-chart-o"></i></button>
                @ComStartCart@
                <button type="button" class="btn btn-cart addToCartList" data-uid="@productUid@" role="button" data-cart="@productSaleReady@">
                    <i class="fa fa-shopping-cart"></i>                             
                     <span>@productSale@</span>
                </button>
                @ComEndCart@   
                
                                @ComStartNotice@
                <a class="btn btn-cart" href="/users/notice.html?productId=@productUid@" title="@productNotice@">
                    <i class="fa fa-envelope-o" aria-hidden="true"></i>                            
                    Уведомить
                </a>                                   
                @ComEndNotice@ 
            </div>
        </div>
    </div>
</div>