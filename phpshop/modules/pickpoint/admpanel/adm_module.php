<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.pickpoint.pickpoint_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();

    if ($data['type_service'] == 'STD')
        $s0 = 'selected';
    else
        $s1 = 'selected';

    $type_service_value[] = array('STD - стандарт, доставка предоплаченного товара без приема оплаты за товар', 'STD', $s0);
    $type_service_value[] = array('STDCOD - доставка с приемом оплаты за товар, т.е. наложенный платеж', 'STDCOD', $s1);


    switch ($data['type_reception']) {
        case "CUR":
            $s2 = 'selected';
            break;
        case "WIN":
            $s3 = 'selected';
            break;
        case "APTCON":
            $s4 = 'selected';
            break;
        case "APT":
            $s5 = 'selected';
            break;
    }

    $type_reception_value[] = array('CUR – сбор отправлений курьером PickPoint', 'CUR', $s2);
    $type_reception_value[] = array('WIN – самостоятельный привоз отправлений в окно приема на сортировочный центр PickPoint', 'WIN', $s3);
    $type_reception_value[] = array('APTCON – сдача отправлений консолидировано в 1 ячейку в Постамате валом', 'APTCON', $s4);
    $type_reception_value[] = array('APT – самостоятельный развоз отправлений по Постаматам', 'APT', $s4);


    $Tab1 = $PHPShopGUI->setField('Имя доставка PickPoint', $PHPShopGUI->setInputText(false, 'city_new', $data['city']) . $PHPShopGUI->setHelp('Доставки должны быть созданы в базе и содержать ее имя.'));
    $Tab1.=$PHPShopGUI->setField('Текст ссылки', $PHPShopGUI->setInputText(false, 'name_new', $data['name'], 300));

    $Tab1.=$PHPShopGUI->setField('Титы услуг', $PHPShopGUI->setSelect('type_service_new', $type_service_value,400));
    $Tab1.=$PHPShopGUI->setField('Вид приема', $PHPShopGUI->setSelect('type_reception_new', $type_reception_value,400));

    $info = 'Необходимо создать новые доставки, в именах которых есть слово \'PickPoint\'. Если имя доcтавки требуется изменить, то
        новое имя-формулу вхождения нужно указать в настройках этого модуля в поле \'Имя доставка PickPoint\'. Для примера, при указании имени
        PickPoint, выбор пункта доставки появится у всех доставок, у которых слово PickPoint присутствует в имени доставки. Это правило не относится к
        именам каталога доставки.
<p>
После заказа на почту администратора придет письмо с вложенным XML файлом выгрузки заказа в панель управления услугой <a href="http://
PickPoint.ru?from=phpshop_mod" target="_blank">PickPoint<a>.
</p> ';

    $Tab2 = $PHPShopGUI->setInfo($info);

    // Форма регистрации
    $Tab3 = $PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 270), array("Инструкция", $Tab2, 270), array("О Модуле", $Tab3, 270));

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