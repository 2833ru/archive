<div id="modalInfo" class="modal">
    <header class="bar bar-nav">
        <a class="icon icon-close pull-right" href="#modalInfo" onclick="modal_off(this.hash)"></a>
        <h1 class="title">Покупатель:</h1>
    </header>
    <div class="content">
        <p class="content-padded">
        <form method="post" name="forma_order" action="/done/">
            <input type="email" placeholder="E-mail" name="mail" required value="@UserMail@">
            <input type="text" placeholder="ФИО" name="name_new" required value="@UserName@">
            <input type="text" placeholder="Телефон" name="tel_new" required value="@UserTel@">
            <textarea rows="5" name="street_new" placeholder="Адрес доставки">@UserAdres@</textarea>
            <input type="hidden" name="d" id="d" value="@delivery_id@">
            <input type="hidden" name="ouid" id="ouid" value="@orderNum@">
            <input type="hidden" name="order_metod" id="order_metod">
            <input type="hidden" name="send_to_order" id="send_to_order" value="send_to_order">
            <button class="btn btn-positive btn-block">Оформить заказ</button>
        </form>
        </p>
    </div>
</div>
<div id="modalDelivery" class="modal">
    <header class="bar bar-nav">
        <a class="icon icon-close pull-right" href="#modalDelivery" onclick="modal_off(this.hash)"></a>
        <h1 class="title">Выбор доставки</h1>
    </header>
    <div class="content">
        <p class="content-padded"><ul class="table-view">@delivery@</ul></p>
    </div>
</div> 
<div id="modalPayment" class="modal">
    <header class="bar bar-nav">
        <a class="icon icon-close pull-right" href="#modalPayment" onclick="modal_off(this.hash)"></a>
        <h1 class="title">Выбор оплаты</h1>
    </header>
    <div class="content">
        <p class="content-padded"><ul class="table-view">@payment@</ul></p>
    </div>
</div>