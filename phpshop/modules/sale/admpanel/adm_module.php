<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.sale.sale_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm, $LoadItems;

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
    $price = ($price + (($price * $LoadItems['System']['percent']) / 100));
    $pr = $_POST['mod_sale_price'] / 100;

    switch ($_POST['mod_sale_old']) {
        case "null":
            $price_n = 'price_n=0, price=old_price_sale';
            break;
        case "price":
            $price_n = 'price_n=price';
            break;
        default:
            $price_n = null;
    }

    if (!empty($_POST['mod_sale_price'])) {

        if (!empty($price_n))
            $price_action = ',';
        else
            $price_action = null;

        $price_action.=' price=price' . $_POST['mod_sale_opt'] . '(price*' . $pr . ')';
    }


    $PHPShopOrm->sql = 'update ' . $GLOBALS['SysValue']['base']['products'] . ' set 
    ' . $price_n . $price_action;

    $action = $PHPShopOrm->update();
    header('Location: ?path=modules&install=check');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();

    $sel_value[] = array('+', '+', false);
    $sel_value[] = array('-', '-', 'selected');
    $Tab1 = $PHPShopGUI->setField('Новая цена изменить', $PHPShopGUI->setInputText(false, 'mod_sale_price', "", '100', '%', 'left') . $PHPShopGUI->set_() . $PHPShopGUI->setSelect('mod_sale_opt', $sel_value, 50, 'left'));

    $sel_value2[] = array('Выбрать', 'none');
    $sel_value2[] = array('Обнулить', 'null');
    $sel_value2[] = array('Присвоить значение розничной цены до изменения', 'price');

    $Tab1.=$PHPShopGUI->setField('Старая цена', $PHPShopGUI->setSelect('mod_sale_old', $sel_value2, 300));

    $Info = 'Для расчета скидки конкретного каталога используйте закладку "Распродажа" в карточке редактирования требуемого каталога товаров.
<p>Для снятия скидки выберите опцию "Обнулить" старые цены.';
    $Tab1.= $PHPShopGUI->setField('Совет', $PHPShopGUI->setInfo($Info));

    $Tab2 = $PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 270), array("О Модуле", $Tab2, 270));

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