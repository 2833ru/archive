<div class="span span-first-child">
    <div class="left">
        <div class="image2 span2">
            <span class="sale-iconBl">
             <a href="/shop/UID_@productUid@@nameLat@.html" title="@productName@">   @specIcon@
                @newtipIcon@ </a>
            </span>
            <a href="/shop/UID_@productUid@@nameLat@.html" title="@productName@"><img src="@productImg@" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,'@pathTemplate@')" onload="EditFoto(this, @productImgWidth@)" alt="@productName@" title="@productName@" border="0"></a></div>
        <div class="span4">
            <div class="name" ><a style="padding-top:0px; margin:0px 0px 10px 0px;" href="/shop/UID_@productUid@@nameLat@.html" title="@productName@">@productName@</a></div>
            <div class="rate_l">  <div class="rating hidden-phone hidden-tablet">@rateCid@</div> </div>
            <div class="description2">@productDes@</div>
            <div class="wishlist"><a href="javascript:addToWishList(@productUid@)" >Отложить</a></div>
            <div class="compare"><a href="javascript:AddToCompare(@productUid@)" title="Сравнить @productName@">Сравнить</a></div>
        </div>
    </div>
    <div class="span2">
        <div class="price span2 fixp"> @ComStart@
            <div ><span class="price-old">@productPriceRub@</span><br><span>@productPrice@ @productValutaName@</span> </div>

            @ComEnd@ </div>
        <div class="cart center2">
            @ComStartCart@<a class="button" href="javascript:AddToCart(@productUid@)" title="@productSale@">@productSale@</a>@ComEndCart@
            @ComStartNotice@<a class="button" href="/users/notice.html?productId=@productUid@" title="@productNotice@">@productNotice@</a>@ComEndNotice@
        </div>
    </div>
</div>

