
<div class="col-md-4">
    <div class="thumbnail">
       <a class="highslide" onclick="return hs.expand(this);" href="@productImgBigFoto@" target="_blank" getParams="null" title="@productName@"><img src="@productImg@" alt="@productName@"></a>
        <div class="caption">
            <h5><a href="/shop/UID_@productUid@.html" title="@productName@">@productName@</a></h5>
            <h4 class="text-primary">@productPrice@ <span class="rubznak">@productValutaName@</span></h4>
            <h5>@productPriceRub@</h5>
            <div>@ComStartCart@<button class="btn btn-primary btn-sm addToCartList btn-block" role="button" data-num="1" data-uid="@productUid@">@productSale@</button>@ComEndCart@ <button class="btn btn-default btn-sm addToWishList btn-block" role="button" onclick="addToWishList('@productUid@');">Отложить</button></div>
        </div>
    </div>
   
</div>
