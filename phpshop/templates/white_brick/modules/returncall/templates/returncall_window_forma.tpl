
<!-- Модальное окно returncall-->
<div class="modal hide fade" id="returnCallModal" style="width:auto !important;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>@leftMenuName@</h3>
    </div>
    <form role="form" method="post" class="form-horizontal" name="user_forma" action="@ShopDir@/returncall/">
        <div class="modal-body">

            <div class="control-group">
                <label class="control-label">Имя:</label>
                <div class="controls">
                    <input type="text" name="returncall_mod_name" class="span3" placeholder="Имя..." required="">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Телефон:</label>
                <div class="controls">
                    <input type="text" name="returncall_mod_tel" class="form-control input-xlarge" placeholder="Телефон..." required="">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Время звонка:</label>
                <div class="controls">
                    <input class="form-control input-xlarge" type="text" name="returncall_mod_time_start">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Сообщение:</label>
                <div class="controls">
                    <textarea class="form-control input-xlarge" name="returncall_mod_message"></textarea>
                </div>
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

<a class="btn btn-default btn-sm" href="#returnCallModal" data-toggle="modal"><i class="icon-headphones"></i> Обратный звонок</a>