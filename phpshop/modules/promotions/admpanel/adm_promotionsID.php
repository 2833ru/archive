<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.promotions.promotions_forms"));

function TestCat($n) {// есть ли еще подкаталоги
    global $SysValue, $link_db;
    $sql = "select id from " . $SysValue['base']['table_name'] . " where parent_to='$n'";
    $result = mysqli_query($link_db, $sql);
    $num = mysqli_num_rows($result);
    return $num;
}

function Vivod_rekurs($n, $prefix, $categories) {// вывод подкаталогов рекурсом
    global $SysValue, $link_db;
    $sql = "select * from " . $SysValue['base']['table_name'] . " where parent_to='$n' order by num, name";
    $result = mysqli_query($link_db, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $i = 0;
        $id = $row['id'];
        $name = str_replace(array('"', "'"), " ", $row['name']);
        $parent_to = $row['parent_to'];
        $num = TestCat($id);



        if ($i < $num) {// если есть еще каталоги
            //@$disp.=$name . Vivod_rekurs($id);
            $disp.='<option value="' . $id . '" disabled>' . $prefix . ' ' . $name . '</option>';
            $prefix = $prefix . '-';
            $disp.=Vivod_rekurs($id, $prefix, $categories);
        } else {// если нет каталогов
            $catego_ar = explode(',', $categories);
            foreach ($catego_ar as $val_c) {
                if ($val_c == $id):
                    $ssel = 'selected';
                    break;
                else:
                    $ssel = '';
                endif;
            }
            $disp.='<option value="' . $id . '" ' . $ssel . '>' . $prefix . ' ' . $name . '</option>';
        }
    }
    return @$disp;
}

function Vivod_pot($categories) {// вывод каталогов
    global $link_db;
    $sql = "select * from " . $GLOBALS['SysValue']['base']['table_name'] . " where parent_to=0 order by num, name";
    $result = mysqli_query($link_db, $sql);
    $i = 0;
    $dis = null;
    while ($row = mysqli_fetch_array($result)) {
        $id = $row['id'];
        $name = str_replace(array('"', "'"), " ", $row['name']);
        $num = TestCat($id);
        if ($num > 0) {
            //$dis.=$name . Vivod_rekurs($id);
            $dis .= '<option value="' . $id . '" disabled>' . $name . '</option>';
            $dis .= Vivod_rekurs($id, '-', $categories);
        } else {
            //@$dis.=$name . Vivod_rekurs($id);
            $catego_ar = explode(',', $categories);
            foreach ($catego_ar as $val_c) {
                if ($val_c == $id) {
                    $ssel = 'selected';
                    break;
                } else {
                    $ssel = '';
                }
            }
            $dis .= '<option value="' . $id . '" ' . $ssel . '>' . $name . '</option>';
            //$dis .= Vivod_rekurs($id, '-',$categories);
        }
        $i++;
    }

    return $dis;
}

