<?php

$TitlePage = __("Основные Настройки");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['system']);

// Выбор html редактора
function GetEditors($editor) {
    global $PHPShopGUI;

    if ($editor == 'tiny_mce')
        $editor = 'default';

    $dir = "./editors/";
    if (is_dir($dir)) {
        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {

                if ($editor == $file)
                    $sel = "selected";
                else
                    $sel = "";

                if ($file != "." and $file != ".." and $file != "index.html")
                    $value[] = array($file, $file, $sel);
            }
            closedir($dh);
        }
    }

    return $PHPShopGUI->setSelect('option[editor]', $value);
}

// Выбор шаблона дизайна
function GetSkinList($skin) {
    global $PHPShopGUI;
    $dir = "../templates/";

    if (is_dir($dir)) {
        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if (file_exists($dir . '/' . $file . "/main/index.tpl")) {

                    if ($skin == $file)
                        $sel = "selected";
                    else
                        $sel = "";

                    if ($file != "." and $file != ".." and !strpos($file, '.'))
                        $value[] = array($file, $file, $sel);
                }
            }
            closedir($dh);
        }
    }

    return $PHPShopGUI->setSelect('skin_new', $value);
}

// Выбор цвета редактора шабонов
function GetAceSkinList($skin) {
    global $PHPShopGUI;
    $dir = "./tpleditor/gui/ace/";

    if (empty($skin))
        $skin = 'dawn';

    if (is_dir($dir)) {
        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {

                if (preg_match("/^theme-([a-zA-Z0-9_]{1,30}).js$/", $file, $match)) {

                    $file = str_replace(array('.js', 'theme-'), '', $file);

                    if ($skin == $file)
                        $sel = "selected";
                    else
                        $sel = "";

                    if ($file == 'dawn')
                        $value[] = array('default', 'dawn', $sel);

                    elseif ($file != "." and $file != ".." and !strpos($file, '.'))
                        $value[] = array($file, $file, $sel);
                }
            }
            closedir($dh);
        }
    }

    return $PHPShopGUI->setSelect('option[ace_theme]', $value, 200);
}

