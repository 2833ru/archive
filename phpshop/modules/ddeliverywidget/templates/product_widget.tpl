<script src="https://ddelivery.ru/front/widget-card/public/api.js"></script>
<script src="phpshop/modules/ddeliverywidget/js/ddeliveryprodwidget.js"></script>
<div class="pull-right hidden-xs zn-delivery">
    <div>
        <button class="btn btn-cart" id="cartDelivery" role="button" >Стоимость доставки</button>
    </div>
</div>
<!-- Модальное окно ddeliverywidget -->
<div class="modal fade bs-example-modal" id="ddeliverywidgetModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Доставка</h4>
            </div>
            <div class="modal-body" style="width:100%">
                 <div id="dd-widget-card"></div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="ddelivery-close">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<script>
    $("#cartDelivery").on("click", function () {
        ddeliveryprodwidgetStart();
    })
</script>
<!--/ Модальное окно ddeliverywidget -->