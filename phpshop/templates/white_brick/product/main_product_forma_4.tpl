
<div class="pbox grid4">
    <div class="image">
        <span class="sale-iconBl">
            @specIcon@
            @newtipIcon@
        </span>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td align="center" height="150"> 
                    <a href="/shop/UID_@productUid@@nameLat@.html" title="@productName@">
                        <img src="@productImg@" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,'@pathTemplate@')" onload="EditFoto(this, @productImgWidth@)" alt="@productName@" title="@productName@" border="0"></a>
                </td>
            </tr>
        </table>
    </div>
    <div class="description_4grid hidden-phone hidden-tablet">@productDes@</div>
    <div class="rating hidden-phone hidden-tablet">
        @rateCid@
    </div>
    <div class="name name_height" ><a href="/shop/UID_@productUid@@nameLat@.html" title="@productName@">@productName@</a></div>
    <div class="price"> @ComStart@
        <div align="center"><span class="price-old">@productPriceRub@</span><br> <span>@productPrice@ @productValutaName@</span> </div>

        @ComEnd@ </div>
    <div class="cart">
        @ComStartCart@<a class="button" href="javascript:AddToCart(@productUid@)" title="@productSale@">@productSale@</a>@ComEndCart@
        @ComStartNotice@<a class="button" href="/users/notice.html?productId=@productUid@" title="@productNotice@">@productNotice@</a>@ComEndNotice@
    </div>
    <div class="wishlist"><a href="javascript:addToWishList(@productUid@)" >Отложить</a></div>
    <div class="compare"><a href="javascript:AddToCompare(@productUid@)" title="Сравнить @productName@">Сравнить</a></div>
</div>