function Vivod_cat_all_num($n) {// выбор кол-ва товаров из данного подкатолога
    global $SysValue, $link_db;
    $sql = "select id from " . $SysValue['base']['table_name'] . " where category='$n' and enabled='1'";
    $result = mysqli_query($link_db, $sql);
    $num = mysqli_num_rows($result);
    return $num;
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $_POST['enabled_new'] = $_POST['enabled_new'][0];

    if (empty($_POST['ajax'])) {
        if (empty($_POST['enabled_new']))
            $_POST['enabled_new'] = 0;
        if (empty($_POST['active_check_new']))
            $_POST['active_check_new'] = 0;
        if (empty($_POST['discount_check_new']))
            $_POST['discount_check_new'] = 0;
        if (empty($_POST['free_delivery_new']))
            $_POST['free_delivery_new'] = 0;
        if (empty($_POST['categories_check_new']))
            $_POST['categories_check_new'] = 0;
        if (empty($_POST['products_check_new']))
            $_POST['products_check_new'] = 0;
        if (empty($_POST['sum_order_check_new']))
            $_POST['sum_order_check_new'] = 0;
        if (empty($_POST['delivery_method_check_new']))
            $_POST['delivery_method_check_new'] = 0;
        if (empty($_POST['code_check_new']))
            $_POST['code_check_new'] = 0;
        if (empty($_POST['discount_tip_new']))
            $_POST['discount_tip_new'] = 0;
        if (empty($_POST['code_tip_new']))
            $_POST['code_tip_new'] = 0;

        if (isset($_POST['categories'])) {
            foreach ($_POST['categories'] as $value) {
                $_POST['categories_new'] .= $value . ',';
            }
        } else {
            $_POST['categories_new'] = '';
        }

        $PHPShopOrm->updateZeroVars('block_old_price_new');

        //Общая скидка
        if ($_POST['code_new'] == "") {
            $_POST['code_new'] = '*';
        }
    }


    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    return array('success' => $action);
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $PHPShopOrm, $PHPShopModules, $select_name;

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));

    // Выбор даты
    $PHPShopGUI->addJSFiles('./js/bootstrap-datetimepicker.min.js', './news/gui/news.gui.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-datetimepicker.min.css');

    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->addJSFiles('../modules/promotions/admpanel/gui/promotions.gui.js');

    $Tab1 = $PHPShopGUI->setCollapse('Основное', $PHPShopGUI->setField('Название', $PHPShopGUI->setInputText('', 'name_new', $data['name'], 300)) . $PHPShopGUI->setField('Статус', $PHPShopGUI->setRadio("enabled_new[]", 1, "Показывать", $data['enabled']) . $PHPShopGUI->setRadio("enabled_new[]", 0, "Скрыть", $data['enabled'])));

    $versphp = phpversion(); //5.3.0
    //$versphp = "4.1.1";
    $version_status = version_compare($versphp, "5.3.0");

    if ($version_status != '-1') {
        $Tab1.=$PHPShopGUI->setCollapse('Активность', $PHPShopGUI->setField('Статус', $PHPShopGUI->setCheckbox("active_check_new", 1, "Учитывать активность", $data['active_check'])) . $PHPShopGUI->setField('Начало', $PHPShopGUI->setInputDate("active_date_ot_new", $data['active_date_ot'])) . $PHPShopGUI->setField('Завершение', $PHPShopGUI->setInputDate("active_date_do_new", $data['active_date_do'])));
    }


    $Tab1.=$PHPShopGUI->setCollapse('Скидка', $PHPShopGUI->setField('Статус', $PHPShopGUI->setCheckbox("discount_check_new", 1, "Учитывать скидку или бесплатную доставку", $data['discount_check'])) .
            $PHPShopGUI->setField('Тип', $PHPShopGUI->setRadio("discount_tip_new", 1, "%", $data['discount_tip']) . $PHPShopGUI->setRadio("discount_tip_new", 0, "сумма", $data['discount_tip']), 'left') .
            $PHPShopGUI->setField('Скидка', $PHPShopGUI->setInputText('', 'discount_new', $data['discount'], '100')) .
            $PHPShopGUI->setField('Доставка', $PHPShopGUI->setCheckbox("free_delivery_new", 1, "Бесплатная доставка", $data['free_delivery']))
    );

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.promotions.promotions_codes"));
    $PHPShopOrm->debug = false;
    $id = $data['id'];
    $count_all = $PHPShopOrm->select(array('count("id") as count'), array('promo_id' => "='$id'"));

    $qty_all_count = $count_all['count'];

    $PHPShopOrm->clean();

    $count_active = $PHPShopOrm->select(array('count("id") as count'), array('promo_id' => "='$id'", 'enabled' => '="1"'));
    $qty_active_count = $count_active['count'];
    // способ оплаты
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['payment_systems']);
    $data_payment_systems = $PHPShopOrm->select(array('id,name'), false, array('order' => 'name'), array('limit' => 100));

    foreach ($data_payment_systems as $value) {
        if ($value['id'] == $data['delivery_method'])
            $sel = 'selected';
        else
            $sel = false;
        $value_payment_systems[] = array($value['name'], $value['id'], $sel);
    }


    //рекурс категорий в мультиселект
    $categories_option = Vivod_pot($data['categories']);
    $categories_mul_sel .= '<select multiple size="10" id="categories"  class="form-control input-sm" name="categories[]">' . $categories_option . '</select>';



    if ($qty_all_count)
        $qty_all = $PHPShopGUI->setDiv(false, $qty_all_count, '" class="badge  badge-info', 'qty-all" data-toggle="tooltip" title="Всего промокодов');
    if ($qty_active_count)
        $qty_active = $PHPShopGUI->setDiv(false, $qty_active_count, '" class="badge btn-success', 'qty-active" data-toggle="tooltip" title="Активных промокодов');
    $qty_off_count = $qty_all_count - $qty_active_count;

    if ($qty_off_count > 0)
        $qty_off = '<button class="btn btn-danger btn-sm" type="button" id="qty_del" name="qty_del"> Удалить <span id="qty_off_count" data-count="' . $qty_off_count . '">' . $qty_off_count . '</span> использованных промокодов
