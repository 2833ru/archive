<?php

PHPShopObj::loadClass(array('delivery', 'payment'));


$TitlePage = __('Создание Доставки');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['delivery']);

// Построение дерева категорий
function treegenerator($array, $i, $parent) {
    global $tree_array;
    $del = '¦&nbsp;&nbsp;&nbsp;&nbsp;';
    $tree = $tree_select = $check = false;

    if (!empty($array['sub']) and is_array($array['sub'])) {
        foreach ($array['sub'] as $k => $v) {
            $del = str_repeat($del, $i);
            $check = treegenerator($tree_array[$k], $i + 1, $k);


            if ($k == $_GET['parent_to'])
                $selected = 'selected';
            else
                $selected = null;

            if (empty($check['select'])) {
                $tree_select .= '<option value="' . $k . '" ' . $selected . '>' . $del . $v . '</option>';
                $i = 1;
            } else {
                $tree_select .= '<option value="' . $k . '" ' . $selected . '>' . $del . $v . '</option>';
                //$i++;
            }


            $tree .= '<tr class="treegrid-' . $k . ' treegrid-parent-' . $parent . ' data-tree">
		<td><a href="?path=delivery&id=' . $k . '">' . $v . '</a></td>
                    </tr>';

            $tree_select .= $check['select'];
            $tree .= $check['tree'];
        }
    }
    return array('select' => $tree_select, 'tree' => $tree);
}

