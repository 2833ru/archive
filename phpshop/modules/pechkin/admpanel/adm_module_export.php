<?
$_classPath="../../../";
include($_classPath."class/obj.class.php");
include("../class/PHPechkin.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("string");


$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
include($_classPath."admpanel/enter_to_admin.php");


// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    //if(empty($_POST['enabled_new'])) $_POST['enabled_new']=0;
    //$action = $PHPShopOrm->update($_POST);
    return $action;
}


// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm;

    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Экспорт адресов";
    $PHPShopGUI->size="500,590";

    //Подключаем JS дополнительно
    $PHPShopGUI->addJSFiles('../js/jquery-1.7.1.min.js','../js/pechkin-admin.js');
    $PHPShopGUI->addCSSFiles('../css/pechkin-admin.css');

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Экспорт адресов 'Печкин'","Экспорт адресов","../img/email_go.png");

    //Цикл группы пользователей (статусы)
    $select = 'SELECT * FROM `phpshop_shopusers_status`';
    $query = mysql_query($select);
    $status = mysql_fetch_array($query);
    do {
        $status_html .= $PHPShopGUI->setInput("checkbox", "status_new", $status['id'], false, false, false, "status_new", false, false, $status['name']);
    }
    while ($status = mysql_fetch_array($query));


    // Содержание закладки 1
    if($_SESSION['pechkinLogin']=='') {
        $Tab1=$PHPShopGUI->setField('Внимание!', 
            'Вы <b><u>не вошли</u></b> в систему Печкин как пользователь. Необходимо произвести авторизацию в Настройках модуля для экспорта адресов в адресные базы.<br><br>'
            , false, false, '10');
    }

    if($_SESSION['pechkinLogin']!='') {
        $Tab1.=$PHPShopGUI->setField('Статус пользователей для переноса', 
            $PHPShopGUI->setInput("checkbox", "status_new", '0', false, false, false, "status_new", false, false, 'Без статуса') .
            $status_html
            , false, false, '10');

        //Набор данных если есть авторизация
        if(isset($_SESSION['pechkinLogin'])) {
            $PHPechkin = new PHPechkin();
            $PHPechkin->__construct($_SESSION['pechkinLogin'],$_SESSION['pechkinPass']);
            $lists_get = $PHPechkin->lists_get();

            if($lists_get['row']['id']!='') {
                $lists_get_new[0] = $lists_get['row'];
            }
            else {
                $lists_get_new = $lists_get['row'];
            }

            if(isset($lists_get_new)) {
                foreach ($lists_get_new as $value) {
                    $name_base[$value['id']][0] = PHPShopString::utf8_win1251($value['name']);
                    $name_base[$value['id']][1] = PHPShopString::utf8_win1251($value['id']);
                }
            }
        }

        //Доп. параметры
        $value_merge_option[] = array('- не передавать параметр',0);
        $value_merge_option[] = array('Дата регистрации','datas');
        $value_merge_option[] = array('ФИО','name');
        //Доп. параметры из массива
        $value_merge_option[] = array('Телефон','tel_new');
        $value_merge_option[] = array('Страна','country_new');
        $value_merge_option[] = array('Регион','state_new');
        $value_merge_option[] = array('Город','city_new');
        $value_merge_option[] = array('Индекс','index_new');
        $value_merge_option[] = array('Улица','street_new');
        $value_merge_option[] = array('Дом','house_new_new');
        $value_merge_option[] = array('Подъезд','porch_new_new');
        $value_merge_option[] = array('Квартира','flat_new');


        $Tab1.=$PHPShopGUI->setField('Выберите данные для переноса в <u>pechkin-mail.ru</u>', 
                'Дополнительное поле 1 '.$PHPShopGUI->setSelect('merge1_new',$value_merge_option,200) . '<br>' .
                'Дополнительное поле 2 '.$PHPShopGUI->setSelect('merge2_new',$value_merge_option,200) . '<br>' .
                'Дополнительное поле 3 '.$PHPShopGUI->setSelect('merge3_new',$value_merge_option,200) . '<br>' .
                'Дополнительное поле 4 '.$PHPShopGUI->setSelect('merge4_new',$value_merge_option,200) . '<br>' .
                'Дополнительное поле 5 '.$PHPShopGUI->setSelect('merge5_new',$value_merge_option,200) . '<br>' .
                '<p>'.$PHPShopGUI->setImage('../../../admpanel/icon/icon_info.gif', 16, 16, false, false, 'margin-top:-1px; vertical-align:middle;') .
                            __('Добавить дополнительные поля можно по ссылке <a href="https://web.pechkin-mail.ru/?page=lists">https://web.pechkin-mail.ru/?page=lists</a></p>')
        ,false,false,'10' );
    
    
        $Tab1.=$PHPShopGUI->setField('Адресные базы в <u>pechkin-mail.ru</u>', 
                'Адресная база '.$PHPShopGUI->setSelect('adress_base_new',$name_base,200) . '<br>' .
                '<p>'.$PHPShopGUI->setImage('../../../admpanel/icon/icon_info.gif', 16, 16, false, false, 'margin-top:-1px; vertical-align:middle;') .
                            __('Добавить списки рассылки можно по ссылке <a href="https://web.pechkin-mail.ru/?page=lists">https://web.pechkin-mail.ru/?page=lists</a></p>') .
                $PHPShopGUI->setInput("checkbox", "autoload_new", '0', false, false, false, "autoload_new", false, false, 'Выгружать автоматически') .
                $PHPShopGUI->setInput("button","","Перенести","left",100,"importShopUsers()","but",false, false, '<span id="text_status"></span> <img src="../img/zoomloader.gif" id="loader_auth">')
        ,false,false,'10' );
    }

    //Цикл группы пользователей (статусы)
    $select = 'SELECT * FROM `phpshop_shopusers_status`';
    $query = mysql_query($select);
    $status = mysql_fetch_array($query);
    do {
        $status_html_subscribe .= $PHPShopGUI->setInput("checkbox", "status_subscribe_new", $status['id'], false, false, false, "status_new", false, false, $status['name']);
    }
    while ($status = mysql_fetch_array($query));

    if($_SESSION['pechkinLogin']=='') {
        $Tab2=$PHPShopGUI->setField('Внимание!', 
            'Вы <b><u>не вошли</u></b> в систему Печкин как пользователь. Необходимо произвести авторизацию в Настройках модуля для экспорта адресов в адресные базы.<br><br>'
            , false, false, '10');
    }
    else {
        // Содержание закладки 2
        $Tab2.=$PHPShopGUI->setField('Статус пользователей для переноса', 
            $PHPShopGUI->setInput("checkbox", "status_subscribe_new", '0', false, false, false, "status_subscribe_new", false, false, 'Без статуса') .
            $status_html_subscribe
            , false, false, '10');

        //Набор данных если есть авторизация
        if(isset($_SESSION['pechkinLogin'])) {
            $PHPechkin = new PHPechkin();
            $PHPechkin->__construct($_SESSION['pechkinLogin'],$_SESSION['pechkinPass']);
            $lists_get = $PHPechkin->lists_get();

            if($lists_get['row']['id']!='') {
                $lists_get_new[0] = $lists_get['row'];
            }
            else {
                $lists_get_new = $lists_get['row'];
            }

            if(isset($lists_get_new)) {
                foreach ($lists_get_new as $value) {
                    $name_base[$value['id']][0] = PHPShopString::utf8_win1251($value['name']);
                    $name_base[$value['id']][1] = PHPShopString::utf8_win1251($value['id']);
                }
            }
        }


        $Tab2.=$PHPShopGUI->setField('Выберите данные для переноса в <u>pechkin-mail.ru</u>', 
                'Дополнительное поле 1 '.$PHPShopGUI->setSelect('merge1_subscribe_new',$value_merge_option,200) . '<br>' .
                'Дополнительное поле 2 '.$PHPShopGUI->setSelect('merge2_subscribe_new',$value_merge_option,200) . '<br>' .
                'Дополнительное поле 3 '.$PHPShopGUI->setSelect('merge3_subscribe_new',$value_merge_option,200) . '<br>' .
                'Дополнительное поле 4 '.$PHPShopGUI->setSelect('merge4_subscribe_new',$value_merge_option,200) . '<br>' .
                'Дополнительное поле 5 '.$PHPShopGUI->setSelect('merge5_subscribe_new',$value_merge_option,200) . '<br>' .
                '<p>'.$PHPShopGUI->setImage('../../../admpanel/icon/icon_info.gif', 16, 16, false, false, 'margin-top:-1px; vertical-align:middle;') .
                            __('Добавить дополнительные поля можно по ссылке <a href="https://web.pechkin-mail.ru/?page=lists">https://web.pechkin-mail.ru/?page=lists</a></p>')
        ,false,false,'10' );
        
        $Tab2.=$PHPShopGUI->setField('Адресные базы в <u>pechkin-mail.ru</u>', 
                'Адресная база '.$PHPShopGUI->setSelect('adress_base_subscribe_new',$name_base,200) . '<br>' .
                '<p>'.$PHPShopGUI->setImage('../../../admpanel/icon/icon_info.gif', 16, 16, false, false, 'margin-top:-1px; vertical-align:middle;') .
                            __('Добавить списки рассылки можно по ссылке <a href="https://web.pechkin-mail.ru/?page=lists">https://web.pechkin-mail.ru/?page=lists</a></p>') .
                $PHPShopGUI->setInput("checkbox", "autoload2_new", '0', false, false, false, "autoload_new", false, false, 'Выгружать автоматически') .
                $PHPShopGUI->setInput("button","","Перенести","left",100,"importShopUsersSubscribe()","but",false, false, '<span id="text_status_subscribe"></span> <img src="../img/zoomloader.gif" id="loader_auth_subscribe">')
        ,false,false,'10' );

    }
    
    
    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Экспорт пользователей",$Tab1,490), array("Экспорт подписчиков",$Tab2,490) );

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=
            $PHPShopGUI->setInput("hidden","newsID",$id,"right",70,"","but").
            $PHPShopGUI->setInput("button","","Отмена","right",70,"return onCancel();","but").

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