</button>';


    $Tab1.=$PHPShopGUI->setCollapse('Условия', $PHPShopGUI->setField('Категории', $PHPShopGUI->setHelp('Выберите категории товаров и/или укажите ID товаров для акции.') . $PHPShopGUI->setCheckbox("categories_check_new", 1, "Учитывать категории товара", $data['categories_check']) . $PHPShopGUI->setCheckbox("selectalloption", 1, "Выбрать все категории?", '', "right") . $categories_mul_sel) .
            $PHPShopGUI->setField('Товары', $PHPShopGUI->setCheckbox("products_check_new", 1, "Учитывать товары", $data['products_check']) . $PHPShopGUI->setCheckbox("block_old_price_new", 1, "Игнорировать товары со старой ценой", $data['block_old_price']) .
                    $PHPShopGUI->setTextarea('products_new', $data['products']) . $PHPShopGUI->setHelp('ID товаров в формате 1,2,3 без пробелов'))
            .
            $PHPShopGUI->setField('Заказ', $PHPShopGUI->setCheckbox("sum_order_check_new", 1, "Учитывать сумму заказа", $data['sum_order_check']) .
                    $PHPShopGUI->setInputText(null, 'sum_order_new', $data['sum_order'], '200', $PHPShopSystem->getDefaultValutaCode()) .
                    $PHPShopGUI->setCheckbox("delivery_method_check_new", 1, "Учитывать способ оплаты", $data['delivery_method_check']) . '<br>' .
                    $PHPShopGUI->setSelect('delivery_method_new', $value_payment_systems, 300)
    ));

    $Tab1.=$PHPShopGUI->setCollapse('Купон', $PHPShopGUI->setField('Статус', $PHPShopGUI->setCheckbox("code_check_new", 1, "Учитывать код купона", $data['code_check'])) .
            $PHPShopGUI->setField('Код', $PHPShopGUI->setInputText('', 'code_new', $data['code'], '170', false, 'left') . '&nbsp;' .
                    $PHPShopGUI->setInput('button', 'gen', 'Сгенерировать', $float = "none", 120, $onclick = "randAa(10);", 'btn-sm btn-success')) .
            $PHPShopGUI->setField($qty_all . ' ' . $qty_active . '  Коды', '<div class="form-inline">' . $PHPShopGUI->setInputText('Кол-во', 'qty_new', '1', '130', false, 'left') . '&nbsp;'
                    . $PHPShopGUI->setInput('button', 'qty_gen', 'Сгенерировать', '', 120, false, 'btn-sm btn-success') . '&nbsp;' . $qty_off . '&nbsp;<button class="btn btn-default btn-sm" type="button" id="download_codes" name="download_codes"> Скачать активные промокоды</button></div>') .
            $PHPShopGUI->setAlert("Коды успешно сгенерированы", "success hide col-md-3 col-md-offset-2") .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setProgress('Идет генерация...', 'hide') . $PHPShopGUI->setAlert("Использованные коды успешно удалены", "success hide col-md-3 col-md-offset-2") . $PHPShopGUI->setLine() .
            $PHPShopGUI->setField('Использование', $PHPShopGUI->setRadio("code_tip_new", 1, "Одноразово", $data['code_tip']) .
                    $PHPShopGUI->setRadio("code_tip_new", 0, "Многоразово", $data['code_tip']), 'left')
    );

    // Заголовок
    $kupon.=$PHPShopGUI->setField('Тема письма', $PHPShopGUI->setInputText('', 'header_mail_new', $data['header_mail']) . $PHPShopGUI->setHelp('Письмо будет отправлен пользователю при успешном введении промокода.'));

    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"), true);
    $oFCKeditor = new Editor('content_mail_new', true);
    $oFCKeditor->Height = '120';
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = $data['content_mail'];

    $kupon.=$PHPShopGUI->setField('Содержимое письма', $oFCKeditor->AddGUI());

    $Tab2 = $PHPShopGUI->setCollapse('Уведомление', $kupon);

    $oFCKeditor = new Editor('description_new', true);
    $oFCKeditor->Height = '120';
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = $data['description'];

    $template = $PHPShopGUI->setField('Описание акции на сайте', $oFCKeditor->AddGUI());

    $oFCKeditor = new Editor('label_new', true);
    $oFCKeditor->Height = '120';
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = $data['label'];

    $template.= $PHPShopGUI->setField('Лейбл товара на сайте', $oFCKeditor->AddGUI());

    $Tab2.=$PHPShopGUI->setCollapse('Оформление', $template);


    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1), array("Дополнительно", $Tab2));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "", "but", "actionDelete.modules.edit") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.modules.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

/**
 * Экшен сохранения
 */
function actionSave() {

    // Сохранение данных
    actionUpdate();

    header('Location: ?path=' . $_GET['path']);
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.promotions.promotions_codes"));
    $action = $PHPShopOrm->delete(array('promo_id' => '=' . $_POST['rowID']));


    return array("success" => $action);
}

// Обработка событий
$PHPShopGUI->getAction();


// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>