<?
$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("file");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
include($_classPath."admpanel/enter_to_admin.php");


// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.pbrf.pbrf_system"));


// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $_POST['data_new'] = serialize($_POST['data']);

    $action = $PHPShopOrm->update($_POST);
    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm,$PHPShopModules,$GLOBALS;

    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Настройка модуля";
    $PHPShopGUI->size="500,450";

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Печатные бланки pbrf.ru'","Настройки",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

    //Системные настройки
    $data = $PHPShopOrm->select();
    //@extract($data);

    $data_person = unserialize($data['data']);

    $Tab1 .= $PHPShopGUI->setLine() . $PHPShopGUI->setField('Ключ API:', 
        $PHPShopGUI->setInputText(false, 'key_new', $data['key'], 220) . 
        $PHPShopGUI->setLine(false, 10) .
        $PHPShopGUI->setImage('../../../admpanel/icon/icon_info.gif', 16, 16) .
            __('<i>Например: <b>11234536951ecd6ab1e9d18e9f3b5088</b></i>'), 'left', 0, 0, array('width' => '98%'));

    $Tab1 .= $PHPShopGUI->setLine() . $PHPShopGUI->setField('Ваши данные для печати:', 
        $PHPShopGUI->setInputText('Фамилиия&nbsp;&nbsp; ', 'data[surname]', $data_person['surname'], 120, false , 'left') . 
        $PHPShopGUI->setInputText('Имя&nbsp;&nbsp; ', 'data[name]', $data_person['name'], 120, false , 'left') . 
        $PHPShopGUI->setInputText('Отчество&nbsp;&nbsp; ', 'data[name2]', $data_person['name2'], 120) . 
        $PHPShopGUI->setInputText('Страна&nbsp;&nbsp; ', 'data[country]', $data_person['country'], 125 , false , 'left') . 
        $PHPShopGUI->setInputText('Область, Район', 'data[region]', $data_person['region'], 127, false , 'left') . 
        $PHPShopGUI->setInputText('Город&nbsp;&nbsp; ', 'data[city]', $data_person['city'], 127) . 
        $PHPShopGUI->setInputText('Улица&nbsp;&nbsp; ', 'data[street]', $data_person['street'], 120 , false , 'left') . 
        $PHPShopGUI->setInputText('Дом&nbsp;&nbsp; ', 'data[build]', $data_person['build'], 40 , false , 'left') . 
        $PHPShopGUI->setInputText('Квартира&nbsp;&nbsp; ', 'data[appartment]', $data_person['appartment'], 40) . 
        $PHPShopGUI->setInputText('Почтовый индекс&nbsp;&nbsp; ', 'data[zip]', $data_person['zip'], 120) .
        $PHPShopGUI->setInputText('Телефон для sms&nbsp;&nbsp; +7', 'data[tel]', $data_person['tel'], 108)
    , 'left', 0, 0, array('width' => '98%'));

    $Tab1 .= $PHPShopGUI->setLine() . $PHPShopGUI->setField('Предъявленный документ:', 
        $PHPShopGUI->setInputText('Наименование документа&nbsp;&nbsp; ', 'data[document]', $data_person['document'], 120) . 
        $PHPShopGUI->setInputText('Серия&nbsp;&nbsp; ', 'data[document_serial]', $data_person['document_serial'], 70, false , 'left') . 
        $PHPShopGUI->setInputText('№&nbsp;&nbsp; ', 'data[document_number]', $data_person['document_number'], 115) . 
        $PHPShopGUI->setInputText('Выдан&nbsp;&nbsp; ', 'data[document_day]', $data_person['document_day'], 70 , false , 'left') . 
        $PHPShopGUI->setInputText('20&nbsp;&nbsp; ', 'data[document_year]', $data_person['document_year'], 100,'г.') . 
        $PHPShopGUI->setInputText('Наименование учреждения выдающего документ&nbsp;&nbsp; ', 'data[document_issued_by]', $data_person['document_issued_by'], 220 , false , 'left')
    , 'left', 0, 0, array('width' => '98%'));


    // Содержание закладки 3
    $Info = '<div style="font-size:12px;"><b><u>Инструкция сервиса pbrf.ru</u></b>
    <p><b>Для получения ключа необходимо:</b>
    <ul>
        <li>Зарегистироваться на <a target="_blank" href="http://pbrf.ru/пользователь/войти">pbrf.ru</a></li>
        <li>Авторизоваться на сервисе</li>
        <li>Получить ключ доступа в личном кабинете <i>(вкладка API)</i></li>
        <li>Ввести этот ключ в поле "Ключ API" на вкладке "Настройки" в этом окне</li>
    </ul>
    </p>
    <p><b>Важно!</b> - Название домена при создание ключа необходимо указывать первого уровня, даже если магазин работает у вас на субдомене.</p>
    <p><b>Работать с API сервиса pbrf.ru доступно только на некоторых платных тарифах. Подробнее <a target="_blank" href="http://pbrf.ru/тарифы/выбрать-тариф">смотреть</a> на сайте компании.</b></p></div>';
    $Tab3=$PHPShopGUI->setInfo($Info, 250, '95%');

    // Содержание закладки 4
    $Tab4=$PHPShopGUI->setPay('О модуле',false);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Настройка",$Tab1,430), array("Инструкция",$Tab3,430), array("О Модуле",$Tab4,430));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=
            $PHPShopGUI->setInput("hidden","newsID",$id,"right",70,"","but").
            $PHPShopGUI->setInput("button","","Отмена","right",70,"return onCancel();","but").
            $PHPShopGUI->setInput("submit","editID","ОК","right",70,"","but","actionUpdate");

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


