	<TABLE  border="0" cellpadding="0" cellspacing="10" width="340">
		<TR>
			<TD>
				<DIV>
		<A href="/shop/UID_@productUid@.html" class="product_name"  title="@productName@">@productName@</A>
				</DIV>
			</TD>
		</TR>
		<TR>
			<TD style="TEXT-ALIGN: justify" class="text_1">
			<A href="/shop/UID_@productUid@.html" class="product_name"  title="@productName@"><img src="@productImg@" alt="@productName@" title="@productName@" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,'@pathTemplate@')" onload="EditFoto(this,@productImgWidth@)"  border="0" align="left" hspace="5"></A>
			@productDes@
			</TD>
		</TR>
		<TR>
			<TD align="right">
						<TABLE>
								<TR>
								<td>
								<TABLE  BORDER=0 CELLPADDING=0 CELLSPACING=0 width="100%" title="Цена: @productName@">
	<TR>
		<TD align="center" class="center">
		<strong class=price > @productPrice@ @productValutaName@</strong><br>
		<font class=black>@productPriceRub@</font><br>
		@productSklad@
		</TD>
	</TR>
</TABLE>
								</td>
								<td style=padding-top:5>
<img src="images/shop/line3.gif" alt="" width="1" height="35" border="0">
								</td>
						<TD  width="77" height="25">
							<TABLE cellpadding="2" cellspacing="0">
							    <tr>
								   <TD>
								   @ComStartCart@
									<img src="images/shop/basket_put.gif" alt="В корзину" border="0">@ComEndCart@ <!-- Блок уведомить -->
    @ComStartNotice@
    <img src="images/icon_email.gif" alt="" border="0" align="absmiddle">
	@ComEndNotice@
	<!-- Блок уведомить -->
									</TD>
									<TD valign="middle">
									<!-- Блок корзина -->
									@ComStartCart@
									<A href="javascript:AddToCart(@productUid@)" class=b  title="Купить @productName@"><u>@productSale@</u></A>@ComEndCart@
 <!-- Блок корзина -->
  <!-- Блок уведомить -->
    @ComStartNotice@
	<A href="/users/notice.html?productId=@productUid@" title="@productNotice@">@productNotice@</A>
	@ComEndNotice@
	<!-- Блок уведомить -->
									</TD>
								</tr>
								<tr>
								   <TD >
								
<img   src="images/shop/application_view_tile.gif" alt="Сравнить" border="0">
    
									</TD>
									<TD valign="middle">
									<A href="javascript:AddToCompare(@productUid@)" class=b  title="Сравнить @productName@">Сравнить</A>
                                    </TD>
								</tr>
							</TABLE>
						</TD>
					</TR>
				</TABLE>
			</TD>
		</TR>
		<tr>
		   <td>
		   <!-- 	<div >
Продавец: <A href="/shop/SIDI_@productUserId@.html"  title="@productUserName@">@productUserName@</A>
	</div> -->

		   </td>
		</tr>
	</TABLE>
