<div>
	<div class="product_img">
		<div class="product_img_bg_body_2"><table cellpadding="0" cellspacing="0" border="0"><tr><td>
        <A class=highslide onclick="return hs.expand(this)" href="@productImgBigFoto@" target=_blank getParams="null" title="@productName@"><img src="@productImg@" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,'@pathTemplate@')"  alt="@productName@" title="@productName@" border="0" vspace="0" hspace="0" class="img_cart"></A><div class=highslide-caption>@productName@</div></td></tr></table>
		</div>
		
<div>
        <div class="product_content_3"><A href="/shop/UID_@productUid@@nameLat@.html" title="@productName@">@productName@</A></div>
		<div id="product_price">
			<div class="zag_2">@ComStart@@productPrice@ @productValutaName@ @ComEnd@</div>
		</div>
</div>
   </div>
</div>

<div id="product_cart_3_2">
	<!-- Блок уведомить -->
    @ComStartNotice@
	<A href="/users/notice.html?productId=@productUid@" title="@productNotice@ @productName@"><img src="images/but_add_notice_2.gif" border="0"></A>
	@ComEndNotice@
	<!-- Блок уведомить -->
	
	<!-- Блок корзина -->
	@ComStartCart@
	<A href="javascript:AddToCart(@productUid@)" title="@productSale@ @productName@"><img src="images/but_add_cart_2.gif" border="0"></A>
	@ComEndCart@
	<!-- Блок корзина -->
</div>