<tr>
    <td class="visible-lg">            
        <a href="/shop/UID_@prodId@.html"><img class="template-wishlist-list" src="@prodPic@" alt="@prodName@"  title="@prodName@"></a>
    </td>
    <td ><a href="/shop/UID_@prodId@.html">@prodName@</a></td>
    <td >            
        <div class="price">
            @prodPrice@ @productValutaName@
        </div>
    </td>
    <td >
        <a class="btn btn-success btn-sm" title="В корзину" onclick="addToCartList('@prodId@');" @prodDisabled@>+</a>
        <a href="?delete=@prodId@" title="Удалить из списка" class="btn btn-info btn-sm hidden-xs">X</a>
    </td>
</tr>