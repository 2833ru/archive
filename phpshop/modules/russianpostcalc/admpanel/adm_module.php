<?php

PHPShopObj::loadClass("delivery");

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.russianpostcalc.russianpostcalc_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&id=' . $_GET['id']);
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();

    // Доставка
    $PHPShopDeliveryArray = new PHPShopDeliveryArray(array('is_folder'=>"!='1'"));

    $DeliveryArray = $PHPShopDeliveryArray->getArray();
    if (is_array($DeliveryArray))
        foreach ($DeliveryArray as $delivery) {

            // Длинные наименования
            if (strpos($delivery['city'], '.')) {
                $name = explode(".", $delivery['city']);
                $delivery['city'] = $name[0];
            }

            $delivery_value[] = array($delivery['city'], $delivery['id'], $data['delivery_id']);
        }

        // Тип отправления
    $type_value[] = array('Почта России 1 Класс', 1, $data['type']);
    $type_value[] = array('Ценная Посылка (обычная)', 2, $data['type']);

    $Tab1 = $PHPShopGUI->setField('API ключ', $PHPShopGUI->setInputText(false, 'key_new', $data['key'], 300));
    $Tab1.=$PHPShopGUI->setField('API пароль', $PHPShopGUI->setInput('password', 'password_new', $data['password'], null, 300));
    $Tab1.=$PHPShopGUI->setField('Индекс отправителя', $PHPShopGUI->setInputText(false, 'delivery_index_new', $data['delivery_index'], 300));
    $Tab1.=$PHPShopGUI->setField('Доставка', $PHPShopGUI->setSelect('delivery_id_new', $delivery_value, 300));
    $Tab1.=$PHPShopGUI->setField('Тип отправления', $PHPShopGUI->setSelect('type_new', $type_value, 300,true));
    $Tab1.= $PHPShopGUI->setField('Объявленная ценность', $PHPShopGUI->setInputText('От суммы корзины', 'cennost_new', $data['cennost'], 250,'%'));
    $Tab1.= $PHPShopGUI->setField('Добавить наценку', $PHPShopGUI->setInputText(null, 'fee_new', $data['fee'], 100));
    $Tab1.= $PHPShopGUI->setField('Тип наценки', $PHPShopGUI->setSelect('fee_type_new', array(array('%', 1, $data['fee_type']), array('Руб.', 2, $data['fee_type'])), 100, true, false, $search = false, false, $size = 1));

    $info = '<h4>Получение API ключа приложения</h4>
       <ol>
        <li>Зарегистрироваться в <a href="http://russianpostcalc.ru" target="_blank">Russianpostcalc.ru</a>.
        <li>Перейти по ссылке  <a target="_blank" href="http://russianpostcalc.ru/user/myaddr/api/">Настройка API</a>.
        <li>"API ключ" и "API пароль" скопировать в одноименные поля настроек модуля.
        </ol>
        
      <h4>Настройка модуля</h4>
 <ol>
        <li>Указать индекс отправителя отправлений магазина.
        <li>Выбрать тип отправления "Почта России 1 Класс" или "Ценная Посылка обычное отправление".
        <li>Указать формулу расчета объявленной ценности 0 - 100% от суммы корзины
        <li>Выбрать имя доставки для активации модуля.
        </ol>
';

    $Tab2 = $PHPShopGUI->setInfo($info);

    // Форма регистрации
    $Tab3 = $PHPShopGUI->setPay($serial = false, false, $data['version'], true);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true), array("Инструкция", $Tab2), array("О Модуле", $Tab3));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>