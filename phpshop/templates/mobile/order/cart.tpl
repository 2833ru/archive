<ul class="table-view">
    @display_cart@
    <li class="table-view-cell">Итого: @cart_num@ (шт.)  <strong>@cart_sum@</strong> @currency@</li>
    <li class="table-view-cell">Скидка: <strong>@discount@</strong> %</li>
    <li class="table-view-cell">Доставка: <strong id="delivery_price">@delivery_price@</strong> @currency@
        <a href="#modalDelivery" class="btn btn-primary" onclick="modal_on(this.hash)"><span class="icon icon-down-nav"></span> Выбрать</a></li>
    <li class="table-view-cell">Оплата: <strong id="payment_name"></strong>
        <a  class="btn btn-primary" href="#modalPayment" onclick="modal_on(this.hash)"><span class="icon icon-down-nav"></span> Выбрать</a></li>