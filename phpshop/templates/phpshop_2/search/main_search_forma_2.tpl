	<TABLE  border="0" cellpadding="0" cellspacing="10" >
	   <TR>
			<TD width="110">
				<A href="/shop/UID_@productUid@.html" class="product_name"  title="@productName@"><img src="@productImg@"  onerror="NoFoto(this,'@pathTemplate@')"  alt="@productName@" title="@productName@" border="0"></A>
			</TD>
		
			<TD width="300">
				<DIV>
		<A href="/shop/UID_@productUid@.html" class="product_name"  title="@productName@">@productName@</A><br>
@productDes@
				</DIV>
			</TD>

		<TD valign="middle">
	  <TABLE   BORDER=0 CELLPADDING=0 CELLSPACING=0 width="100%" title="Цена: @productName@">
	<TR>
		<TD height="21" style="padding-left:3px"  >
		<strong class=price style="font-size: 15px"> <nobr>@productPrice@ @productValutaName@</nobr></strong><br>
		<font class=black>@productPriceRub@</font><br>
		@productSklad@
		</TD>
	</TR>
</TABLE>
<TABLE cellpadding="0" border="0" cellspacing="0">
							    <tr>
								   <TD >
								   @ComStartCart@
									<img hspace="5" src="images/shop/basket_put.gif" alt="В корзину" border="0">@ComEndCart@ <!-- Блок уведомить -->
    @ComStartNotice@
    <img src="images/icon_email.gif" alt="" border="0" align="absmiddle">
	@ComEndNotice@
	<!-- Блок уведомить -->
									</TD>
									<TD valign="middle">@ComStartCart@<A href="javascript:AddToCart(@productUid@)" class=b  title="Купить @productName@">@productSale@</A>@ComEndCart@@ComStartNotice@<A href="/users/notice.html?productId=@productUid@" title="@productNotice@">@productNotice@</A>@ComEndNotice@
                                    </TD>
								</tr>
							</TABLE>


									</TD>
		</TR>
		
	</TABLE>
