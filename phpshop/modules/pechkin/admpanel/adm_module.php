<?
$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("orm");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
include($_classPath."admpanel/enter_to_admin.php");


// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

PHPShopObj::loadClass("system");
$PHPShopSystem = new PHPShopSystem();

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm;


    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Настройка модуля";
    $PHPShopGUI->size="500,450";

    //Почта администратора
    $adminmail = $PHPShopSystem->objRow['adminmail2'];

    //Подключаем JS дополнительно
    $PHPShopGUI->addJSFiles('../js/jquery-1.7.1.min.js','../js/pechkin-admin.js');
    $PHPShopGUI->addCSSFiles('../css/pechkin-admin.css');

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Печкин'","Настройки","../img/email_edit.png");

    if($_SESSION['pechkinLogin']!='') {
        $LoginPechkin = $_SESSION['pechkinLogin'];
        $cssPechkin = 'hidden';
        $cssPechkinTrue = 'visible';
        $cssPechkinReg = 'hidden';
    }
    else {
        $cssPechkin = 'visible';
        $cssPechkinTrue = 'hidden';
        $cssPechkinReg = 'visible';
    }


    // Содержание закладки 1
    $Tab1=$PHPShopGUI->setField('Авторизация на сервисе <u>Pechkin-Mail</u>',
        '<div class="auth-pechkin '.$cssPechkin.'">' .
        $PHPShopGUI->setInput("text", "login_p_new", $login_p, false, 140, false, false, false, 'Логин&nbsp;&nbsp;&nbsp;') .
        $PHPShopGUI->setInput("password", "pass_p_new", $pass_p, false, 140, false, false, false, 'Пароль&nbsp;') .
        $PHPShopGUI->setInput("button","auth","Войти",false,70,"authPechkin()","but","actionStart", false ,'<img src="../img/zoomloader.gif" id="loader_auth">') .
        '</div>' .
        '<div class="auth-pechkin-true '.$cssPechkinTrue.'"><img src="../../../admpanel/icon/information.gif" id="inf-pechkin"> Вы вошли как <b>'.$LoginPechkin.'</b><br>' .
        $PHPShopGUI->setInput("button","exit","Выход",false,70,"exitPechkin()","but","actionStart", false ,'<img src="../img/zoomloader.gif" id="loader_auth">') .
        '</div>'
    ,false,false,'10' );

    $Tab1.='<div class="reg-pechkin '.$cssPechkinReg.'">'.
        $PHPShopGUI->setField('Регистрация на сервисе <u>Pechkin-Mail</u>',
        $PHPShopGUI->setInput("button","auth","Регистрация",false,150,"miniWin(' https://web.pechkin-mail.ru/common/auth_new.php?registration&email=".$adminmail."&username=".$adminmail."&integration=phpshop', 970, 500);","but","actionStart") .
        $PHPShopGUI->setImage('../../../admpanel/icon/icon_info.gif', 16, 16, false, false, 'float:left; margin:4px 0 -1px 4px;vertical-align: bottom;padding-right: 3px;') .
                    __('<div class="reg">После нажатия, Вам на почту <b>'.$adminmail.'</b> будет отправлено<br> письмо с информацией по активации учетной записи.</div>')
    ,false,false,'10', array('margin-top' => '10px' ) );
    $Tab1.= '</div>';
    
    // Содержание закладки 2
    $Tab2=$PHPShopGUI->setPay('О модуле',false);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Авторизация",$Tab1,270), array("О модуле",$Tab2,270));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=
            $PHPShopGUI->setInput("hidden","newsID",$id,"right",70,"","but").
            $PHPShopGUI->setInput("button","","Закрыть","right",70,"return onCancel();","but");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

if($UserChek->statusPHPSHOP < 2) {

    // Вывод формы при старте
    $PHPShopGUI->setLoader($_POST['editID'],'actionStart');

    // Обработка событий
    $PHPShopGUI->getAction();

}else $UserChek->BadUserFormaWindow();

?>


