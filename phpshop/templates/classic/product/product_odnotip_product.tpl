	<TABLE  border="0" cellpadding="0" cellspacing="10" >
	   <TR>
			<TD width="110">
				<A href="/shop/UID_@productUid@_@productNameLat@.html" class="product_name"  title="@productName@"><img src="@productImg@" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,'@pathTemplate@')" onload="EditFoto(this,@productImgWidth@)" alt="@productName@" title="@productName@" border="0" align="left" hspace="5"></A>
			</TD>
		
			<TD width="340">
				<DIV>
		<A href="/shop/UID_@productUid@_@productNameLat@.html" class="product_name"  title="@productName@">@productName@</A><br>
				</DIV>
			</TD>
	   <TD width="70">@ComStart@
		<strong class=price > @productPrice@ @productValutaName@</strong><br>
		<font class=black>@productPriceRub@</font>
		@productSklad@
		@ComEnd@
		</TD>
		<TD valign="middle">
		
		
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


									</TD>
		</TR>
		
	</TABLE>


