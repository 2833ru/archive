@checkLabelForOldTemplatesNoDelete@
@order_action_add@

<div class="page-header">
    <h2>Заказ №@orderNum@</h2>
</div>

<form role="form" method="post" name="forma_order" id="forma_order" action="/done/">

    <div class="panel panel-default">
        <input type="hidden" name="ouid" value="@orderNum@" readonly="1">
        <input type="hidden" value="@orderDate@"  readonly="1">
        <div class="panel-heading">
            <h3 class="panel-title">Личные данные</h3>
        </div>
        <div class="panel-body">
            @authData@ @noAuth@
        </div>
    </div>


    <div class="panel panel-default">

        <div class="panel-heading">
            <h3 class="panel-title">Доставка, адрес получателя</h3>
        </div>
        <div class="panel-body">

            <div class="row">
                <div class="col-md-6">
                    <div class="radio">
                        @orderDelivery@
                    </div>  
                    <BR><BR>
                    @UserAdresList@

                </div>
                <div class="col-md-6">


                    @noAuthAdr@
                    <div id="userAdresData">
                    </div>
                    <br>
                    Дополнительная информация к заказу:
                    <textarea class="form-control" name="dop_info" id="dop_info"></textarea>

                </div>
            </div>

        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Способ оплаты</h3>
        </div>
        <div class="panel-body">

            <div class="radio">
                @orderOplata@
            </div>
            <br>
            <div id="showYurDataForPaymentLoad">
            </div>

        </div>
    </div>

    <p class="text-center">
        <input type="hidden" name="send_to_order" value="ok" >
        <input type="hidden" name="d" id="d" value="@deliveryId@">
        <input type="hidden" name="nav" value="done">
        <button type="reset" class="btn btn-default btn-lg visible-lg-inline"><span class="glyphicon glyphicon-remove"></span> Очистить</button> 
        <button type="submit" class="btn btn-success btn-lg orderCheckButton"><span class="glyphicon glyphicon-ok"></span> Оформить заказ</button>
    </p>
    <p><br><br></p>
</form>
@showYurDataForPayment@