<div class="product_forma_1" style="height:225px">
   <div class="product_img">
     <div class="product_img_bg">
   <A href="/shop/UID_@productUid@.html"  title="@productName@"><img src="@productImgOdnotip@" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,'@pathTemplate@')" onload="EditFoto(this,@productImgWidth@)" alt="@productName@" title="@productName@" border="0"  vspace="15"></A>
     </div>
     <div class="product_price_bg">
     @ComStart@цена: <strong>@productPrice@</strong> @productValutaName@ @ComEnd@
     </div>
	 <div class="product_cart" align="center">
  <!-- Блок уведомить -->
    @ComStartNotice@
    <img src="images/icon_email.gif" alt="" border="0" align="absmiddle">
	<A href="/users/notice.html?productId=@productUid@" title="@productNotice@">@productNotice@</A>
	@ComEndNotice@
	<!-- Блок уведомить -->
	
	<!-- Блок корзина -->
	@ComStartCart@
    <img src="images/shop/basket_put.gif" alt="" border="0" align="absmiddle">
	<A href="javascript:AddToCart(@productUid@)" title="@productSale@">@productSale@</A>
	@ComEndCart@
	<!-- Блок корзина -->


<img src="images/shop/information.gif" alt="@productInfo@" border="0" align="absmiddle" hspace="5"><A href="/shop/UID_@productUid@.html">@productInfo@</A>
   </div>
   </div>
   <div class="product_content">
        <div><A href="/shop/UID_@productUid@.html"  title="@productName@">@productName@</A></div>
		<div>
		@productDesOdnotip@
		</div>
   </div>
</div>



<!-- <tr>
	<td width="300">
	<A href="/shop/UID_@productUid@.html"  title="@productName@">@productName@</A>
	</td>
	<td>
	<strong class=price > @productPrice@ @productValutaName@</strong><br>
		<font class=black>@productPriceRub@</font>
	</td>
	<td style="padding-left: 10px"><A href="javascript:AddToCart(@productUid@)" class=b  title="Купить @productName@"><img src="images/shop/basket_put.gif" alt="В корзину" border="0" align="absmiddle" hspace="3">@productSale@</A>
	</td>
</tr> -->

