<table width="242" border="0" cellpadding="0" cellspacing="5" style="">
    <tr>
        <td height="35" align="left" valign="top"><A href="/shop/UID_@productUid@.html" class="product_name"  title="@productName@"><u><span class="MiddleTovarText2">@productName@</span></u></A></td>
    </tr>
    <tr>
        <td width="242" height="130" align="left" valign="top"><div style="float:left"><A class=highslide onclick="return hs.expand(this)" href="@productImgBigFoto@" target=_blank getParams="null" title="@productName@"><img src="@productImg@" alt="@productName@" title="@productName@" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,'@pathTemplate@')" onload="EditFoto(this, @productImgWidth@)"  border="0" align="left" hspace="5"></A><div class=highslide-caption>@productName@</div></div>
            <div>@productDes@</div>
            </td>
    </tr>
    <tr>
        <td height="39">
            <TABLE>
                <TR>
                    <td width="75%" align="left" valign="top">
                        <TABLE  BORDER=0 CELLPADDING=0 CELLSPACING=0 width="100%" title="Цена: @productName@">
                            <TR>
                                <TD align="right" class="center">
                                    <strong class=price > @productPrice@ @productValutaName@</strong><br>
                                    <font class=black>@productPriceRub@</font><br>
                                    @productSklad@		</TD>
                            </TR>
                        </TABLE>								</td>
                    <TD  width="77" height="25" align="right" valign="top">
                        <TABLE cellpadding="2" cellspacing="0">
                            <tr>
                                <TD valign="top">
                                    @ComStartCart@
                                    <img src="images/shop/basket_put.gif" alt="В корзину" border="0">@ComEndCart@ <!-- Блок уведомить -->
                                    @ComStartNotice@
                                    <img src="images/icon_email.gif" alt="" border="0" align="absmiddle">
                                    @ComEndNotice@
                                    <!-- Блок уведомить -->									</TD>
                                <TD valign="top">
                                    <!-- Блок корзина -->
                                    @ComStartCart@
                                    <A href="javascript:AddToCart(@productUid@)" class=b  title="Купить @productName@"><u>@productSale@</u></A>@ComEndCart@
                                    <!-- Блок корзина -->
                                    <!-- Блок уведомить -->
                                    @ComStartNotice@
                                    <A href="/users/notice.html?productId=@productUid@" class=b title="@productNotice@">@productNotice@</A>
                                    @ComEndNotice@
                                    <!-- Блок уведомить -->									</TD>
                            </tr>
                            <tr>
                                <TD >

                                    <img   src="images/shop/application_view_tile.gif" alt="Сравнить" border="0">									</TD>
                                <TD valign="middle">
                                    <A href="javascript:AddToCompare(@productUid@)" class=b  title="Сравнить @productName@"><u>Сравнить</u></A>                                    </TD>
                            </tr>
                        </TABLE>						</TD>
                </TR>
            </TABLE>    </td>
    </tr>
</table>
