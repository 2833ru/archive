
<div class="col-md-12">
	<span class="sale-icon-content">
                @specIcon@
                @newtipIcon@
                @hitIcon@
               @promotionsIcon@
            </span>
    <div class="thumbnail">
        <a href="/shop/UID_@productUid@.html" target="_blank" getParams="null" title="@productName@"><img data-src="@productImg@" alt="@productName@"></a>
        <div class="caption">
            <h5><a href="/shop/UID_@productUid@.html" title="@productName@">@productName@</a></h5>
           
            <h4 class="product-price">@productPrice@<span class="rubznak">@productValutaName@</span><span class=" price-old">@productPriceOld@</span></h4>

        </div>
            <div class="stock">
@ComStartNotice@
<div class="outStock">@productOutStock@</div>
@ComEndNotice@
<span class="product-sklad-list-block">@productSklad@</span>
</div>
                <a class="btn btn-primary btn-sm btn-block addToCartList @elementCartOptionHide@" href="/shop/UID_@productUid@.html">@productSale@</a>
                <button class="btn btn-primary btn-sm btn-block addToCartList @elementCartHide@" data-uid="@productUid@" data-num="1">@productSale@</button>
							 <a class="btn btn-primary btn-sm btn-block  @elementNoticeHide@" href="/users/notice.html?productId=@productUid@" title="@productNotice@" >
                    @productNotice@                         
                </a>  
                <button class="btn btn-default btn-sm addToWishList btn-block" onclick="addToWishList('@productUid@');">{Отложить}</button></div>
        </div>
