<table class="table table-bordered">
    <tbody>
    <tr>
        <td>{Статус оплаты}</td>
        <td>@ozonrocket_payment_status@</td>
    </tr>
    <tr>
        <td>{Способ доставки}</td>
        <td>@ozonrocket_delivery_type@</td>
    </tr>
    <tr>
        <td>{Информация о доставке}</td>
        <td>@ozonrocket_delivery_info@</td>
    </tr>
    </tbody>
</table>
<div class="row" style="padding-bottom: 20px;">
    <div class="col-sm-12">
        <button type="button" class="btn btn-sm btn-primary ozonrocket-change-address">{Изменить}</button>
        <button type="button" class="btn btn-sm btn-success ozonrocket-send">{Отправить заказ}</button>
        <input type="hidden" name="ozonrocket_order_id" value="@ozonrocket_order_id@">
    </div>
</div>
@ozonrocket_popup@