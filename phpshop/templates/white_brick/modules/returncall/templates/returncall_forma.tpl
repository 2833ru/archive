<form role="form" method="post" name="user_forma" action="@ShopDir@/returncall/" class="returnCall">
    <div class="control-group">
                <label class="control-label">Имя:</label>
                <div class="controls">
                    <input type="text" name="returncall_mod_name" style="width:100%" placeholder="Имя..." required="">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Телефон:</label>
                <div class="controls">
                    <input type="text" name="returncall_mod_tel" style="width:100%" placeholder="Телефон..." required="">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Время звонка:</label>
                <div class="controls">
                    <input class="form-control input-xlarge" type="text" style="width:100%" name="returncall_mod_time_start">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Сообщение:</label>
                <div class="controls">
                    <textarea class="form-control input-xlarge" style="width:100%" name="returncall_mod_message"></textarea>
                </div>
            </div>
    @returncall_captcha@
    
    <div class="pull-center">
        <br>
        <input type="hidden" name="returncall_mod_send" value="1">
        <button type="submit" class="btn btn-default">Заказать звонок</button>
    </div>
    
</form>