<tr>
    <td class="text-center page-cart-td-align-middle hidden-xs">
        <a href="/shop/UID_@cart_id@.html" title="@cart_name@">
            <img src="@cart_pic_small@" alt="@cart_name@" title="@cart_name@" class="img-thumbnail">
        </a>
    </td>
    <td class="text-center page-cart-product-name-td page-cart-td-align-middle">
        <a href="/shop/UID_@cart_id@.html" title="@cart_name@">@cart_name@</a>
    </td>                           
    <td class="text-center page-cart-td-align-middle">
        <form name="forma_cart" method="post" id="forma_cart">
            <input type="text" value="@cart_num@" size="3" maxlength="5" name="num_new" onchange="this.form.submit()">
            <input type=hidden name="id_edit" value="@cart_xid@">
        </form>
    </td>
    <td class="text-center page-cart-product-button-block page-cart-td-align-middle hidden-xs">
       <form name="forma_cart_plus" method="post" id="forma_cart_plus">
            <button type="submit" class="btn btn-default tool-tip" data-toggle="tooltip" data-placement="top" title="+1">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
            </button>
            <input type=hidden name="id_edit" value="@cart_xid@">
            <input type=hidden name="edit_num" value="edit">
            <input type=hidden name="num_new" value="@cart_num@">
        </form>
        <form name="forma_cart_minus" method="post" id="forma_cart_minus">
            <button type="submit" class="btn btn-default tool-tip" data-toggle="tooltip" data-placement="top" title="-1">
                <i class="fa fa-minus-circle" aria-hidden="true"></i>
            </button>
            <input type=hidden name="id_edit" value="@cart_xid@">
            <input type=hidden name="edit_num" value="minus">
            <input type=hidden name="num_new" value="@cart_num@">
        </form>
        <form name="forma_cart_del" method="post" id="forma_cart_del">
            <button type="submit" class="btn btn-default tool-tip" data-toggle="tooltip" data-placement="top" title="Удалить">
                <i class="fa fa-times" aria-hidden="true"></i>
            </button>
            <input type=hidden name="id_delete" value="@cart_xid@">
        </form>
    </td>
    <td class="text-center page-cart-td-align-middle  hidden-xs">
       @cart_price@ @currency@
    </td>
    <td class="text-center page-cart-td-align-middle">
        @cart_price_all@ @currency@
    </td>
</tr>