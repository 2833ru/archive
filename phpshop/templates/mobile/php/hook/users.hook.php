<?php

function action_register_mob_hook($obj, $row, $rout) {
    $disp = '
        
<form class="input-group" name="users_data" method="post">
  <div class="input-row">
    <label>Логин:</label>
    <input type="text" placeholder="login" name="login_new" value="' . @$_POST['login_new'] . '">
  </div>
  <div class="input-row">
    <label>Пароль:</label>
    <input type="password" name="password_new" value="' . @$_POST['password_new'] . '" placeholder="*******">
  </div>
   <div class="input-row">
    <label>Пароль: </label>
    <input type="password" name="password_new2" placeholder="Пароль еще раз" value="' . @$_POST['password_new2'] . '" >
  </div>
  <div class="input-row">
    <label>ФИО :</label>
    <input type="text" name="name_new" placeholder="Иванов Денис" value="' . @$_POST['name_new'] . '" >
  </div>
  <div class="input-row">
    <label>E-mail :</label>
    <input type="email" name="mail_new" placeholder="username@mail.ru" value="' . @$_POST['mail_new'] . '" >
  </div>
  <div class="input-row">
    <label>Телефон :</label>
    <input type="text" name="tel_new" placeholder="495 856-11-90" value="' . @$_POST['tel_new'] . '">
  </div>
   <div class="input-row">
    <label>Адрес:</label>
    <input type="text" name="adres_new" placeholder="Рязанский проспект, д. 24" value="' . @$_POST['adres_new'] . '">
  </div>
   <div class="input-row">
    <label><a href="#setCaptcha" onclick="modal_on(this.hash)"><img src="phpshop/captcha.php" id="captcha" alt="" border="0" height="20"></a> 
</label>
    
    <input type="text" name="key" placeholder="12345" value="' . @$_POST['key'] . '">
    <input type="hidden" value="1" name="add_user"> 
  </div>
  <div style="padding-bottom:50px;margin:10px">
 <button class="btn btn-positive btn-block"><span class="icon icon-plus"></span> Регистрация</button>
 </div>
</form>';

    $obj->set('formaTitle', 'Новый пользователь');
    $obj->set('formaContent', ' ');
    $obj->set('formaContentRegister', $disp);
}

function user_info_mob_hook($obj, $row, $rout) {

    if ($rout == 'END') {
        $disp = '
   
                            <li class="table-view-divider">Логин</li>
                            <li class="table-view-cell">' . $obj->get('UsersLogin') . '</li>
                            <li class="table-view-divider">Статус</li>
                            <li class="table-view-cell">' . $obj->get('UsersStatusName') . '</li>
                            <li class="table-view-divider">Скидка</li>
                            <li class="table-view-cell">' . $obj->get('UsersStatusDiscount') . '</li>
                        ';

        $obj->set('formaContent', $disp);
        return true;
    }
}

function error_mob_hook($obj, $row) {
    $user_error = null;
    if (is_array($obj->error))
        foreach ($obj->error as $val)
            $user_error.='<button class="btn btn-negative btn-block btn-outlined"><span class="icon icon-info"></span> '.$val.'</button>';

    $obj->set('user_error', $user_error);
    return true;
}

$addHandler = array
    (
    'action_register' => 'action_register_mob_hook',
    'user_info' => 'user_info_mob_hook',
    'error' => 'error_mob_hook'
);
?>
