	<TABLE  border="0" cellpadding="0" cellspacing="10" >
	   <TR>
			<TD width="100">
				<A href="/shop/UID_@productUid@.html" class="product_name"  title="@productName@"><img src="@productImg@" onerror="NoFoto(this,'@pathTemplate@')"  alt="@productName@" title="@productName@" border="0" ></A>
			</TD>
		
			<TD width="280">
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
								   
								   
								   <table>
<tr>
	<td> @ComStartCart@ <A href="javascript:AddToCart(@productUid@)" class="b"  title="@productSale@"> <img src="images/basket_put.gif" alt="" border="0" align="absmiddle"></A>@ComEndCart@ </td>
	<td>@ComStartCart@
		<A href="javascript:AddToCart(@productUid@)" class="b"  title="@productSale@">@productSale@</A>@ComEndCart@ <br>
<A href="javascript:AddToCompare(@productUid@)" class=b  title="Сравнить @productName@">Сравнить</A>
	</td>
</tr>
</table>
								   
								   
								   
									<!-- Блок уведомить -->
    @ComStartNotice@
    <img src="images/icon_email.gif" alt="" border="0" align="absmiddle">
	@ComEndNotice@
	<!-- Блок уведомить -->
									</TD>
									
								</tr>
							</TABLE>


									</TD>
		</TR>
		
	</TABLE>
