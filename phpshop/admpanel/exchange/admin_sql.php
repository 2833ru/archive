<?php

$TitlePage = __("SQL запрос к базе");

// Описание полей
$sqlHelper = array(
    'phpshop_categories' => 'Категории товаров',
    'phpshop_orders' => 'Заказы пользователей',
    'phpshop_products' => 'Товарные позиции. <a href="https://help.phpshop.ru/knowledgebase/article/171" target="_blank"><span class="glyphicon glyphicon-share-alt"></span> Описание полей</a>',
    'phpshop_system' => 'Настройки сайта',
    "phpshop_gbook" => "Отзывы о сайте из гостевой книги",
    "phpshop_news" => 'Новости',
    "phpshop_jurnal" => 'Журнал авторизации администраторов',
    "phpshop_page" => 'Страницы сайта (главное меню, контакты и т.д.)',
    "phpshop_menu" => 'Текстовые информационные блоки',
    "phpshop_baners" => 'Рекламные баннеры',
    "phpshop_links" => 'Полезные ссылки',
    "phpshop_search_jurnal" => 'Журнал поиска по сайту',
    "phpshop_users" => 'Администраторы сайта',
    "phpshop_sort_categories" => 'Наборы характеристик для привязки к каталогам товаров',
    "phpshop_sort" => 'Характеристики их значения',
    "phpshop_shopusers" => 'Пользователи сайта, покупатели',
    "phpshop_page_categories" => 'Категории страниц',
    "phpshop_foto" => 'Изображения товаров',
    "phpshop_comment" => 'Комментарии к товарам, оставленные пользователями',
    "phpshop_messages" => 'Сообщения для администрации, оставленные пользователями',
    "phpshop_modules" => 'Подключенные дополнительные модули',
    "phpshop_newsletter" => 'Тексты рассылок',
    "phpshop_slider" => 'Слайдер на главной странице',
    "phpshop_slider" => 'Слайдер на главной странице',
);

