<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.cron.cron_job"));

// Функция обновления
function actionInsert() {
    global $PHPShopOrm;

    // Мультибаза
    if (is_array($_POST['servers'])) {
        $_POST['servers_new'] = "";
        foreach ($_POST['servers'] as $v)
            if ($v != 'null' and ! strstr($v, ','))
                $_POST['servers_new'] .= "i" . $v . "i";
    }

    $action = $PHPShopOrm->insert($_POST);
    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI;

    $work[] = array('Выбрать', '');
    $work[] = array('Бекап БД', 'phpshop/modules/cron/sample/dump.php');
    $work[] = array('Курсы валют', 'phpshop/modules/cron/sample/currency.php');
    $work[] = array('Снятие с продаж товаров', 'phpshop/modules/cron/sample/product.php');
    $work[] = array('Разновалютый поиск', 'phpshop/modules/cron/sample/pricesearch.php');

    // Учет модуля SiteMap
    if (!empty($GLOBALS['SysValue']['base']['sitemap']['sitemap_system'])) {
        $work[] = array("|");
        $work[] = array('Карта сайта', 'phpshop/modules/sitemap/cron/sitemap_generator.php');
        $work[] = array('Карта сайта SSL', 'phpshop/modules/sitemap/cron/sitemap_generator.php?ssl');
    }

    // Загрузка CSV
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['exchanges']);
    $exchanges_data = $PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array("limit" => "1000"));
    if (is_array($exchanges_data)) {
        foreach ($exchanges_data as $row) {

            if ($row['type'] == 'import')
                $import[] = array($row['name'], 'phpshop/modules/cron/sample/import.php?id=' . $row['id']);
            elseif ($row['type'] == 'export')
                $export[] = array($row['name'], 'phpshop/modules/cron/sample/export.php?id=' . $row['id'].'&file=export_'.md5($row['name']));
        }

        $work[] = array('Импорт данных', $import);
        $work[] = array('Экспорт данных', $export);
    }

    $Tab1 = $PHPShopGUI->setField("Название задачи:", $PHPShopGUI->setInput("text.requared", "name_new", __('Новая задача')));
    $Tab1 .= $PHPShopGUI->setField("Запускаемый Файл:", $PHPShopGUI->setInputArg(array('type' => "text.requared", 'name' => "path_new", 'size' => '60%', 'float' => 'left', 'placeholder' => 'phpshop/modules/cron/sample/testcron.php')) . '&nbsp;' . $PHPShopGUI->setSelect('work', $work, 200, true, false, false, false, false, false, false, 'selectpicker', '$(\'input[name=path_new]\').val(this.value);'));
    $Tab1 .= $PHPShopGUI->setField("Статус", $PHPShopGUI->setCheckbox("enabled_new", 1, "Включить", 1));
    $Tab1 .= $PHPShopGUI->setField("Кол-во запусков в день", $PHPShopGUI->setSelect('execute_day_num_new', $PHPShopGUI->setSelectValue(false), 70));
    $Tab1 .= $PHPShopGUI->setField("Витрины", $PHPShopGUI->loadLib('tab_multibase', null, 'catalog/'));



    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "Сохранить", "right", false, false, false, "actionInsert.modules.create");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>