// Выбор шаблона панели управления
function GetAdminSkinList($skin) {
    global $PHPShopGUI;
    $dir = "./css/";

    $color = array(
        'default' => '#178ACC',
        'cyborg' => '#000',
        'flatly' => '#D9230F',
        'spacelab' => '#46709D',
        'slate' => '#4E5D6C',
        'yeti' => '#008CBA',
        'simplex' => '#DF691A',
        'sardbirds' => '#45B3AF',
        'wordless' => '#468966',
        'wildspot' => '#564267',
        'loving' => '#FFCAEA',
        'retro' => '#BBBBBB',
        'cake' => '#E3D2BA'
    );

    if (is_dir($dir)) {
        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {

                if (preg_match("/^bootstrap-theme-([a-zA-Z0-9_]{1,30}).css$/", $file, $match)) {
                    $icon = $color[$match[1]];

                    $file = str_replace(array('.css', 'bootstrap-theme-'), '', $file);

                    if ($skin == $file)
                        $sel = "selected";
                    else
                        $sel = "";

                    if ($file != "." and $file != ".." and !strpos($file, '.'))
                        $value[] = array($file, $file, $sel, 'data-content="<span class=\'glyphicon glyphicon-text-background\' style=\'color:' . $icon . '\'></span> ' . $file . '"');
                }
            }
            closedir($dh);
        }
    }

    return $PHPShopGUI->setSelect('option[theme]', $value, 200, null, false, false, false, 1, false, 'theme_new');
}

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $TitlePage, $PHPShopOrm;

    PHPShopObj::loadClass('valuta');
    PHPShopObj::loadClass('user');


    // Выборка
    $data = $PHPShopOrm->select();
    $option = unserialize($data['admoption']);

    // Валюты
    $PHPShopValutaArray = new PHPShopValutaArray();
    $valuta_array = $PHPShopValutaArray->getArray();
    if (is_array($valuta_array))
        foreach ($valuta_array as $val) {
            $dengi_value[] = array($val['name'], $val['id'], $data['dengi']);
            $kurs_value[] = array($val['name'], $val['id'], $data['kurs']);
            $kurs_beznal_value[] = array($val['name'], $val['id'], $data['kurs_beznal']);
        }


    // Статусы
    $PHPShopUserStatusArray = new PHPShopUserStatusArray();
    $userstatus_array = $PHPShopUserStatusArray->getArray();

    $userstatus_value[] = array(__('Авторизованный пользователь'), 0, $option['user_status']);
    if (is_array($userstatus_array))
        foreach ($userstatus_array as $val) {
            $userstatus_value[] = array($val['name'], $val['id'], $option['user_status']);
        }

    // Размер названия поля
    $PHPShopGUI->field_col = 3;
    $PHPShopGUI->addJSFiles('./js/jquery.waypoints.min.js', './system/gui/system.gui.js');
    $PHPShopGUI->setActionPanel($TitlePage, false, array('Сохранить'));

    $num_vitrina_value[] = array(1, 1, $data['num_vitrina']);
    $num_vitrina_value[] = array(2, 2, $data['num_vitrina']);
    $num_vitrina_value[] = array(3, 3, $data['num_vitrina']);
    $num_vitrina_value[] = array(4, 4, $data['num_vitrina']);

    $nowbuy_enabled_value[] = array('Выключить', 0, $data['nowbuy_enabled']);
    $nowbuy_enabled_value[] = array('Компактный список', 1, $data['nowbuy_enabled']);
    $nowbuy_enabled_value[] = array('Сетка', 2, $data['nowbuy_enabled']);

    $sklad_status_value[] = array('Не используется', 1, $option['sklad_status']);
    $sklad_status_value[] = array('Товар убирается с продаж', 2, $option['sklad_status']);
    $sklad_status_value[] = array('Товар ставится под заказ', 3, $option['sklad_status']);

    $search_enabled_value[] = array('Искать в учебнике', 2, $option['search_enabled']);
    $search_enabled_value[] = array('Искать в товарах', 3, $option['search_enabled']);
    $search_enabled_value[] = array('Не используется', 1, $option['search_enabled']);

    // Содержание закладки 1
    $PHPShopGUI->_CODE = '<p></p>' . $PHPShopGUI->setField("Общая пагинация", $PHPShopGUI->setInputText(false, 'num_row_new', $data['num_row'], 50), 1, 'Количество позиций на одной странице в магазине') .
            $PHPShopGUI->setField("Количество в Спецпредложениях", $PHPShopGUI->setInputText(false, 'spec_num_new', $data['spec_num'], 50)) .
            $PHPShopGUI->setField("Количество в Новинках", $PHPShopGUI->setInputText(false, 'new_num_new', $data['new_num'], 50)) .
            $PHPShopGUI->setField("Товарная сетка витрины", $PHPShopGUI->setSelect('num_vitrina_new', $num_vitrina_value, 50), 1, 'Товаров в длину 
	  для витрины главной страницы') .
            $PHPShopGUI->setField("Сейчас покупают", $PHPShopGUI->setSelect('option[nowbuy_enabled]', $nowbuy_enabled_value)) .
            $PHPShopGUI->setField("Календарь новостей", $PHPShopGUI->setCheckbox('option[user_calendar]', 1, 'Cортировки новостей по датам', $option['user_calendar'])) .
            $PHPShopGUI->setField("Облако тегов", $PHPShopGUI->setCheckbox('option[cloud_enabled]', 1, 'Сортировка товаров по ключевым тегам', $option['cloud_enabled'])) .
            $PHPShopGUI->setField("Цифровые товары", $PHPShopGUI->setCheckbox('option[digital_product_enabled]', 1, 'Продажа цифровых товаров', $option['digital_product_enabled']), 1, 'Прикрепленные к товару файлы доступны после оплаты заказа в личном кабинете');

    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('Настройка цен', $PHPShopGUI->setField("Валюта по умолчанию", $PHPShopGUI->setSelect('dengi_new', $dengi_value)) .
            $PHPShopGUI->setField("Валюта в счете", $PHPShopGUI->setSelect('kurs_new', $kurs_value)) .
            $PHPShopGUI->setField("Валюта для безнала", $PHPShopGUI->setSelect('kurs_beznal_new', $kurs_beznal_value)) .
            $PHPShopGUI->setField("Накрутка цены", $PHPShopGUI->setInputText(false, 'percent_new', $data['percent'], 100, '%')) .
            $PHPShopGUI->setField("НДС", $PHPShopGUI->setCheckbox('nds_enabled_new', 1, 'Учитывать НДС в счете', $data['nds_enabled'])) .
            $PHPShopGUI->setField("Значение НДС", $PHPShopGUI->setInputText(false, 'nds_new', $data['nds'], 100, '%')) .
            $PHPShopGUI->setField("Склад", $PHPShopGUI->setCheckbox('option[sklad_enabled]', 1, 'Показывать значение склада у товара', $option['sklad_enabled'])) .
            $PHPShopGUI->setField("Округление цен", $PHPShopGUI->setInputText(false, 'option[price_znak]', $option['price_znak'], 50), 1, 'Количество знаков после запятой в цене') .
            $PHPShopGUI->setField("Минимальная сумма заказа", $PHPShopGUI->setInputText(false, 'option[cart_minimum]', $option['cart_minimum'], 100)) .
            $PHPShopGUI->setField("Контроль склада", $PHPShopGUI->setSelect('option[sklad_status]', $sklad_status_value)) .
            $PHPShopGUI->setField("Подтипы", $PHPShopGUI->setCheckbox('option[parent_price_enabled]', 1, 'Показывать цену и корзину у ведущего товара в подтипах', $option['parent_price_enabled']))
    );

    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('Настройка дизайна', $PHPShopGUI->setField('Дизайн', GetSkinList($data['skin']) . '<br>' . $PHPShopGUI->setCheckbox('option[user_skin]', 1, 'Смены дизайна пользователями', $option["user_skin"]), 1, 'Дизайн шаблон сайта (front-end)') . $PHPShopGUI->setField("Логотип", $PHPShopGUI->setIcon($data['logo'], "logo_new", false), 1, 'Используется в шапке дизайна и печатных документах'));

    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('Настройка e-mail уведомлений', $PHPShopGUI->setField(__("E-mail оповещение"), $PHPShopGUI->setInputText(null, "adminmail2_new", $data['adminmail2'], 300), 1, 'Для использования сторонних SMTP сервисов адрес должен совпадать с пользователем SMTP') .
            $PHPShopGUI->setField("SMTP", $PHPShopGUI->setCheckbox('option[mail_smtp_enabled]', 1, 'Отправка почты через SMTP протокол', $option['mail_smtp_enabled']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[mail_smtp_debug]', 1, 'Включить отладочные сообщения (Debug)', $option['mail_smtp_debug']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[mail_smtp_auth]', 1, 'Авторизация на сервере SMTP', $option['mail_smtp_auth'])
            ) .
            $PHPShopGUI->setField(__("Почтовый сервер SMTP"), $PHPShopGUI->setInputText(null, "option[mail_smtp_host]", $option['mail_smtp_host'], 300, false, false, false, 'smtp.yandex.ru'), 1, 'Сервер исходяшей почты SMTP') .
            $PHPShopGUI->setField(__("Порт сервера"), $PHPShopGUI->setInputText(null, "option[mail_smtp_port]", $option['mail_smtp_port'], 100, false, false, false, '25'), 1, 'Порт почтового SMTP сервера') .
            $PHPShopGUI->setField(__("Пользователь"), $PHPShopGUI->setInputText(null, "option[mail_smtp_user]", $option['mail_smtp_user'], 300, false, false, false, 'user@yandex.ru')) .
            $PHPShopGUI->setField(__("Пароль"), $PHPShopGUI->setInput('password', "option[mail_smtp_pass]", $option['mail_smtp_pass'], null, 300)) .
            $PHPShopGUI->setField(__("Обратный адрес"), $PHPShopGUI->setInputText(null, "option[mail_smtp_replyto]", $option['mail_smtp_replyto'], 300), 1, 'Ответы на почтовые сообщения будут приходить на этот адрес')
    );

    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('Настройка sms уведомлений', $PHPShopGUI->setField("SMS оповещение", $PHPShopGUI->setCheckbox('option[sms_enabled]', 1, 'Уведомление о заказе администратору', $option['sms_enabled']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[sms_status_order_enabled]', 1, 'Уведомление о статусе заказа пользователю', $option['sms_status_order_enabled']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[notice_enabled]', 1, 'Уведомление о наличии товара пользователям', $option['notice_enabled']), 1, 'Используется сервис terasms.ru'
            ) .
            $PHPShopGUI->setField(__("Мобильный телефон"), $PHPShopGUI->setInputText(null, "option[sms_phone]", $option['sms_phone'], 300), 1, 'Телефон для SMS уведомлений формата 792612345678') .
            $PHPShopGUI->setField(__("Пользователь"), $PHPShopGUI->setInputText(null, "option[sms_user]", $option['sms_user'], 300), 1, 'Пользователь в системе terasms.ru') .
            $PHPShopGUI->setField(__("Пароль"), $PHPShopGUI->setInput('password', "option[sms_pass]", $option['sms_pass'], null, 300), 1, 'Пароль в системе terasms.ru') .
            $PHPShopGUI->setField(__("Имя отправителя"), $PHPShopGUI->setInputText(null, "option[sms_name]", $option['sms_name'], 300), 1, 'Зарегистрированное имя отправителя в terasms.ru')
    );

    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('Настройка Пользователей', $PHPShopGUI->setField("Регистрация пользователей", $PHPShopGUI->setCheckbox('option[user_mail_activate]', 1, 'Активация через E-mail', $option['user_mail_activate']) . '<br>' . $PHPShopGUI->setCheckbox('option[user_mail_activate_pre]', 1, 'Ручная активация администратором', $option['user_mail_activate_pre']) . '<br>' . $PHPShopGUI->setCheckbox('option[user_price_activate]', 1, 'Регистрация для просмотра цен', $option['user_price_activate'])) . $PHPShopGUI->setField("Статус после регистрации", $PHPShopGUI->setSelect('option[user_status]', $userstatus_value)));

    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('Настройка управления', $PHPShopGUI->setField('Дизайн', GetAdminSkinList($option['theme']), 1, 'Цветовая схема оформления панели управления (back-end)') .
            $PHPShopGUI->setField("HTML-редактор по умолчанию", GetEditors($option['editor']), 1, 'Визуальный редактор контента') .
            $PHPShopGUI->setField("Цвет редактора шаблонов", GetAceSkinList($option['ace_theme']), 1, 'Стилизованная подсветка синтаксиса кода шаблонов') .
            $PHPShopGUI->setField(__("Заголовок"), $PHPShopGUI->setInputText(null, "option[adm_title]", $option['adm_title'], 300), 1, 'Брендовый заголовок в левом верхнем углу панели управления') .
            $PHPShopGUI->setField("RSS", $PHPShopGUI->setCheckbox('option[rss_graber_enabled]', 1, 'Создавать новости из RSS каналов', $option['rss_graber_enabled'])) .
            $PHPShopGUI->setField("Multi Manager", $PHPShopGUI->setCheckbox('option[rule_enabled]', 1, 'Учет прав управления товарами для менеджеров', $option['rule_enabled'])) .
            $PHPShopGUI->setField("Быстрый поиск", $PHPShopGUI->setSelect('option[search_enabled]', $search_enabled_value), 1, 'Поиск в верхнем правом углу панели управления (back-end)')
    );

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);


    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.system.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.system.edit");

    $PHPShopGUI->setFooter($ContentFooter);

    $sidebarleft[] = array('title' => 'Категории', 'content' => $PHPShopGUI->loadLib('tab_menu', false, './system/'));
    $PHPShopGUI->setSidebarLeft($sidebarleft, 2);

    // Футер
    $PHPShopGUI->Compile(2);
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

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;

    // Выборка
    $data = $PHPShopOrm->select();
    $option = unserialize($data['admoption']);

    // Счетчик сообщений о поддержке
    unset($option['support_notice']);

    // Корректировка пустых значений
    $PHPShopOrm->updateZeroVars('option.user_calendar', 'option.cloud_enabled', 'option.digital_product_enabled', 'option.parent_price_enabled', 'option.user_skin', 'option.sms_enabled', 'option.sms_status_order_enabled', 'option.notice_enabled', 'option.user_mail_activate', 'option.user_mail_activate_pre', 'option.user_price_activate', 'option.rss_graber_enabled', 'option.mail_smtp_enabled', 'option.mail_smtp_debug', 'option.mail_smtp_auth', 'option.sklad_enabled','option.rule_enabled');

    if (is_array($_POST['option']))
        foreach ($_POST['option'] as $key => $val)
            $option[$key] = $val;

    // Смена шаблона на front-end
    if ($data['skin'] != $_POST['skin_new'] and PHPShopSecurity::true_skin($_POST['skin_new']))
        $_SESSION['skin'] = $_POST['skin_new'];


    $_POST['admoption_new'] = serialize($option);

    $_POST['nds_enabled_new'] = $_POST['nds_enabled_new'] ? 1 : 0;
    $_POST['nds_enabled_new'] = $_POST['nds_enabled_new'] ? 1 : 0;


    // Логотип
    $_POST['logo_new'] = iconAdd('logo_new');

    //$PHPShopOrm->debug=true;
    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));


    return array("success" => $action);
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

    if (empty($file))
        $file = '';

    return $file;
}

// Обработка событий
$PHPShopGUI->getAction();
?>