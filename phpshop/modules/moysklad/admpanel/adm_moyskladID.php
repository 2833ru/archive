<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.moysklad.moysklad_log"));

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

   
    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));
    
    $PHPShopGUI->setActionPanel('Журнал от '.PHPShopDate::get($data['date']), false, array('Закрыть'));
    
    // Переводим в читаемый вид
    ob_start();
    print_r(unserialize($data['message']));
    $log = ob_get_clean();

    $Tab1 = $PHPShopGUI->setTextarea(null, PHPShopString::utf8_win1251($log), $float = "none", $width = '99%', $height = '340');

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Информация о заказе", $Tab1, 370));

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>