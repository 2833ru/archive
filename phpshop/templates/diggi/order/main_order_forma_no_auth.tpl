<div class="form-group">
    <span style="color:red">@user_error@</span>
</div>
<div class="form-group">
    <label for="mail" class="col-sm-3 control-label">E-mail</label>
    <div class="col-sm-9">
        <input type="email" name="mail" class="form-control req" placeholder="E-mail..." required="" value="@php echo $_POST['mail']; php@">
    </div>
</div>
<div class="form-group">
    <label for="name_new" class="col-sm-3 control-label">Имя</label>
    <div class="col-sm-9">
        <input type="text" name="name_new" class="form-control req"  placeholder="Имя..." required="" value="@php echo $_POST['name_new']; php@"> 
    </div>
</div>
<div class="col-sm-12 no-p">
    <div class="alert alert-warning">
        <span class="glyphicon glyphicon-info-sign"></span> Если Вы - новый пользователь, то личный кабинет мы создадим за Вас и пришлём пароли на почту. Если Вы не авторизованы, мы узнаем Вас по емейлу и привяжем этот заказ к Вашему аккаунту.<br>
         <label><input type="checkbox" value="on" name="rule" class="req" checked="checked"> @rule@</label>
    </div>
</div>