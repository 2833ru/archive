<?php

$_classPath = "../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();

PHPShopObj::loadClass("system");
$PHPShopSystem = new PHPShopSystem();

// Редактор GUI
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->title = "Редактирование Адреса";
$PHPShopGUI->ajax = "'menu','','','core'";
$PHPShopGUI->alax_lib = true;

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name27']);

// Модули
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $SysValue, $_classPath, $PHPShopOrm, $PHPShopModules;

    if ($_GET['adrId'] == "new")
        $adrId = "";
    else
        $adrId = intval($_GET['adrId']);
    // Выборка
    $data = $PHPShopOrm->select(array('data_adres', 'id'), array('id' => '=' . intval($_GET['id'])));
    $mass = unserialize($data['data_adres']);



    // ID окна для памяти закладок
    $PHPShopGUI->setID(__FILE__, $data['id']);

    $PHPShopGUI->dir = "../";
    //$PHPShopGUI->size = "630,530";
    // Графический заголовок окна
    $PHPShopGUI->setHeader("Редактирование Адреса Пользователя", "", $PHPShopGUI->dir . "img/i_select_another_account_med[1].gif");

    $adresData = array();
    if (is_array($mass['list'][$adrId]))
        $adresData = $mass['list'][$adrId];

    if ($mass['main'] == $adrId)
        $defaultChecked = "checked";
    else
        $defaultChecked = "";
    // Данные покупателя
    $Tab1 = $PHPShopGUI->setField(__("ФИО"), $PHPShopGUI->setInputText('', 'mass[fio_new]', $adresData['fio_new'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Телефон"), $PHPShopGUI->setInputText('', 'mass[tel_new]', $adresData['tel_new'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Страна"), $PHPShopGUI->setInputText('', 'mass[country_new]', $adresData['country_new'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Регион/штат"), $PHPShopGUI->setInputText('', 'mass[state_new]', $adresData['state_new'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Город"), $PHPShopGUI->setInputText('', 'mass[city_new]', $adresData['city_new'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Индекс"), $PHPShopGUI->setInputText('', 'mass[index_new]', $adresData['index_new'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Улица"), $PHPShopGUI->setInputText('', 'mass[street_new]', $adresData['street_new'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Дом"), $PHPShopGUI->setInputText('', 'mass[house_new]', $adresData['house_new_new'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Подъезд"), $PHPShopGUI->setInputText('', 'mass[porch_new]', $adresData['porch_new_new'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Код домофона"), $PHPShopGUI->setInputText('', 'mass[door_phone_new]', $adresData['door_phone_new'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Квартира"), $PHPShopGUI->setInputText('', 'mass[flat_new]', $adresData['flat_new'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Время доставки"), $PHPShopGUI->setInputText('', 'mass[delivtime_new]', $adresData['delivtime_new'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Данные по умолчанию"), $PHPShopGUI->setCheckbox('default', '1', 'установить как данные по умолчанию', $defaultChecked), 'left');

    // Юр. данные покупателя
    $Tab2 = $PHPShopGUI->setField(__("Наименование организации "), $PHPShopGUI->setInputText('', 'mass[org_name_new]', $adresData['org_name_new'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("ИНН "), $PHPShopGUI->setInputText('', 'mass[org_inn_new]', $adresData['org_inn_new'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("КПП"), $PHPShopGUI->setInputText('', 'mass[org_kpp_new]', $adresData['org_kpp_new'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Юридический адрес"), $PHPShopGUI->setInputText('', 'mass[org_yur_adres_new]', $adresData['org_yur_adres_new'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Фактический адрес"), $PHPShopGUI->setInputText('', 'mass[org_fakt_adres_new]', $adresData['org_fakt_adres_new'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Расчётный счёт"), $PHPShopGUI->setInputText('', 'mass[org_ras_new]', $adresData['org_ras_new'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Наименование банка"), $PHPShopGUI->setInputText('', 'mass[org_bank_new]', $adresData['org_bank_new'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Корреспондентский счёт"), $PHPShopGUI->setInputText('', 'mass[org_kor_new]', $adresData['org_kor_new'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("БИК"), $PHPShopGUI->setInputText('', 'mass[org_bik_new]', $adresData['org_bik_new'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Город"), $PHPShopGUI->setInputText('', 'mass[org_city_new]', $adresData['org_city_new'], '190', false, 'left'), 'left');


    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Данные пользователя", $Tab1, 300), array("Юр. данные пользователя", $Tab2, 300));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "newsID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("hidden", "adrId", $adrId, "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "return onDelete('" . __('Вы действительно хотите удалить?') . "')", "but", "actionDelete.shopusers.edit") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.shopusers.edit") .
            $PHPShopGUI->setLine();

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

/**
 * Экшен сохранения
 */
function actionSave() {
    global $PHPShopGUI;

    // Сохранение данных
    actionUpdate();

    $_GET['id'] = $_POST['newsID'];
    $PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;

    $adrId = $_POST['adrId'];
    // Выборка
    $PHPShopOrm->clean();
    $data = $PHPShopOrm->select(array('data_adres', 'id'), array('id' => '=' . intval($_POST['newsID'])));
    $mass = unserialize($data['data_adres']);
    if (is_array($mass['list'][$adrId]))
        $mass['list'][$adrId] = $_POST['mass'];
    else {
        $mass['list'][] = $_POST['mass'];
        // получаем Ид добавленного адреса
        end($mass['list']);         // move the internal pointer to the end of the array
        $adrId = key($mass['list']);
    }
    // Сохраняем адрес по умолчанию
    if (isset($_POST['default']) AND $_POST['default'] == 1)
        $mass['main'] = $adrId;
//        
    // Перехват модуля
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $_POST);


    $_POST['data_adres_new'] = serialize($mass);

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['newsID']));
    $PHPShopOrm->clean();
    echo"
<script>
    opener.location.reload(); 
    window.close(); 
</script>
	   ";
    return $action;
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    $adrId = $_POST['adrId'];
    // Выборка
    $PHPShopOrm->clean();
    $data = $PHPShopOrm->select(array('data_adres', 'id'), array('id' => '=' . intval($_POST['newsID'])));
    $mass = unserialize($data['data_adres']);
    if (isset($mass['list'][$adrId]))
        unset($mass['list'][$adrId]);
    // если удаляемый адрес - адр. по умлочанию, назначем последний 
    if ($mass['main'] == $adrId) {
        // получаем Ид добавленного адреса
        end($mass['list']);         // move the internal pointer to the end of the array
        $adrId = key($mass['list']);
        $mass['main'] = $adrId;
    }


    // Перехват модуля
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $_POST);


    $_POST['data_adres_new'] = serialize($mass);

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['newsID']));
    $PHPShopOrm->clean();
    echo"
<script>
    opener.location.reload(); 
    window.close(); 
</script>
	   ";
    return $action;
}

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');

// Обработка событий
$PHPShopGUI->getAction();
?>