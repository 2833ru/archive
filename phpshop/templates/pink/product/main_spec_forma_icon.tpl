<div class="product_forma_1" style="height:250px">
   <div class="product_name" align="center">
        <A href="/shop/UID_@productUid@.html"  title="@productName@">@productName@</A>
   </div>
   <div class="product_img">
   
    <table class="product_img_bg" cellpadding="0" cellspacing="0">
<tr>
	<td class="product_img_bg_body">
	   <A href="/shop/UID_@productUid@.html"  title="@productName@"><img src="@productImg@" lowsrc="images/shop/no_photo.gif" onload="EditFoto(this,@productImgWidth@)" onerror="NoFoto(this,'@pathTemplate@')"  alt="@productName@" title="@productName@" border="0"  vspace="15"></A>
	</td>
</tr>
<tr>
	<td class="product_img_bg_footer"></td>
</tr>
</table>

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
</div>