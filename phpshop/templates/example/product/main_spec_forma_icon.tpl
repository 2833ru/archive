<table cellpadding="5" cellspacing="0" border="1" width="100%" style="margin-bottom: 10px">
  <tr>
    <td>
      <a class="highslide" onclick="return hs.expand(this)" href="@productImgBigFoto@" target="_blank" getParams="null" title="@productName@"><img src="@productImg@" lowsrc="images/shop/no_photo.gif" onload="EditFoto(this,@productImgWidth@)" onerror="NoFoto(this,'@pathTemplate@')"  alt="@productName@" title="@productName@" border="0"></a><div class="highslide-caption">@productName@</div>
    </td>
  </tr>
  <tr>
	<td>
    
      <!-- Вывод имени товара -->
      <div style="padding-bottom:6px"><a href="/shop/UID_@productUid@.html" title="@productName@">@productName@</a></div>
      <!-- Вывод имени товара -->
      
      <!-- Вывод краткого описания для тематических товаров -->
      @productDesOdnotip@
      <!-- Вывод краткого описания для тематических товаров -->
      
    </td>
  </tr>
  <tr>
	<td>
    @ComStart@
    
      <!-- Вывод старой цены -->
	  @productPriceRub@
      <!-- Вывод старой цены -->
      
      <div style="padding-bottom:6px; padding-top:6px"> цена: <strong>@productPrice@</strong> @productValutaName@  </div>
      
      <!-- Вывод количества на складе -->
	  @productSklad@
      <!-- Вывод количества на складе -->
      
    @ComEnd@
    </td>
  </tr>
  <tr>
	<td align="center">
        <!-- Блок уведомить -->
        @ComStartNotice@ <img src="images/icon_email.gif" alt="" border="0" align="absmiddle" hspace="5">
        <a href="/users/notice.html?productId=@productUid@" title="@productNotice@">@productNotice@</a> @ComEndNotice@
        <!-- Блок уведомить -->

        <!-- Блок корзина -->
        @ComStartCart@ <img src="images/shop/basket_put.gif" alt="" border="0" align="absmiddle" hspace="5">
        <a href="javascript:AddToCart(@productUid@)" title="@productSale@">@productSale@</a> @ComEndCart@
        <!-- Блок корзина -->
    </td>
  </tr>
  <tr>
	<td align="center">
        <!-- Блок подробно  -->
        <img src="images/shop/information.gif" alt="@productInfo@" border="0" align="absmiddle" hspace="5">
        <a href="/shop/UID_@productUid@.html">@productInfo@</a>
        <!-- Блок подробно -->
    </td>
  </tr>
  <tr>
	<td align="center">
        <!-- Блок сравнить -->
        <img src="images/shop/application_view_tile.gif" alt="Сравнить" border="0" align="absmiddle" hspace="5">
        <a href="javascript:AddToCompare(@productUid@)" title="Сравнить @productName@">Сравнить</a>
        <!-- Блок сравнить -->
     </td>
  </tr>
</table>
