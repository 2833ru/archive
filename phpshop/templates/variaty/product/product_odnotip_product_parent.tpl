<table>
    <tr>
        <td ><br> @parentList@ <br></td>
    </tr>
    <tr>
        <td>
            <span class="input-append"><input class="span1" type="text" id="n@productUid@" name="n@productUid@" value="1" placeholder="1">
            <button onclick="AddToCartParent(@productUid@); return false;" class="btn btn-primary" rel="tooltip" data-original-title="В корзину"><i class="icon-shopping-cart"></i> @productSale@</button></span>
            <button class="btn" onclick="addToWishList(@productUid@); return false;"  data-toggle="tooltip" rel="tooltip" data-original-title="Отложить" data-title="+ Отложить"><i class="icon-heart"></i></button>
            <button onclick="AddToCompare(@productUid@); return false;" class="btn" data-toggle="tooltip" rel="tooltip" data-original-title="Сравнить" data-title="Сравнить @productName@"><i class="icon-refresh"></i></button>
            <input type="hidden" id="parentId" value="@parentCheckedId@"/>
        </td>
    </tr>
</table>
