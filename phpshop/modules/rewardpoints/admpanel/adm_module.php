<?php
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
$PHPShopModules = new PHPShopModules($_classPath."modules/","rewardpoints");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.rewardpoints.rewardpoints_system"));



// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->update($_POST);

    //Обновляем валюты
    foreach ($_POST as $key=>$val) {
        $arname = explode('_', $key);
        if($arname[0]=='valuta') {
            //запрос на изменение валюты
            mysql_query("UPDATE `phpshop_valuta` SET `price_point` =  '".$val."' WHERE `id` =".$arname[1]);
        }
    }

    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI,$_classPath,$PHPShopOrm;

    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Настройка модуля";
    $PHPShopGUI->size="500,450";

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Бонусные баллы'","Настройки",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

    //Системные настройки
    $data = $PHPShopOrm->select();
    @extract($data);

    //Запрос валют
    $PHPShopOrmValuta = new PHPShopOrm($GLOBALS['SysValue']['base']['currency']);
    $data_currency = $PHPShopOrmValuta->select(array('*'));

    //Составим input для валют
    if(isset($data_currency)):
        foreach ($data_currency as $value) {
            $html_valuta .= $PHPShopGUI->setInputText('1 балл = ', 'valuta_'.$value['id'], $value['price_point'], 70, $value['code']);
        }
    endif;

    $Tab1 = $PHPShopGUI->setLine() . $PHPShopGUI->setField('Цена за 1 балл:', 
        $html_valuta . 
        $PHPShopGUI->setLine(false, 10) .
        $PHPShopGUI->setImage('../../../admpanel/icon/icon_info.gif', 16, 16) .
            __('<i>Например: 1балл = 100 руб</i>'), 'left', 0, 0, array('width' => '98%'));

    $Tab1_2 .= $PHPShopGUI->setLine() . $PHPShopGUI->setField('Процент для начисления:', 
        $PHPShopGUI->setInputText('Процент начисления ', 'percent_add_new', $data['percent_add'], 40, '%') . 
        $PHPShopGUI->setLine(false, 10) .
        $PHPShopGUI->setImage('../../../admpanel/icon/icon_info.gif', 16, 16) .
            __('<i>Например: 10% от стоимости товара (если для товаров указано определенно кол-во баллов, то настройка действовать не будет)</i>'), 'left', 0, 0, array('width' => '98%'));

    //Варианты покупки баллами
    $selectBuy[ $data['percent'] ] = 'selected';
    for($u=1;$u<=100;$u++): 
        $vaBuy[] = array($u.'% покупки', $u, $selectBuy[$u]);
    endfor;

    $Tab1_2 .= $PHPShopGUI->setLine() . $PHPShopGUI->setField('Покупка баллами:', 
        $PHPShopGUI->setSelect('percent_new', $vaBuy, '120px') .
        $PHPShopGUI->setLine(false, 10) .
        $PHPShopGUI->setImage('../../../admpanel/icon/icon_info.gif', 16, 16) .
            __('<i>Например при выборе 50% означает, что пользователь сможет купить баллами только половину своей покупки</i>')
    , 'left', 0, 0, array('width' => '98%'));

    $Tab1_3 .= $PHPShopGUI->setLine() . $PHPShopGUI->setField('Сроки:', 
        $PHPShopGUI->setInputText('Срок хранения баллов (Количество дней) ', 'days_new', $data['days'], 40, 'дн.') . 
        $PHPShopGUI->setInputText('Срок информации о списание баллов (Количество дней) ', 'daysInterval_new', $data['daysInterval'], 40, 'дн.') . 
        $PHPShopGUI->setLine(false, 10) .
        $PHPShopGUI->setImage('../../../admpanel/icon/icon_info.gif', 16, 16) .
            __('<i>Например: 10 дней. <b>Важно!</b> - срок информации о списание не может быть больше срока хранения баллов</i>')

        , 'left', 0, 0, array('width' => '98%'));



    //Запрос на статусы
    $PHPShopOrmValuta = new PHPShopOrm($GLOBALS['SysValue']['base']['order_status']);
    $data_order_status = $PHPShopOrmValuta->select(array('*'));
    foreach ($data_order_status as $order_status) {
        if($data['status_order']==$order_status['id'])
            $order_status_check = 'selected';
        else
            $order_status_check = '';

        if($data['status_order_null']==$order_status['id'])
            $order_null_status_check = 'selected';
        else
            $order_null_status_check = '';

        $vaOrderStatus[] = array($order_status['name'], $order_status['id'], $order_status_check);
        $vaOrderStatusNull[] = array($order_status['name'], $order_status['id'], $order_null_status_check);
    }


    $Tab1_4 .= $PHPShopGUI->setLine() . $PHPShopGUI->setField('Статусы:', 
        $PHPShopGUI->setSelect('status_order_new', $vaOrderStatus, '120px', false, 'Статус заказа на подтверждение баллов') .
        $PHPShopGUI->setLine(false, 10) .
        $PHPShopGUI->setSelect('status_order_null_new', $vaOrderStatusNull, '120px',false, 'Статус заказа на аннулирование начисленных баллов:') .
        $PHPShopGUI->setLine(false, 10) .
        $PHPShopGUI->setImage('../../../admpanel/icon/icon_info.gif', 16, 16) .
            __('<i>Например для подтверждения статус "Выполнен", а для списания статус "Аннулирован"</i>')

    , 'left', 0, 0, array('width' => '98%'));

    


    $Info = '
    <p>Путь до шаблона <b>phpshop/templates/имя шаблона/</b></p>
    <p>1. Шаблон <b>/users/users_page_info.tpl</b> (Личный кабинет)<br>
    <ul>
        <li><b>@pointsBalance@</b> - Баланс баллов</li>
        <li><b>@minTd@</b> - Таблица транзакций маленькая (превью)</li>
        <li><b>@maxTd@</b> - Таблица транзакций полная</li>
        <li><b>@pointsWriteOff@</b> - Информация о списаниях</li>
    </ul>
    </p>
    <p>2. Шаблон <b>/users/users_forma_enter.tpl</b> (Навигация пользователя в шапке)<br>
    <ul>
        <li><b>@pointsBalance@</b> - Баланс баллов</li>
    </ul>
    </p>
    <p>3. Шаблон <b>/product/main_product_forma_full.tpl</b> (Карточка товара)<br>
    <ul>
        <li><b>@pointsAccrued@</b> - Кол-во начисленных бонусов за товар</li>
    </ul>
    </p>
    <p>4. Необходимо скопировать в папку <b>/order/</b> вашего шаблона два файла из папки с модулем <b>/phpshop/modules/rewardpoints/templates/order/</b><br>
    <ul>
        <li>1). <b>cart.tpl</b> - корзина</li>
        <li>2). <b>product.tpl</b> - товары в корзине</li>
    </ul>
    </p>';

    // Содержание закладки 3
    $Tab3=$PHPShopGUI->setInfo($Info, 300, '95%');

    // Содержание закладки 4
    $Tab4=$PHPShopGUI->setPay($data['serial'],true);

    // Содержание закладки 5
    $Info = '
    <p><b>Инструкция по настройке автоматического списания бонусов по истечению срока.</b></p>
    <p>1. Включить модуль <b>Cron</b></p>
    <p>2. Перейти в раздел <b>Модули > Задачи > Настройка Cron</b></p>
    <p>3. Создать новую запись, где прописать в поле "Запускаемый Файл" строку <b>phpshop/modules/rewardpoints/cron/pointswriteoff.php</b> для авто списания баллов<br>Там же возможно задать количество запусков в день.</p>
    <p>4. Создать новую запись, где прописать в поле "Запускаемый Файл" строку <b>phpshop/modules/rewardpoints/cron/mailpointswriteoff.php</b> для рассылки писем о предостоящих списаниях<br>Там же возможно задать количество запусков в день.</p>
    <p>5. В файлах <b>phpshop/modules/rewardpoints/cron/mailpointswriteoff.php</b> и <b>phpshop/modules/rewardpoints/cron/pointswriteoff.php</b> в первых строках кода поставить <b>true</b> вместо false <p>';

    $Tab5=$PHPShopGUI->setInfo($Info, 300, '95%');

    // Содержание закладки 6
    //$Tab6=$PHPShopGUI->setInfo("Шабл", 250, '95%');

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Курс",$Tab1,350), array("Покупка/Начисление",$Tab1_2,350), array("Сроки",$Tab1_3,350), array("Статусы",$Tab1_4,350), array("Шаблон",$Tab3,350), array("Авто-списание",$Tab5,350), array("О Модуле",$Tab4,350));

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


