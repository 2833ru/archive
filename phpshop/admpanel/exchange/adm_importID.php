<?php

$TitlePage = __("Настройки импорта");

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI;

    // SQL
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['exchanges_log']);

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));
    $PHPShopGUI->setActionPanel(__('Настройки импорта от ') . PHPShopDate::get($data['date']), false, array('Закрыть'));
    $PHPShopGUI->field_col = 4;

    $option = unserialize($data['option']);

    // Настройки
    $PHPShopOrmExchanges = new PHPShopOrm($GLOBALS['SysValue']['base']['exchanges']);
    $data_exchanges = $PHPShopOrmExchanges->select(array('*'), array('id' => '=' . intval($option['exchanges']) . ' or id=' . intval($option['exchanges_new'])), false, array("limit" => 1));
    if(!is_array($data_exchanges)){
       $data_exchanges['name'] = '-';
    }

    if (empty($option['export_imgpath']))
        $option['export_imgpath'] = __('Выкл');
    else
        $option['export_imgpath'] = __('Вкл');

    if (empty($option['export_imgproc']))
        $option['export_imgproc'] = __('Выкл');
    else
        $option['export_imgproc'] = __('Вкл');

    if (empty($option['export_imgload']))
        $option['export_imgload'] = __('Выкл');
    else
        $option['export_imgload'] = __('Вкл');

    if (empty($option['export_uniq']))
        $option['export_uniq'] = __('Выкл');
    else
        $option['export_uniq'] = __('Вкл');


    $delim_value = array(';' => __('Точка с запятой'), ',' => __('Запятая'));
    $action_value = array('update' => __('Обновление'), 'insert' => __('Создание'));
    $delim_sortvalue = array('#' => '#', '@' => '@', '$' => '$', '-' => __('Колонка'));
    $delim_sort = array('/' => '/', '\\' => '\\', '-' => '-', '&' => '&', ';' => ';', ',' => ',');
    $delim_imgvalue = array(',' => __('Запятая'), 0 => __('Выкл'), ';' => __('Точка с запятой'), '#' => '#', ' ' => __('Пробел'));
    $code_value = array('ansi' => 'ANSI', 'utf' => 'UTF-8');

    if (!empty($option['export_key']))
        $key_value = $option['export_key'];
    else
        $key_value = 'Id или Артикул';

    if (empty($data['status'])) {
        $status = "<span class='text-warning'>" . __('Ошибка') . "</span>";
        $text = null;
        $class = 'hide';
    } else {
        $status = __("Выпонен");
        $info = unserialize($data['info']);
        $text = __('Обработано ') . $info[0] . (' строк') . '.<br><a href="' . $info[3] . '" target="_blank">' . $info[1] . ' ' . $info[2] . __(' записей') . '</a>';
    }

    // Закладка 1
    $Tab1 = $PHPShopGUI->setField("Файл", $PHPShopGUI->setText($PHPShopGUI->setLink($data['file'], pathinfo($data['file'])['basename']))) .
            $PHPShopGUI->setField("Настройка", $PHPShopGUI->setText('<a href="?path=exchange.import&exchanges=' . $data_exchanges['id'] . '">' . $data_exchanges['name'] . '</a>'), false, false, $class) .
            $PHPShopGUI->setField("Обработано строк", $PHPShopGUI->setText($info[0]), false, false, $class) .
            $PHPShopGUI->setField($info[1] . ' записей', $PHPShopGUI->setText($info[2]), false, false, $class) .
            $PHPShopGUI->setField('Загружено изображений', $PHPShopGUI->setText((int) $info[4]), false, false, $class) .
            $PHPShopGUI->setField('Отчет', $PHPShopGUI->setText('<a href="' . $info[3] . '" target="_blank">CSV</a>'), false, false, $class) .
            $PHPShopGUI->setField('Действие', $PHPShopGUI->setText($action_value[$option['export_action']])) .
            $PHPShopGUI->setField('CSV-разделитель', $PHPShopGUI->setText($delim_value[$option['export_delim']])) .
            $PHPShopGUI->setField('Разделитель для характеристик', $PHPShopGUI->setText($delim_sortvalue[$option['export_sortdelim']])) .
            $PHPShopGUI->setField('Разделитель значений характеристик', $PHPShopGUI->setText($delim_sort[$option['export_sortsdelim']])) .
            $PHPShopGUI->setField('Полный путь для изображений', $PHPShopGUI->setText($option['export_imgpath']), 1, 'Добавляет к изображениям папку /UserFiles/Image/') .
            $PHPShopGUI->setField('Обработка изображений', $PHPShopGUI->setText($option['export_imgproc']), 1, 'Создание тумбнейла и ватермарка') .
            $PHPShopGUI->setField('Загрузка изображений', $PHPShopGUI->setText($option['export_imgload']), 1, 'Загрузка изображений на сервер по ссылке') .
            $PHPShopGUI->setField('Разделитель для изображений', $PHPShopGUI->setText($delim_imgvalue[$option['export_imgdelim']])) .
            $PHPShopGUI->setField('Кодировка текста', $PHPShopGUI->setText($code_value[$option['export_code']])) .
            $PHPShopGUI->setField('Ключ обновления', $PHPShopGUI->setText($key_value)) .
            $PHPShopGUI->setField('Проверка уникальности', $PHPShopGUI->setText($option['export_uniq']), 1, 'Исключает дублирование данных при создании');

    $Tab1 = $PHPShopGUI->setCollapse('Настройки', $Tab1);

    $name_col = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T'];
    foreach ($option['select_action'] as $k => $p) {
        if (empty($p))
            $p = '-';
        $Tab2 .= $PHPShopGUI->setField('Колонка ' . $name_col[$k], $PHPShopGUI->setText(__($p)));
    }

    $Tab2 = $PHPShopGUI->setCollapse('Сопоставление полей', $Tab2);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Информация", $Tab1 . $Tab2, true, false, true));

    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>