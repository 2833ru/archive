<table cellpadding="0" cellspacing="0" border="0"  width="180">
  <tbody>
    <tr>
      <td align="center" valign="middle"><a class="highslide" onclick="return hs.expand(this)" href="@productImgBigFoto@" target="_blank" getParams="null" title="@productName@"><img src="@productImg@" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,'@pathTemplate@')" onload="EditFoto(this,@productImgWidth@)" alt="@productName@" title="@productName@" border="0"></a><div class=highslide-caption>@productName@</div></td>
    </tr>
  </tbody>
</table>
<div class="NameCart"><a href="/shop/UID_@productUid@.html" title="@productName@">@productName@</a></div>
<div class="ContentCart">@productDes@</div>
@ComStart@
<span class="OldPriceNewtip">@productPriceRub@</span>
<div class="PriceNewtip">@productPrice@ @productValutaName@</div>
<span class="SkladNewtip">@productSklad@</span>
@ComEnd@
<div>
<!-- Блок корзина -->
@ComStartCart@
<div class="Cart"><a href="javascript:AddToCart(@productUid@)" title="@productSale@"><img src="images/furniture_19.gif" border="0"></a></div>
@ComEndCart@
<!-- Блок корзина -->

<!-- Блок уведомить -->
@ComStartNotice@
<div class="Cart"><a href="/users/notice.html?productId=@productUid@" title="@productNotice@"><img src="images/furniture_21.gif" border="0"></a></div>
@ComEndNotice@
<!-- Блок уведомить -->

<div class="Compare"><a href="javascript:AddToCompare(@productUid@)" title="Сравнить @productName@"><img src="images/furniture_20.gif" border="0"></a></div>
</div>

<div class="CartLine"></div>