<div id="bg_catalog_1">@catalogCat@ / @catalogCategory@</div>
<div id="bglist"></div>
<div id=allspec>
    @breadCrumbs@
</div>

<p>
    @DispCatNav@
</p>
<div align="left" style="padding-left:15px;padding-top:0px">
    <span id="catalogContent">@catalogContent@</span>
</div>
<table cellpadding="0" cellspacing="0">
    <tr>

        <td bgcolor="#FAFAFA" id=allspec >
    <NOINDEX>
        <form method="post" action="./CID_@productId@.html" name="sort">
            <table cellpadding="0" cellspacing="0" >
                <tr>
                    <td style="padding:5px" class="black">
                        Сортировка по: <a href="./CID_@productId@_@productPageThis@.html?@productVendor@&f=@productSortNext@&s=@productSort@" title="Изменить направление"><img src="images/shop/@productSortImg@.gif" alt="Изменить направление" hspace="3" border="0" align="absmiddle"></a><a href="./CID_@productId@_@productPageThis@.html?@productVendor@&f=@productSortTo@&s=1" class="@productSortA@">алфавиту</a> - <a href="./CID_@productId@_@productPageThis@.html?@productVendor@&f=@productSortTo@&s=2"  class="@productSortB@">цене</a> - <a href="./CID_@productId@_@productPageThis@.html?@productVendor@&f=@productSortTo@&s=3"  class="@productSortC@">популярности</a>
                    </td>
                </tr>

                <tr>
                    <td style=padding-left:10px;padding-top:10px>

                        Цена от: <input type="text" size="5" name="priceOT" value="@productRriceOT@"> до: <input type="text" size="5" name="priceDO" value="@productRriceDO@">
                        <input type="submit" value="Показать" name="priceSearch">
                        <input type="hidden" value="@productSort@" name="s">
                    </td>
                </tr>
                <tr>
                    <td>
                        @vendorDispTitle@
                        <table cellspacing="0" cellpadding="0">
                            <tr>
                                @vendorDisp@
                                <td> @vendorSelectDisp@</td>
                            </tr>
                        </table>

                    </td>
                </tr>
                <tr>
                    <td style="padding-left:10px;padding-top:5px">
                        @productPageNav@
                    </td>
                </tr>
            </table>
        </form>
    </NOINDEX>
</td>

</tr>
</table>
<div align="right" style="padding:10px">
    <img src="images/shop/page_excel.gif" alt="" border="0" align="absmiddle" hspace="5"><a href="/price/CAT_SORT_@pcatalogId@.html" title="Прайс-лист каталога @catalogCategory@">Прайс-лист каталога</a>
</div>
<table width="680" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0" width="680">
                <!-- main_product_forma -->
                @productPageDis@
                <!-- main_product_forma-->
            </table>
        </td>
    </tr>
    <tr>
        <td width="680" style=padding-left:10px class=style8 colspan="2">
            <div style="padding-top:15px;padding-bottom:15px" class=black>
                @productPageNav@
            </div>
        </td>
    </tr>
</table>