<table align="center" style="margin:10px 0px;"  width="250" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top"><table width="250" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top" width="120" align="center"><A class="highslide" onclick="return hs.expand(this)" href="@productImgBigFoto@" target="_blank" getParams="null" title="@productName@"><img src="@productImg@" alt="@productName@" title="@productName@" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,'@pathTemplate@')" onload="EditFoto(this,@productImgWidth@)"  border="0" ></A><div class=highslide-caption>@productName@</div></td>
    <td valign="top" ><DIV style="padding:0px 0px 5px 5px; ">
		<A href="/shop/UID_@productUid@.html" class="product_name"  title="@productName@">@productName@</A>
				</DIV>
                <TABLE   BORDER=0 CELLPADDING=0 CELLSPACING=0 width="100%" title="Цена: @productName@">
	<TR>
		<TD height="21" style="background:url(../images/price_bg.gif) top left no-repeat; padding-top:3px;padding-left:13px;" >
		<strong class=price > @productPrice@ @productValutaName@</strong><br>
		<div style="margin:7px 0px 5px"><font class=black>@productPriceRub@</font></div>
<div style="margin:5px 0px">@productSklad@</font></div>
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
								<tr>
								   <TD >
								
<img hspace="5"  src="images/shop/application_view_tile.gif" alt="Сравнить" border="0">
    
									</TD>
									<TD valign="middle">
									<A href="javascript:AddToCompare(@productUid@)" class=b  title="Сравнить @productName@">Сравнить</A>
                                    </TD>
								</tr>
							</TABLE></td>
  </tr>
 </table>
</td>
  </tr>
  <tr>
    <td valign="top" style="padding:10px;">@optionsDisp@ @productDes@</td>
  </tr>
</table>


	