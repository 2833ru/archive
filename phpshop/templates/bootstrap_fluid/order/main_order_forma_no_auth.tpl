<div class="row">
    <div class="col-md-4">
        <span style="color:red">@user_error@</span>
        <div class="form-group">
            <label>E-mail</label>
            <input type="email" name="mail" class="form-control req" placeholder="E-mail..." required="" value="@php echo $_POST['mail']; php@">
        </div>
        <div class="form-group">
            <label>Имя</label>
            <input type="text" name="name_new" class="form-control req"  placeholder="Имя..." required="" value="@php echo $_POST['name_new']; php@">
        </div>
    </div>
    <div class="col-md-8">
        <div class="alert alert-warning">
            <span class="glyphicon glyphicon-info-sign"></span> Если Вы - новый пользователь, то личный кабинет мы создадим за Вас и пришлём пароли на почту. Если Вы не авторизованы, мы узнаем Вас по емейлу и привяжем этот заказ к Вашему аккаунту.<br>
         <label><input type="checkbox" value="on" name="rule" class="req" checked="checked"> @rule@</label>
        </div>
    </div>
</div>