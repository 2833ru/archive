	<TABLE  border="0" cellpadding="0" cellspacing="10" width="220">
		<TR>
			<TD>
				<DIV>
		<A href="/shop/UID_@productUid@_@productNameLat@.html" class="product_name"  title="@productName@">@productName@</A>
				</DIV>
			</TD>
		</TR>
		<TR>
			<TD style="TEXT-ALIGN: justify" class="text_1">
			<A href="/shop/UID_@productUid@.html" class="product_name"  title="@productName@"><img src="@productImg@" alt="@productName@" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,'@pathTemplate@')" title="@productName@" border="0" align="left" hspace="5"></A>
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
		<font class=black>@productPriceRub@</font>
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
									<img src="images/shop/basket_put.gif" alt="В корзину" border="0">
									</TD>
									<TD valign="middle">
									<A href="javascript:AddToCart(@productUid@)" class=b  title="Купить @productName@"><u>@productSale@</u></A>
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
