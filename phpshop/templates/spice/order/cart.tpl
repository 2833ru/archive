<table class="table table-bordered">
    <thead>         
        <tr>
            <td class="text-center  hidden-xs">Изображение</td>
            <td class="text-center">Наименование</td>
            <td class="page-cart-td-quantity text-center">Кол-во</td>
            <td class="text-center  hidden-xs">Операции</td>
            <td class="text-center hidden-xs">Цена 1 шт.</td>
            <td class="text-center">Стоимость</td>
        </tr>
    </thead>
    <tbody>
        @display_cart@
    </tbody>
    <tfoot>
        <tr>
            <td style="border-right: none;" class="hidden-xs"></td>
            <td style="border-right: none; border-left: none;"></td>
            <td style="border-right: none; border-left: none;" class="hidden-xs"></td>
            <td style="border-right: none; border-left: none;" class="hidden-xs"></td>
            <td style="border-left: none;" class="text-right">
            <strong>Итого:</strong>
            </td>
            <td class="text-left">
                @cart_sum@ @currency@
            </td>
        </tr>
    </tfoot>
</table>
<input type="hidden" id="OrderSumma" name="OrderSumma"  value="@cart_sum@">
<script>
    $(function() {
       $('#num').html('@cart_num@');
       $('#sum').html('@cart_sum@');
    });
</script>