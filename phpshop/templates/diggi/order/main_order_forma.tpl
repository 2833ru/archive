@checkLabelForOldTemplatesNoDelete@
@order_action_add@

<div class="page-header">
    <h2>Заказ №@orderNum@</h2>
</div>
<!-- Form Starts -->
<form class="form-horizontal" role="form" method="post" name="forma_order" id="forma_order" action="/done/">
    <div class="row">
    
        <!-- Shipping & Shipment Block Starts -->
            <div class="col-sm-12">
                <div class="panel panel-smart">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Личные данные
                        </h3>
                    </div>
                    <div class="panel-body">
                        <input type="hidden" name="ouid" value="@orderNum@" readonly="1">
                        <input type="hidden" value="@orderDate@"  readonly="1">
                        @authData@ 
                        @noAuth@
                    </div>
                </div>
                <!-- Taxes Block Starts -->
                <div class="panel panel-smart">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Выберите регион и метод доставки
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-md-12 radio">
                                @orderDelivery@      
                            </div>    
                        </div>
                        @UserAdresList@
                    </div>
                </div>
                <!-- Taxes Block Ends -->
                <!-- Shipment Information Block Starts -->
                <div class="panel panel-smart">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Укажите точный адрес получателя
                        </h3>
                    </div>
                    <div class="panel-body">
                        @noAuthAdr@
                        <div id="userAdresData">
                        </div>
                        <div class="form-group">
                            <label for="dop_info" class="col-sm-12 control-label">Дополнительная информация к заказу:</label>
                            <div class="col-sm-12">
                                <textarea class="form-control" name="dop_info" id="dop_info"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Shipment Information Block Ends -->
            </div>
        <!-- Shipping & Shipment Block Ends -->
        <!-- Discount & Conditions Blocks Starts -->
            <div class="col-sm-12">
                <div class="panel panel-smart">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Выберите способ оплаты
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-sm-12">
                                @orderOplata@
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div id="showYurDataForPaymentLoad"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- Discount & Conditions Blocks Ends -->
        <!-- Total Panel Starts -->
        <div class="col-sm-12">
            <div class="panel panel-smart">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Итого
                    </h3>
                </div>
                <div class="panel-body">
                    <dl class="dl-horizontal">
                        <dt>Количество товаров :</dt>
                        <dd>@cart_num@ шт.</dd>
                        <dt>Скидка :</dt>
                        <dd id="SkiSummaAll"><span id="SkiSumma">@discount@</span></dd>
                        <dt>Доставка :</dt>
                        <dd><span id="DosSumma">@delivery_price@</span> <span class="rubznak">@currency@</span> <span id="deliveryInfo"></span></dd>
                        
                    </dl>
                    <hr>
                    <dl class="dl-horizontal total">
                        <dt>Итого :</dt>
                        <dd><span id="WeightSumma" class="hidden">@cart_weight@</span><span id="TotalSumma">@total@</span> <span class="rubznak">@currency@</span></dd>
                    </dl>
                    <hr>
                    <div class="text-uppercase clearfix">
                        <input type="hidden" name="send_to_order" value="ok" >
                        <input type="hidden" name="d" id="d" value="@deliveryId@">
                        <input type="hidden" name="nav" value="done">
                        <button type="reset" class="btn btn-main pull-left">Очистить</button>
                        <button type="submit" class="btn btn-main pull-right orderCheckButton">Оформить заказ</button>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- Total Panel Ends -->
    </div>
</form>
<!-- Form Ends -->
@showYurDataForPayment@