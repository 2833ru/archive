<tr>
    <td  class="hidden-xs">
        <a href="/shop/UID_@cart_id@.html" title="@cart_name@"><img src="@cart_pic_small@" border="0" hspace="5" vspace="5" align="absmiddle" style="max-width: 40px; max-height: 40px;"></a>
    </td>
    <td>
        <a href="/shop/UID_@cart_id@.html" title="@cart_name@">@cart_name@</a>
    </td>
    <td>
        <table cellpadding="0" cellspacing="0">
            <tr>

                <td align="center">
                    <form name="forma_cart" method="post" id="forma_cart">
                        <input type="text" value="@cart_num@" size="3" maxlength="5" name="num_new" onchange="this.form.submit()">
                        <input type=hidden name="id_edit" value="@cart_xid@">
                    </form>
                </td>


            </tr>
        </table>
    </td>
    <td class="hidden-xs">
        <table  align="center">
            <tr>
                <td class="hidden-xs">
                    <form name="forma_cart_plus" method="post" id="forma_cart_plus">
                        <button type="submit" class="btn label label-success" data-toggle="tooltip" data-placement="top" title="+1">+</button>
                        <input type=hidden name="id_edit" value="@cart_xid@">
                        <input type=hidden name="edit_num" value="edit">
                        <input type=hidden name="num_new" value="@cart_num@">
                    </form>
                </td>
                <td class="hidden-xs" style="padding-left:5px">
                    <form name="forma_cart_minus" method="post" id="forma_cart_minus">
                        <button type="submit" class="btn label label-info" data-toggle="tooltip" data-placement="top" title="-1">-</button>
                        <input type=hidden name="id_edit" value="@cart_xid@">
                        <input type=hidden name="edit_num" value="minus">
                        <input type=hidden name="num_new" value="@cart_num@">
                    </form>
                </td>
                <td style="padding-left:5px">
                    <form name="forma_cart_del" method="post" id="forma_cart_del">
                        <button type="submit" class="btn label label-warning" data-toggle="tooltip" data-placement="top" title="Удалить">x</button>
                        <input type=hidden name="id_delete" value="@cart_xid@">
                    </form>
                </td>
            </tr>
        </table>
    </td>
    <td align="right" class="hidden-xs">@cart_price@ <span class="rubznak">@currency@</span></td>
    <td align="right">@cart_price_all@ <span class="rubznak">@currency@</span>  </td>
</tr>