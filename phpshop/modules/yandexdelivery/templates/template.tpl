<div class="modal fade bs-example-modal" id="yandexdeliveryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">{Доставка}</h4>
            </div>
            <div class="modal-body" style="width:100%;">
                <div id="phpshopYaDeliveryWidget" style="height: 600px"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="yandexdelivery-close">{Закрыть}</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="yandex_delivery_order_uid" value="@yandexdelivery_order_uid@">
<input type="hidden" name="yandex_delivery_api" value="@yandexdelivery_api_key@">
<input type="hidden" name="yandex_delivery_sender_id" value="@yandexdelivery_sender_id@">
<input type="hidden" name="yandex_delivery_warehouse_id" value="@yandexdelivery_warehouse_id@">
<input type="hidden" name="yandex_delivery_cart" value='@yandexdelivery_cart@'>
<script type="text/javascript" src="phpshop/modules/yandexdelivery/templates/script.js?v=1.0"></script>
<script async src="https://widgets.delivery.yandex.ru/script/api"></script>