// Функция обновления
function actionSave() {
    global $PHPShopGUI, $result_message, $result_error_tracert, $link_db;

    // Выполнение команд из формы
    if (!empty($_POST['sql_text'])) {
        $sql_query = explode(";\r", trim($_POST['sql_text']));

        foreach ($sql_query as $v)
            $result = mysqli_query($link_db, trim($v));

        // Выполнено успешно
        if ($result)
            $result_message = $PHPShopGUI->setAlert(__('SQL запрос успешно выполнен'));
        else {
            $result_message = $PHPShopGUI->setAlert(__('SQL ошибка').': ' . mysqli_error($link_db), 'danger');
            $result_error_tracert = $_POST['sql_text'];
        }
    }

    // Копируем csv от пользователя
    if (!empty($_FILES['file']['name'])) {
        $_FILES['file']['ext'] = PHPShopSecurity::getExt($_FILES['file']['name']);
        if ($_FILES['file']['ext'] == "sql") {
            if (move_uploaded_file($_FILES['file']['tmp_name'], "csv/" . $_FILES['file']['name'])) {
                $csv_file = "csv/" . $_FILES['file']['name'];
                $csv_file_name = $_FILES['file']['name'];
            }
            else
                $result_message = $PHPShopGUI->setAlert(__('Ошибка сохранения файла').' <strong>' . $csv_file_name . '</strong> в phpshop/admpanel/csv', 'danger');
        }
    }

    // Читаем файл из URL
    elseif (!empty($_POST['furl'])) {
        $csv_file = $_POST['furl'];
        $path_parts = pathinfo($csv_file);
        $csv_file_name = $path_parts['basename'];
    }

    // Читаем файл из файлового менеджера
    elseif (!empty($_POST['lfile'])) {
        $csv_file = $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['dir']['dir'] . $_POST['lfile'];
        $path_parts = pathinfo($csv_file);
        $csv_file_name = $path_parts['basename'];
    }


    // Обработка sql
    if (!empty($csv_file)) {
        $result_error_tracer = $error_line = null;

        // GZIP
        if ($path_parts['extension'] == 'gz') {
            ob_start();
            readgzfile($csv_file);
            $sql_file_content = ob_get_clean();
        }
        else
            $sql_file_content = file_get_contents($csv_file);

        $sql_query = explode(";\r", $sql_file_content);
        $count = count($sql_query);
        if ($count < 1)
            $sql_query = explode(";", $sql_file_content);


        foreach ($sql_query as $k => $v) {

            if (strlen($v) > 10)
                $result = mysqli_query($link_db, $v);

            if (!$result) {
                $error_line.='[Line ' . $k . '] ';
                $result_error_tracert.= 'Запрос: ' . $v . '
Ошибка: ' . mysqli_error($link_db);
            }
        }
        
                // Удаление файла после выполнения
        if(isset($_POST['clean']))
            @unlink($csv_file);

        // Выполнено успешно
        if (empty($result_error_tracert)) {
            if (!empty($_POST['ajax']))
                return array("success" => true);
            else
                $result_message = $PHPShopGUI->setAlert(__('SQL запрос успешно выполнен'));
        }
        else {
            if (!empty($_POST['ajax']))
                return array("success" => false, "error" => mysqli_error($link_db) . ' -> ' . $error_line);
            else
                $result_message = $PHPShopGUI->setAlert(__('SQL ошибка').' ' . mysqli_error($link_db), 'danger');
        }
        
    }
}

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $TitlePage, $PHPShopModules, $result_message, $result_error_tracert, $PHPShopSystem, $selectModalBody, $sqlHelper;

    $PHPShopGUI->action_button['Выполнить'] = array(
        'name' => 'Выполнить',
        'class' => 'btn btn-primary btn-sm navbar-btn ace-save',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-ok'
    );

    $bases = $DROP = $TRUNCATE = $selectModal = null;
    $baseArray = array();

    foreach ($GLOBALS['SysValue']['base'] as $val) {
        if (is_array($val)) {
            foreach ($val as $mod_base)
                $baseArray[$mod_base] = $mod_base;
        }
        else
            $baseArray[$val] = $val;
    }

    foreach ($baseArray as $val) {
        if (!empty($val)) {
            $bases.="`" . $val . "`, ";
            $DROP.='DROP TABLE ' . $val . ';
';
            if(!empty($sqlHelper[$val]))
            $selectModal.='<tr><td><kbd>' . $val . '</kbd></td><td>' . $sqlHelper[$val] . '</td></tr>';
        }
    }

    unset($baseArray['phpshop_system']);
    unset($baseArray['phpshop_users']);
    unset($baseArray['phpshop_valuta']);
    unset($baseArray['phpshop_citylist_country']);
    unset($baseArray['phpshop_citylist_region']);
    unset($baseArray['phpshop_citylist_city']);
    unset($baseArray['phpshop_modules_key']);


    $TRUNCATE = null;

    foreach ($baseArray as $val) {
        $TRUNCATE.='TRUNCATE `' . $val . '`;
';
    }

    $bases = substr($bases, 0, strlen($bases) - 2) . ';';

    // Размер названия поля
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->addJSFiles('./exchange/gui/exchange.gui.js', './tpleditor/gui/ace/ace.js');

    $PHPShopGUI->_CODE = $result_message;
    $help = '<p class="text-muted">'.__('Для очистки демо-базы и демо-товаров следует выбрать SQL команду <kbd>Очистить базу</kbd></p> <p class="text-muted">Для увелечения производительности сайта вызвать SQL команду <kbd>Оптимизировать базу</kbd></p> <p class="text-muted">Справочник полезных SQL команд для пакетной обработки товаров доступен в <a href="https://help.phpshop.ru/knowledgebase/article/398" target="_blank" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-book"></span> Базе знаний</a>').'</p>';


    $PHPShopGUI->setActionPanel($TitlePage, false, array('Выполнить'));

    if ($_GET['query'] == 'optimize')
        $optimize_sel = 'selected';
    else
        $optimize_sel = null;

    $query_value[] = array('Выбрать SQL команду', 0, '');
    $query_value[] = array('Оптимизировать базу', 'OPTIMIZE TABLE ' . $bases, $optimize_sel);
    $query_value[] = array('Починить базу', 'REPAIR TABLE ' . $bases, '');
    $query_value[] = array('Удалить характеристики', 'TRUNCATE ' . $GLOBALS['SysValue']['base']['sort'] . ';
TRUNCATE ' . $GLOBALS['SysValue']['base']['sort_categories'].';
UPDATE ' . $GLOBALS['SysValue']['base']['products'] . ' set vendor=\'\', vendor_array=\'\';
UPDATE ' . $GLOBALS['SysValue']['base']['categories'] . ' set sort=\'\';', '');
    $query_value[] = array('Удалить каталог товаров', 'DELETE FROM ' . $GLOBALS['SysValue']['base']['categories'] . ' WHERE ID=', '');
    $query_value[] = array('Удалить все каталоги', 'TRUNCATE ' . $GLOBALS['SysValue']['base']['categories'], '');
    $query_value[] = array('Удалить все товары', 'TRUNCATE ' . $GLOBALS['SysValue']['base']['products'].';
TRUNCATE ' . $GLOBALS['SysValue']['base']['foto'].';', '');
    $query_value[] = array('Удалить товары в каталоге', 'DELETE FROM ' . $GLOBALS['SysValue']['base']['products'] . ' WHERE category=', '');
    $query_value[] = array('Удалить страницу', 'DELETE FROM ' . $GLOBALS['SysValue']['base']['page'] . ' WHERE ID=', '');
    $query_value[] = array('Починить зацикливающиеся каталоги', 'UPDATE ' . $GLOBALS['SysValue']['base']['categories'] . ' SET parent_to=0 WHERE parent_to=id', '');
    $query_value[] = array('Уменьшить время генерации меню каталогов',"UPDATE phpshop_categories SET phpshop_categories.vid = '0' WHERE phpshop_categories.parent_to IN (select * from ( SELECT phpshop_categories.id
 FROM phpshop_categories WHERE phpshop_categories.parent_to='0')t );
 UPDATE phpshop_categories SET vid='1' where parent_to !='0';");
    
    
    
    $query_value[] = array('Очистить базу', $TRUNCATE, '');
    $query_value[] = array('Уничтожить базу (!)', $DROP, '');
    

    // Оптимизация по ссылке
    if ($_GET['query'] == 'optimize')
        $result_error_tracert = 'OPTIMIZE TABLE ' . $bases;

    // Тема
    $theme = $PHPShopSystem->getSerilizeParam('admoption.ace_theme');
    if (empty($theme))
        $theme = 'dawn';

    $PHPShopGUI->_CODE.= '<textarea class="hide hidden-edit" id="editor_src" name="sql_text" data-mod="sql" data-theme="' . $theme . '">' . $result_error_tracert . '</textarea><pre id="editor">'.__('Загрузка').'...</pre>';

    $PHPShopGUI->_CODE.= '<div class="text-right data-row"><a href="#" id="vartable" data-toggle="modal" data-target="#selectModal" data-title="Основные таблицы"><span class="glyphicon glyphicon-question-sign"></span>'.__('Описание таблиц').'</a></div>';

    // Модальное окно таблицы описаний перменных
    $selectModalBody = '<table class="table table-striped"><tr><th>'.__('Таблица').'</th><th>'.__('Описание').'</th></tr>' . $selectModal . '</table>';

    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('Настройки', $PHPShopGUI->setField('Команда', $PHPShopGUI->setSelect('sql_query', $query_value,null,true)) .
            $PHPShopGUI->setField(__("Файл"), $PHPShopGUI->setFile()), 'in', false, true
    );

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, false);


    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("hidden", "saveID", "Применить", "right", 80, "", "but", "actionSave.system.edit");

    $PHPShopGUI->setFooter($ContentFooter);

    // Футер
    $sidebarleft[] = array('title' => 'Категории', 'content' => $PHPShopGUI->loadLib('tab_menu_service', false, './exchange/'));
    $sidebarleft[] = array('title' => 'Подсказка', 'content' => $help);
    $PHPShopGUI->setSidebarLeft($sidebarleft, 2);
    $PHPShopGUI->Compile(2);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();
?>