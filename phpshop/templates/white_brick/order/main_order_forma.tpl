@checkLabelForOldTemplatesNoDelete@
@order_action_add@
<form method="post" name="forma_order" id="forma_order" action="/done/">
    <div id="checkout">
        <div id="checkout">
              <b>Заказ №</b>
                    <input type="text" name=ouid style="width:50px; height:18px; font-family:tahoma; font-size:11px ; color:#9e0b0e; background-color:#f2f2f2;" value="@orderNum@"  readonly="1">
                    <b>/</b>
                    <input type="text" style="width:50px; height:18px; font-family:tahoma; font-size:11px ; color:#9e0b0e; background-color:#f2f2f2;" value="@orderDate@"  readonly="1"><BR>
            <div class="checkout-heading">Личные данные@authData@</div>
            @noAuth@
        </div>
        <div id="checkout">
            <div class="checkout-heading">Доставка, адрес получателя</div><br>
            <div class="checkout-content" style="display: block;">
                <div class="left">                    
                    @orderDelivery@ 
                    <BR><BR>
                    @UserAdresList@
                </div>
                <div id="login" class="right">
                  
                    @noAuthAdr@
                    <div id="userAdresData">
                    </div>
                    <br>
                    Дополнительная информация к заказу: 
                    <textarea style="width:225px; height:100px; font-family:tahoma; font-size:11px ; color:#4F4F4F " name="dop_info" id="dop_info"></textarea>
                </div>
            </div>
            <BR>
        </div>
        <div id="checkout">
            <div class="checkout-heading">Способ оплаты</div>
            <BR>
            <div class="checkout-content" style="display: block;">
                @orderOplata@
                <br>
                <div id="showYurDataForPaymentLoad">
                </div>
            </div>
        </div>
        <div id="checkout">
        <hr>
            <BR>
            <div class="checkout-content" style="display: block;">
                <div class="left">
                    <img src="images/shop/brick_error.gif" border="0" align="absmiddle"> <a href="javascript:forma_order.reset();" class=link>Очистить форму</a>
                    <input type="hidden" name="send_to_order" value="ok" >
                    <input type="hidden" name="d" id="d" value="@deliveryId@">
                    <input type="hidden" name="nav" value="done">
                </div>
                <div id="login" class="right">
                    <img src="images/shop/brick_go.gif"  border="0" align="absmiddle"> <a href="javascript:OrderChekJq();" class=link>Оформить заказ</a>
                </div>
            </div>
            <BR>
        </div>
    </div>
</form>
@showYurDataForPayment@