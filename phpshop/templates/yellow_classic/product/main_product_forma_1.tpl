	<TABLE  border="0" cellpadding="0" cellspacing="10" >
	   <TR>
			<TD width="110">
				<A href="/shop/UID_@productUid@.html" class="product_name"  title="@productName@"><img src="@productImg@" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,'@pathTemplate@')" onload="EditFoto(this,@productImgWidth@)" alt="@productName@" title="@productName@" border="0" align="left" hspace="5"></A>
			</TD>
		
			<TD width="340">
				<DIV>
		<A href="/shop/UID_@productUid@.html" class="product_name"  title="@productName@">@productName@</A><br>
@productDes@
				</DIV>
			</TD>
	   <TD width="70">@ComStart@
		<strong class=price > @productPrice@ @productValutaName@</strong><br>
		<font class=black>@productPriceRub@</font>
		@productSklad@
		@ComEnd@
		</TD>
		<TD valign="middle">
		
		
		<TABLE cellpadding="0" border="0" cellspacing="0">
							    <tr>
								   <TD >
								   @ComStartCart@
									<img hspace="5"  src="images/shop/basket_put.gif" alt="В корзину" border="0">@ComEndCart@ <!-- Блок уведомить -->
    @ComStartNotice@
    <img hspace="5" src="images/icon_email.gif" alt="" border="0" align="absmiddle">
	@ComEndNotice@
	<!-- Блок уведомить -->
									</TD>
									<TD valign="middle">@ComStartCart@<A href="javascript:AddToCart(@productUid@)" class=b  title="Купить @productName@">@productSale@</A>@ComEndCart@@ComStartNotice@<A href="/users/notice.html?productId=@productUid@" title="@productNotice@">@productNotice@</A>@ComEndNotice@
                                    </TD>
								</tr>
								<tr>
								   <TD >
								
<img hspace="5"  src="images/shop/application_view_tile.gif" alt="Сравнить" border="0">
    
									</TD>
									<TD valign="middle">
									<A href="javascript:AddToCompare(@productUid@)" class=b  title="Сравнить @productName@">Сравнить</A>
                                    </TD>
								</tr>
							</TABLE>


									</TD>
		</TR>
		
	</TABLE>
