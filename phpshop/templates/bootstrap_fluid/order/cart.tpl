<table class="table table-striped">
    <tr>
        <td width="5%" class="hidden-xs"></td>
        <td width="40%"><strong>Наименование</strong></td>
        <td width="10%" align="left"><strong>Кол-во</strong></td>
        <td width="10%" align="center" class="hidden-xs"><strong>Операции</strong></td>
        <td width="15%" align="right" class="hidden-xs"><strong>Цена 1 шт.</strong></td>
        <td width="15%" align="right"><strong>Стоимость</strong></td>
    </tr>
    <tr>
        <td width="5%" class="hidden-xs"></td>
        <td width="40%"></td>
        <td width="10%" align="left"></td>
        <td width="10%" align="center" class="hidden-xs"></td>
        <td width="15%" align="right" class="hidden-xs"></td>
        <td width="15%" align="right"></td>
    </tr>
    @display_cart@
    <tr>
        <td>
            <b>Итого:</b>
        </td>
        <td class="hidden-xs"></td>
        <td width="55" >
            <strong>@cart_num@</strong> (шт.)
        </td>

        <td class="hidden-xs"></td>
        <td align="right" class="hidden-xs"></td>
        <td align="right" class="red">@cart_sum@ <span class="rubznak">@currency@</span></td>
    </tr>
    <!--<tr>
        <td colspan="2">
            Вес товаров:
        </td>
        <td width="55" ></td>
        <td class="mobHideCol" width="30"></td>
        <td align="right" class="red" class="mobHideCol"></td>
        <td align="right" class="red"><span id="WeightSumma">@cart_weight@</span> гр.</td>
        <td align="right" class="red"></td>

    </tr>-->
   
    <tr>
        <td>Скидка:</td>
        <td class="hidden-xs"></td>
        <td class="hidden-xs"></td>
        <td></td>
        <td class="hidden-xs"></td>
        <td align="right" class="red" id="SkiSummaAll"><span id="SkiSumma">@discount@</span>&nbsp;%</td>
    </tr> 
    <tr>
        <td>Доставка:</td>
        <td class="hidden-xs"></td>
        <td class="hidden-xs"></td>
        <td></td>
        <td class="hidden-xs"></td>
        <td align="right" class="red"><span id="DosSumma">@delivery_price@</span>&nbsp; <span class="rubznak">@currency@</span></td>
    </tr>
    
    <tr class="success">
        <td colspan="2">
            К оплате с учетом скидки:
        </td>
        <td class="hidden-xs"></td>
        <td class="hidden-xs"></td>
        <td class="hidden-xs"></td>
        <td align="right" class="red"><span id="WeightSumma" class="hidden">@cart_weight@</span><b><span id="TotalSumma">@total@</span></b> <span class="rubznak">@currency@</span></td>
    </tr>
    
</table>
<input type="hidden" id="OrderSumma" name="OrderSumma"  value="@cart_sum@">
<script>
    $(function() {
       $('#num').html('@cart_num@');
       $('#sum').html('@cart_sum@');
    });
</script>