

<div class="product-block-wrapper-fix list-fix">
    <div class="thumbnail">
       
        <div class="product-btn">
        <button class=" addToCompareList " role="button" data-uid="@productUid@"><span class="icons icons-green icons-small icons-compare"></span></button>
       
                <button class=" addToWishList @elementCartHide@" role="button" data-uid="@productlist_product_id@"><span class="icons icons-green icons-small icons-wishlist"></span></button>

        </div>

        <div class="caption ">
        <div class="d-flex  justify-content-between align-items-start last-block">
            
<div class="last-info">
            <div class="product-name"><a href="@shopDir@@productlist_product_url@.html" title="@productName@">@productlist_product_name@</a></div>
                        <div class="d-flex justify-content-between align-items-start">
                <div class="product-price">
                    <div class=" price-old  @php __hide('productlist_product_price_old'); php@">@productlist_product_price_old@</div>
                    <div class="price-new">@productlist_product_price@<span class="rubznak">@productlastview_product_currency@</span></div>

                </div>
              
            </div>
            </div>
            <div class="product-image position-relative">
                <a href="@shopDir@@productlist_product_url@.html" title="@productName@"><img data-src="@productlist_product_pic_small@" alt="@productName@" class="swiper-lazy" src="@productImg@"></a>
            </div>
            </div>

           
        </div>
       
    </div>
</div>