/**
 * Экшен загрузки форм редактирования
 */
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $PHPShopSystem;

    $PHPShopDelivery = new PHPShopDelivery();

    // Размер названия поля
    $PHPShopGUI->field_col = 4;
    $PHPShopGUI->addJSFiles('./js/jquery.treegrid.js', './delivery/gui/delivery.gui.js');

    if (@$_GET['target'] == 'cat') {
        $catalog = true;
        $data['is_folder'] = 1;
    } else{
        $catalog = false;
        $data['is_folder'] = 0;
        }
    $data['is_mod'] = 1;

    // Начальные данные
    if ($catalog)
        $data['city'] = __('Новая категория доставки');
    else
        $data['city'] = __('Новая доставка');

    $data['enabled'] = 1;
    $data['PID'] = $_GET['cat'];
    
    $data=$PHPShopGUI->valid($data,'flag','price','price_null','price_null_enabled','taxa','ofd_nds','num','city_select','icon','payment','data_fields','comment','sum_max','sum_min','weight_max','weight_min','servers','warehouse');

    $PHPShopGUI->setActionPanel(__("Доставка") . ' &rarr; ' . $data['city'], false, array('Создать и редактировать', 'Сохранить и закрыть'));

    // Наименование
    $Tab_info = $PHPShopGUI->setField("Название", $PHPShopGUI->setInputText(false, 'city_new', $data['city'], '100%') . $PHPShopGUI->setInput('hidden', 'is_folder_new', $data['is_folder']));

    $PHPShopCategoryArray = new PHPShopDeliveryArray(array('is_folder' => "='1'"));
    $CategoryArray = $PHPShopCategoryArray->getArray();

    $CategoryArray[0]['city'] = '- ' . __('Корневой уровень') . ' -';
    $tree_array = array();

    foreach ($PHPShopCategoryArray->getKey('PID.id', true) as $k => $v) {
        foreach ($v as $cat) {
            $tree_array[$k]['sub'][$cat] = $CategoryArray[$cat]['city'];
        }
        $tree_array[$k]['name'] = $CategoryArray[$k]['city'];
        $tree_array[$k]['id'] = $k;
        if ($k == $data['PID'])
            $tree_array[$k]['selected'] = true;
    }

    $GLOBALS['tree_array'] = &$tree_array;
    $_GET['parent_to'] = $data['PID'];

    $tree_select = '<select class="selectpicker show-menu-arrow hidden-edit" data-container=""  data-style="btn btn-default btn-sm" name="PID_new"><option value="0">' . $CategoryArray[0]['city'] . '</option>';
    $tree = '<table class="tree table table-hover">';
    if ($k == $data['PID'])
        $selected = 'selected';
    if (is_array($tree_array[0]['sub']))
        foreach ($tree_array[0]['sub'] as $k => $v) {
            $check = treegenerator(@$tree_array[$k], 1, $k);

            $tree .= '<tr class="treegrid-' . $k . ' data-tree">
		<td><a href="?path=delivery&id=' . $k . '">' . $v . '</a></td>
                    </tr>';

            if ($k == $data['PID'])
                $selected = 'selected';
            else
                $selected = null;

            $tree_select .= '<option value="' . $k . '"  ' . $selected . '>' . $v . '</option>';

            $tree_select .= $check['select'];
            $tree .= $check['tree'];
        }
    $tree_select .= '</select>';
    $tree .= '</table>';

    // Выбор каталога
    if (!$catalog)
    $Tab_info .= $PHPShopGUI->setField("Каталог", $tree_select);

    // Вывод
    $Tab_info .= $PHPShopGUI->setField("Статус", $PHPShopGUI->setCheckbox('enabled_new', 1, null, $data['enabled'])); 
    $Tab_info .= $PHPShopGUI->setField("Доставка по умолчанию",$PHPShopGUI->setCheckbox('flag_new', 1, null, $data['flag']));

    // Цены
    $Tab_price = $PHPShopGUI->setField("Стоимость", $PHPShopGUI->setInputText(false, 'price_new', $data['price'], '150', $PHPShopSystem->getDefaultValutaCode()));

    $Tab_price .= $PHPShopGUI->setField("Бесплатная доставка свыше", $PHPShopGUI->setInputText(false, 'price_null_new', $data['price_null'], '150', $PHPShopSystem->getDefaultValutaCode()) . $PHPShopGUI->setCheckbox('price_null_enabled_new', 1, "Учитывать", $data['price_null_enabled']));

    // Такса
    $Tab_price .= $PHPShopGUI->setField(sprintf("Такса за каждые %s г веса", $PHPShopDelivery->fee), $PHPShopGUI->setInputText(false, 'taxa_new', $data['taxa'], '150', $PHPShopSystem->getDefaultValutaCode()) .
            $PHPShopGUI->setHelp(sprintf('Используется для задания дополнительной тарификации (например, для "Почта России").<br>Каждые дополнительные %s грамм свыше базовых %s грамм будут стоить указанную сумму.', $PHPShopDelivery->fee, $PHPShopDelivery->fee)));

    if ($data['ofd_nds'] == '')
        $data['ofd_nds'] = $PHPShopSystem->getParam('nds');

    $Tab_price .= $PHPShopGUI->setField("Значение НДС", $PHPShopGUI->setInputText(null, 'ofd_nds_new', $data['ofd_nds'], 100, '%'));

    // Тип сортировки
    $Tab_info .= $PHPShopGUI->setField("Приоритет", $PHPShopGUI->setInputText('№', "num_new", $data['num'], 150));

    // Настройка выбора городов из БД
    $city_select_value[] = array('Не использовать', 0, $data['city_select']);
    $city_select_value[] = array('Только Регионы и города РФ', 1, $data['city_select']);
    $city_select_value[] = array('Все страны мира', 2, $data['city_select']);

    if (!$catalog)
        $Tab_info .= $PHPShopGUI->setField("Помощь подбора", $PHPShopGUI->setSelect('city_select_new', $city_select_value, null, true));

    $Tab1 = $PHPShopGUI->setCollapse('Информация', $Tab_info);

    $Tab1 .= $PHPShopGUI->setCollapse('Внешний вид',$PHPShopGUI->setField("Изображение", $PHPShopGUI->setIcon($data['icon'], "icon_new", false)).
            $PHPShopGUI->setField("Комментарий", $PHPShopGUI->setTextarea('comment_new', $data['comment'], false)));

    $PHPShopPaymentArray = new PHPShopPaymentArray(array('enabled' => "='1'"));
    if (strstr($data['payment'], ","))
        $payment_array = explode(",", $data['payment']);
    else
        $payment_array[] = $data['payment'];

    $PaymentArray = $PHPShopPaymentArray->getArray();
    if (is_array($PaymentArray))
        foreach ($PaymentArray as $payment) {

            if (in_array($payment['id'], $payment_array))
                $payment_check = $payment['id'];
            else
                $payment_check = null;
            $payment_value[] = array($payment['name'], $payment['id'], $payment_check);
        }

    // Оплаты
    if (!empty($_GET['target']) and $_GET['target'] != 'cat') {
        $Tab2 = $PHPShopGUI->setField("Блокировка оплат", $PHPShopGUI->setSelect('payment_new[]', $payment_value, false, null, false, $search = false, false, 1, true));
        

        $Tab2 .= $PHPShopGUI->setField('Не изменять стоимость', $PHPShopGUI->setRadio('is_mod_new', 1, __('Выключить'), $data['is_mod'], false, 'text-warning') . $PHPShopGUI->setRadio('is_mod_new', 2, __('Включить'), $data['is_mod']));
    }
    
    
    // Склады
    $PHPShopOrmWarehouse = new PHPShopOrm($GLOBALS['SysValue']['base']['warehouses']);
    $dataWarehouse = $PHPShopOrmWarehouse->select(array('*'), array('enabled' => "='1'"), array('order' => 'num DESC'), array('limit' => 100));
    $warehouse_value[] = array(__('Общий склад'), 0, $data['warehouse']);
    if (is_array($dataWarehouse)) {
        foreach ($dataWarehouse as $val) {
            $warehouse_value[] = array($val['name'], $val['id'], $data['warehouse']);
        }
    }
    
    $Tab1 .= $PHPShopGUI->setCollapse('Дополнительно',$PHPShopGUI->setField("Витрины", $PHPShopGUI->loadLib('tab_multibase', $data, 'catalog/')).
            $PHPShopGUI->setField("Склад для списания", $PHPShopGUI->setSelect('warehouse_new', $warehouse_value, 300)));

    // Сумма заказа
    if (empty($_GET['target']) or $_GET['target'] != 'cat') {
        $Tab2 .= $PHPShopGUI->setField("Блокировка при стоимости более", $PHPShopGUI->setInputText(null, "sum_max_new", $data['sum_max'], 150, $PHPShopSystem->getDefaultValutaCode()));
        $Tab2 .= $PHPShopGUI->setField("Блокировка при стоимости менее", $PHPShopGUI->setInputText(null, "sum_min_new", $data['sum_min'], 150, $PHPShopSystem->getDefaultValutaCode()));
        $Tab2 .= $PHPShopGUI->setField("Блокировка при весе более", $PHPShopGUI->setInputText(null, "weight_max_new", $data['weight_max'], 150, 'грамм'));
        $Tab2 .= $PHPShopGUI->setField("Блокировка при весе менее", $PHPShopGUI->setInputText(null, "weight_min_new", $data['weight_min'], 150, 'грамм'));
    }
    
    if(!empty($Tab2))
    $Tab1 .= $PHPShopGUI->setCollapse('Блокировка',$Tab2);

    // Цены
    if (!$catalog)
        $Tab1 .= $PHPShopGUI->setCollapse('Цены', $Tab_price);


    // Дополнительные поля
    if (!$catalog)
        $Tab2 = $PHPShopGUI->loadLib('tab_option', $data);

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод формы закладки
    if (!$catalog)
        $PHPShopGUI->setTab(array("Основное", $Tab1,true,false,true), array("Адреса пользователя", $Tab2));
    else
        $PHPShopGUI->setTab(array("Основное", $Tab1,true,false,true));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "ОК", "right", 70, "", "but", "actionInsert.delivery.create");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция записи
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    $PHPShopOrm->updateZeroVars('flag_new', 'enabled_new', 'price_null_enabled_new');

    $_POST['icon_new'] = iconAdd('icon_new');

    // Оплаты
    if (isset($_POST['payment_new'])) {
        if (is_array($_POST['payment_new']))
            $_POST['payment_new'] = @implode(',', $_POST['payment_new']);
    }

    // Мультибаза
    $_POST['servers_new'] = "";
    if (is_array($_POST['servers']))
        foreach ($_POST['servers'] as $v)
            if ($v != 'null' and ! strstr($v, ',') and ! empty($v))
                $_POST['servers_new'] .= "i" . $v . "i";

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->insert($_POST);

    if ($_POST['saveID'] == 'Создать и редактировать')
        header('Location: ?path=' . $_GET['path'] . '&id=' . $action);
    else
        header('Location: ?path=' . $_GET['path']);

    return $action;
}

// Добавление изображения 
function iconAdd($name = 'icon_new') {

    // Папка сохранения
    $path = '/UserFiles/Image/';

    // Копируем от пользователя
    if (!empty($_FILES['file']['name'])) {
        $_FILES['file']['ext'] = PHPShopSecurity::getExt($_FILES['file']['name']);
        if (in_array($_FILES['file']['ext'], array('gif', 'png', 'jpg'))) {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['dir']['dir'] . $path . $_FILES['file']['name'])) {
                $file = $GLOBALS['dir']['dir'] . $path . $_FILES['file']['name'];
            }
        }
    }

    // Читаем файл из URL
    elseif (!empty($_POST['furl'])) {
        $file = $_POST[$name];
    }

    // Читаем файл из файлового менеджера
    elseif (!empty($_POST[$name])) {
        $file = $_POST[$name];
    }

    if (!empty($file)) {
        return $file;
    }
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$_POST = $PHPShopGUI->valid($_POST,'saveID');
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>