
<!-- Модальное окно returncall-->
<div class="modal hide fade" id="returnCallModal" style="width:auto !important;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>@leftMenuName@</h3>
    </div>
    <form role="form" method="post" name="user_forma" action="@ShopDir@/returncall/">
    <div class="modal-body">
        
            <div class="form-group">
                <label>Имя</label>
                <input type="text" name="returncall_mod_name" class="form-control input-xlarge" placeholder="Имя..." required="">
            </div>
            <div class="form-group">
                <label>Телефон</label>
                <input type="text" name="returncall_mod_tel" class="form-control input-xlarge" placeholder="Телефон..." required="">
            </div>
            <div class="form-group">
                <label>Время звонка:</label>
                <input class="form-control input-xlarge" type="text" name="returncall_mod_time_start">
            </div>
            <div class="form-group">
                <label>Сообщение</label>
                <textarea class="form-control input-xlarge" name="returncall_mod_message" style="height:30px"></textarea>
            </div>
            @returncall_captcha@
        
    </div>
    <div class="modal-footer">
        <input type="hidden" name="returncall_mod_send" value="1">
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        <button type="submit" class="btn btn-primary">Заказать звонок</button>
    </div>
    </form>
</div>

<a class="btn btn-default btn-sm" href="#returnCallModal" data-toggle="modal"><i class="icon-headphones"></i> Заказать обратный звонок</a>