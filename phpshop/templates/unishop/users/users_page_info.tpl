<div id="allspec">
    @user_error@
</div>
<p><br></p>
<form name="users_password" method="post" class="form-horizontal" role="form">

    <div class="form-group">
        <label class="col-xs-12 col-sm-2 control-label">{Статус}</label>
        <div class="col-xs-4">
            <span class="btn btn-default"><span class="glyphicon glyphicon-user"></span> @user_status@</span>
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-2 control-label">{Скидка}</label>
        <div class="col-xs-4">
            <span class="btn btn-warning">@user_cumulative_discount@ %</span>
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-2 control-label">Email</label>
        <div class="col-xs-12 col-sm-4">
            <input type="email" class="form-control input-width-fix" value="@user_login@" required="" disabled>
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-2 control-label">{Рассылка}</label>
        <div class="checkbox col-xs-12 col-sm-6">
            <label>
                <input type="checkbox" name="sendmail_new" value="1" @user_sendmail_checked@> {Отказаться от новостных рассылок}
            </label>
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-2 control-label">{Пароль}</label>
        <div class="col-xs-4">
            <input type="password" class="form-control input-width-fix" name="password_new" value="@user_password@" required="">
        </div>
    </div>

    <div class="form-group" id="password_repeat" class="hidden" style="display: none;">
        <label class="col-xs-12 col-sm-2 control-label">{Повторите пароль}</label>
        <div class="col-xs-4">
            <input type="password" class="form-control input-width-fix" name="password_new2" required="">
        </div>
    </div>

    <div class="form-group">
        <label class="col-xs-12 col-sm-2 control-label"></label>
        <div class="col-xs-4">
            <input type="hidden" value="1" name="update_password">
            <button type="submit" onclick="$('#password_repeat').slideToggle();" class="btn btn-primary">{Сохранить изменение}</button>

        </div>
    </div>
</form>
