	<TABLE  border="0" cellpadding="0" cellspacing="10" >
	   <TR>
			<TD width="110">
				<A class="highslide" onclick="return hs.expand(this)" href="@productImgBigFoto@" target="_blank" getParams="null" title="@productName@"><img src="@productImg@" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,'@pathTemplate@')"  alt="@productName@" title="@productName@" border="0" align="left" hspace="5"></A><div class=highslide-caption>@productName@</div>
			</TD>
		
			<TD width="340">
				<DIV>
		<A href="/shop/UID_@productUid@.html" class="product_name"  title="@productName@">@productName@</A><br>
        <br>
@productDes@
				</DIV>
			</TD>
	 
		<TD valign="middle" width="130">
		
		
		<!-- Блок уведомить -->
    @ComStartNotice@
    <img src="images/icon_email.gif" alt="" border="0" align="absmiddle">
	<A href="/users/notice.html?productId=@productUid@" title="@productNotice@">@productNotice@</A>
	@ComEndNotice@
	<!-- Блок уведомить -->
	
	<!-- Блок корзина -->
	@ComStartCart@
    <nobr>
	<table>
<tr>
	<td>  <A href="javascript:AddToCart(@productUid@)" class="b"  title="@productSale@"> <img src="images/basket_put.gif" alt="" border="0" align="absmiddle"></A></td>
	<td>
		<A href="javascript:AddToCart(@productUid@)" class="b"  title="@productSale@">@productSale@</A><br>
<A href="javascript:AddToCompare(@productUid@)" class=b  title="Сравнить @productName@">Сравнить</A>
	</td>
</tr>
</table>

 

    </nobr>
	@ComEndCart@
    <br />

@ComStart@
		<strong class=price >   <nobr>@productPrice@ @productValutaName@  </nobr></strong><br>
		<font class=black>@productPriceRub@</font>
		@productSklad@
		@ComEnd@
	<!-- Блок корзина -->


									</TD>
		</TR>
		
	</TABLE>
