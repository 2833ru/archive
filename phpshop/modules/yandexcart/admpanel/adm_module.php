<?php

PHPShopObj::loadClass("delivery");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("category");

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.yandexcart.yandexcart_system"));

// Обновление версии модуля
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate(number_format($option['version'], 1, '.', false));
    $PHPShopOrm->clean();
    $PHPShopOrm->update(array('version_new' => $new_version));
}

// Построение дерева категорий
function treegenerator($array, $i, $curent, $dop_cat_array) {
    global $tree_array;
    $del = '&brvbar;&nbsp;&nbsp;&nbsp;&nbsp;';
    $tree_select = $tree_select_dop = $check = false;

    $del = str_repeat($del, $i);
    if (is_array($array['sub'])) {
        foreach ($array['sub'] as $k => $v) {

            $check = treegenerator($tree_array[$k], $i + 1, $k, $dop_cat_array);

            $selected = null;
            $disabled = null;

            if (is_array($dop_cat_array))
                foreach ($dop_cat_array as $vs) {
                    if ($k == $vs)
                        $selected = "selected";
                }

            if (empty($check['select'])) {
                $tree_select .= '<option value="' . $k . '" ' . $selected . $disabled . '>' . $del . $v . '</option>';

                $i = 1;
            } else {
                $tree_select .= '<option value="' . $k . '" ' . $selected . ' disabled>' . $del . $v . '</option>';
            }

            $tree_select .= $check['select'];
        }
    }
    return array('select' => $tree_select);
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $TitlePage, $select_name, $PHPShopSystem;

    $PHPShopGUI->field_col = 5;
    PHPShopObj::loadClass("order");

    // Выборка
    $data = $PHPShopOrm->select();

    $options = unserialize($data['options']);

    $PHPShopGUI->addJSFiles('../modules/yandexcart/admpanel/gui/yandexcart.gui.js');

    if ($data['model'] === 'FBS') {
        $PHPShopGUI->action_button['Экспортировать данные'] = [
            'name' => 'Экспортировать данные',
            'action' => 'ymImportProducts',
            'class' => 'btn btn-default btn-sm navbar-btn',
            'type' => 'submit',
            'icon' => 'glyphicon glyphicon-export'
        ];

        $PHPShopGUI->addJSFiles('../modules/yandexcart/admpanel/gui/script.gui.js?v=2.9');
        $PHPShopGUI->setActionPanel($TitlePage, $select_name, ['Экспортировать данные', 'Сохранить и закрыть']);
    }

    if ($data['model'] === 'FBS' || $data['model'] === 'DBS') {
        isset($options['statuses']) && is_array($options['statuses']) ? $statuses = $options['statuses'] : $statuses = [];
        isset($options['payments']) && is_array($options['payments']) ? $payments = $options['payments'] : $payments = [];

        // Доставка
        $PHPShopDeliveryArray = new PHPShopDeliveryArray(array('is_folder' => "!='1'", 'enabled' => "='1'"));
        $DeliveryArray = $PHPShopDeliveryArray->getArray();
        if (is_array($DeliveryArray)) {
            foreach ($DeliveryArray as $delivery) {
                if (strpos($delivery['city'], '.')) {
                    $name = explode(".", $delivery['city']);
                    $delivery['city'] = $name[0];
                }
                $delivery_value[] = array($delivery['city'], $delivery['id'], $data['delivery_id']);
            }
        }
    }

    $models = [
        ['FBS (продажи с фулфилментом магазина)', 'FBS', $data['model']],
        ['DBS (продажи с доставкой магазина)', 'DBS', $data['model']],
        ['ADV (реклама товарных предложений)', 'ADV', $data['model']]
    ];

    if (empty($_SESSION['mod_pro']))
        $models = [
            ['FBS (доступна в версии Pro)', 'ADV', $data['model']],
            ['DBS (доступна в версии Pro)', 'ADV', $data['model']],
            ['ADV (реклама товарных предложений)', 'ADV', $data['model']],
        ];

    $Tab1 = $PHPShopGUI->setField('Модель работы', $PHPShopGUI->setSelect('model_new', $models, '100%', true));

    if ($data['model'] === 'ADV' || $data['model'] === 'DBS') {
        $Tab1 .= $PHPShopGUI->setField('Пароль защиты файла', $PHPShopGUI->setInputText('http://' . $_SERVER['SERVER_NAME'] . '/yml/?pas=', 'password_new', $data['password']));
        $Tab1 .= $PHPShopGUI->setField('Вывод характеристик', $PHPShopGUI->setCheckbox('use_params_new', 1, 'Включить вывод характеристик в YML', $data['use_params']));
    }

    if ($data['model'] === 'FBS' || $data['model'] === 'DBS') {
        $Tab1 .= $PHPShopGUI->setField('Идентификатор кампании', $PHPShopGUI->setInputText('xx-', 'campaign_id_new', $data['campaign_id']));
        $Tab1 .= $PHPShopGUI->setField('Авторизационный токен API', $PHPShopGUI->setInputText(null, 'auth_token_new', $data['auth_token']));
        $Tab1 .= $PHPShopGUI->setField('ID приложения Яндекс.OAuth', $PHPShopGUI->setInputText(null, 'client_id_new', $data['client_id']));
        $Tab1 .= $PHPShopGUI->setField('OAuth-токен', $PHPShopGUI->setInputText(null, 'client_token_new', $data['client_token'],false,'<a target="_blank" href="https://oauth.yandex.ru/authorize?response_type=token&client_id=" id="client_token">' . __('Получить') . '</a>'));
        $Tab1 .= $PHPShopGUI->setField('Блокировать прием заказов', $PHPShopGUI->setCheckbox('stop_new', 1, null, $data['stop']));
        $Tab1 .= $PHPShopGUI->setField('Выгружать изображения', $PHPShopGUI->setCheckbox('options[block_image]', 1, null, $options['block_image']));
        $Tab1 .= $PHPShopGUI->setField('Выгружать описание', $PHPShopGUI->setCheckbox('options[block_content]', 1, null, $options['block_content']));

        if ($data['model'] === 'FBS') {
            $Tab1 .= $PHPShopGUI->setField('Доставка для заказов с Маркета', $PHPShopGUI->setSelect('delivery_id_new', $delivery_value, 300, null));
            if ((int) $data['import_from'] > 0) {
                $fromCaption = 'У вас больше 5 000 товаров выгружаются в Яндекс.Маркет. Процесс экспорта разбит на несколько шагов. 
            Каждый новый запуск экспорта товаров продолжает выполнение с указанного смещения. Если вам необходимо начать экспорт сначала, 
            установите значение 0, иначе рекомендуется не изменять это значение.';
                $Tab1 .= $PHPShopGUI->setField('Продолжить импорт с', $PHPShopGUI->setInputText(null, 'import_from_new', $data['import_from'], 100), 1, $fromCaption);
            }
        }
    }

    $PHPShopCategoryArray = new PHPShopCategoryArray($where);
    $CategoryArray = $PHPShopCategoryArray->getArray();

    if (is_array($CategoryArray))
        $GLOBALS['count'] = count($CategoryArray);

    $tree_array = array();

    foreach ($PHPShopCategoryArray->getKey('parent_to.id', true) as $k => $v) {
        foreach ($v as $cat) {
            $tree_array[$k]['sub'][$cat] = $CategoryArray[$cat]['name'];
        }
        $tree_array[$k]['name'] = $CategoryArray[$k]['name'];
        $tree_array[$k]['id'] = $k;
        if ($k == $data['parent_to'])
            $tree_array[$k]['selected'] = true;
    }

    $GLOBALS['tree_array'] = &$tree_array;

    // Допкаталоги
    $dop_cat_array = preg_split('/,/', $data['categories'], -1, PREG_SPLIT_NO_EMPTY);

    if (is_array($tree_array[0]['sub']))
        foreach ($tree_array[0]['sub'] as $k => $v) {
            $check = treegenerator($tree_array[$k], 1, $k, $dop_cat_array);

            // Допкаталоги
            $selected = null;
            if (is_array($dop_cat_array))
                foreach ($dop_cat_array as $vs) {
                    if ($k == $vs)
                        $selected = "selected";
                }


            if (empty($tree_array[$k]))
                $disabled = null;
            else
                $disabled = ' disabled';

            $tree_select .= '<option value="' . $k . '"  ' . $selected . $disabled . '>' . $v . '</option>';

            $tree_select .= $check['select'];
        }


    $tree_select = '<select class="selectpicker show-menu-arrow hidden-edit" data-live-search="true" data-container="body"  data-style="btn btn-default btn-sm" name="categories[]"  data-width="100%" multiple>' . $tree_select . '</select>';

    // Выбор каталога
    $catOption = $PHPShopGUI->setField("Размещение", $tree_select . $PHPShopGUI->setCheckbox("categories_all", 1, "Выбрать все категории?", 0), 1, 'Пакетное редактирование. Настройка не сохраняется.');
    $catOption .= $PHPShopGUI->setField("Вывод в Яндекс.Маркете", $PHPShopGUI->setRadio("enabled_all", 1, "Вкл.", 1) . $PHPShopGUI->setRadio("enabled_all", 0, "Выкл.", 1));

    $Tab1 .= $PHPShopGUI->setField('Шаблон генерации описания', '<div id="yandexDescriptionShablon">
<textarea class="form-control yandex-shablon" name="description_template_new" rows="3" style="width: 100%;height: 70px;">' . $data['description_template'] . '</textarea>
    <div class="btn-group" role="group" aria-label="...">
    <input  type="button" value="' . __('Описание') . '" onclick="yandexShablonAdd(\'@Content@\')" class="btn btn-default btn-sm">
    <input  type="button" value="' . __('Краткое описание') . '" onclick="yandexShablonAdd(\'@Description@\')" class="btn btn-default btn-sm">
    <input  type="button" value="' . __('Характеристики') . '" onclick="yandexShablonAdd(\'@Attributes@\')" class="btn btn-default btn-sm">
<input  type="button" value="' . __('Каталог') . '" onclick="yandexShablonAdd(\'@Catalog@\')" class="btn btn-default btn-sm">
<input  type="button" value="' . __('Подкаталог') . '" onclick="yandexShablonAdd(\'@Subcatalog@\')" class="btn btn-default btn-sm">
<input  type="button" value="' . __('Товар') . '" onclick="yandexShablonAdd(\'@Product@\',)" class="btn btn-default btn-sm">
    </div>
</div>
<script>function yandexShablonAdd(variable) {
    var shablon = $(".yandex-shablon").val() + " " + variable;
    $(".yandex-shablon").val(shablon);
}</script>', 1, 'Характеристики в описании создают дополнительную нагрузку. Рекомендуется использовать только для вывода небольшого количества товаров.');


    $Tab1 = $PHPShopGUI->setCollapse('Информация', $Tab1);
    $Tab1 .= $PHPShopGUI->setCollapse('Товары', $catOption);

    $priceOption = $PHPShopGUI->setField('Колонка цен Яндекс.Маркет', $PHPShopGUI->setSelect('options[price]', $PHPShopGUI->setSelectValue($options['price'], 5), 100)) .
            $PHPShopGUI->setField('Наценка', $PHPShopGUI->setInputText(null, 'options[price_fee]', $options['price_fee'], 100, '%')) .
            $PHPShopGUI->setField(null, $PHPShopGUI->setInputText(null, 'options[price_markup]', $options['price_markup'], 100, $PHPShopSystem->getDefaultValutaCode()));

    if ($data['model'] === 'DBS')
        $priceOption .= $PHPShopGUI->setField('Колонка цен Яндекс.Маркет DBS', $PHPShopGUI->setSelect('options[price_dbs]', $PHPShopGUI->setSelectValue($options['price_dbs'], 5), 100)) .
                $PHPShopGUI->setField('Наценка DBS', $PHPShopGUI->setInputText(null, 'options[price_dbs_fee]', $options['price_dbs_fee'], 100, '%'));


    $Tab1 .= $PHPShopGUI->setCollapse('Настройка цен', $priceOption);

    if (empty($data['model'])) {
        $Tab1 .= sprintf('<div class="alert alert-info" role="alert">%s</div>', __('Выберите Модель работы и нажмите "Сохранить" для доступа к настройкам.'));
    }

    if ($data['model'] === 'FBS' or $data['model'] === 'DBS') {

        // Доступые статусы заказов
        $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
        $OrderStatusArray = $PHPShopOrderStatusArray->getArray();

        if (is_array($OrderStatusArray))
            foreach ($OrderStatusArray as $status) {
                $status_delivered_value[] = [
                    $status['name'], $status['id'], isset($statuses['delivered']) ? $statuses['delivered'] : null
                ];
                $status_delivery_value[] = [
                    $status['name'], $status['id'], isset($statuses['delivery']) ? $statuses['delivery'] : null
                ];
                $status_pickup_value[] = [
                    $status['name'], $status['id'], isset($statuses['pickup']) ? $statuses['pickup'] : null
                ];
                $status_unpaid_value[] = [
                    $status['name'], $status['id'], isset($statuses['unpaid']) ? $statuses['unpaid'] : null
                ];
                $status_started_value[] = [
                    $status['name'], $status['id'], isset($statuses['processing_started']) ? $statuses['processing_started'] : null
                ];
                $status_delivery_service_undelivered_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_delivery_service_undelivered']) ? $statuses['cancelled_delivery_service_undelivered'] : null
                ];
                $status_processing_expired_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_processing_expired']) ? $statuses['cancelled_processing_expired'] : null
                ];
                $status_replacing_order_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_replacing_order']) ? $statuses['cancelled_replacing_order'] : null
                ];
                $status_reservation_expired_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_reservation_expired']) ? $statuses['cancelled_reservation_expired'] : null
                ];
                $status_reservation_failed_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_reservation_failed']) ? $statuses['cancelled_reservation_failed'] : null
                ];
                $status_shop_failed_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_shop_failed']) ? $statuses['cancelled_shop_failed'] : null
                ];
                $status_user_changed_mind_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_user_changed_mind']) ? $statuses['cancelled_user_changed_mind'] : null
                ];
                $status_user_not_paid_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_user_not_paid']) ? $statuses['cancelled_user_not_paid'] : null
                ];
                $status_user_refused_delivery_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_user_refused_delivery']) ? $statuses['cancelled_user_refused_delivery'] : null
                ];
                $status_user_refused_product_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_refused_product']) ? $statuses['cancelled_refused_product'] : null
                ];
                $status_user_refused_quality_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_refused_quality']) ? $statuses['cancelled_refused_quality'] : null
                ];
                $status_user_unreachable_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_unreachable']) ? $statuses['cancelled_unreachable'] : null
                ];
            }

        // Способы оплаты
        $paymentOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['payment_systems']);
        $paymentsArr = $paymentOrm->getList();

        foreach ($paymentsArr as $payment) {
            $payment_yandex_value[] = [
                $payment['name'], $payment['id'], isset($payments['yandex']) ? $payments['yandex'] : null
            ];
            $payment_apple_pay_value[] = [
                $payment['name'], $payment['id'], isset($payments['apple_pay']) ? $payments['apple_pay'] : null
            ];
            $payment_google_pay_value[] = [
                $payment['name'], $payment['id'], isset($payments['google_pay']) ? $payments['google_pay'] : null
            ];
            $payment_credit_value[] = [
                $payment['name'], $payment['id'], isset($payments['credit']) ? $payments['credit'] : null
            ];
            $payment_certificate_value[] = [
                $payment['name'], $payment['id'], isset($payments['certificate']) ? $payments['certificate'] : null
            ];
            $payment_card_on_delivery_value[] = [
                $payment['name'], $payment['id'], isset($payments['card_on_delivery']) ? $payments['card_on_delivery'] : null
            ];
            $payment_cash_on_delivery_value[] = [
                $payment['name'], $payment['id'], isset($payments['cash_on_delivery']) ? $payments['cash_on_delivery'] : null
            ];
        }

        $Tab1 .= $PHPShopGUI->setCollapse('Способы оплаты', $PHPShopGUI->setField('Банковской картой при оформлении заказа', $PHPShopGUI->setSelect('payments[yandex]', $payment_yandex_value)
                ) .
                $PHPShopGUI->setField('Apple Pay', $PHPShopGUI->setSelect('payments[apple_pay]', $payment_apple_pay_value)
                ) .
                $PHPShopGUI->setField('Google Pay', $PHPShopGUI->setSelect('payments[google_pay]', $payment_google_pay_value)
                ) .
                $PHPShopGUI->setField('В кредит', $PHPShopGUI->setSelect('payments[credit]', $payment_credit_value)
                ) .
                $PHPShopGUI->setField('Подарочный сертификат', $PHPShopGUI->setSelect('payments[certificate]', $payment_certificate_value)
                ) .
                $PHPShopGUI->setField('Банковской картой при получении заказа', $PHPShopGUI->setSelect('payments[card_on_delivery]', $payment_card_on_delivery_value)
                ) .
                $PHPShopGUI->setField('Наличными', $PHPShopGUI->setSelect('payments[cash_on_delivery]', $payment_cash_on_delivery_value)
                ), null);

        // Статус заказа
        $Tab1 .= $PHPShopGUI->setCollapse('Статусы заказа', $PHPShopGUI->setField('Статус доставлен', $PHPShopGUI->setSelect('statuses[delivered]', $status_delivered_value), 1, 'DELIVERED'
                ) .
                $PHPShopGUI->setField('Статус передан в службу доставки', $PHPShopGUI->setSelect('statuses[delivery]', $status_delivery_value), 1, 'DELIVERY'
                ) .
                $PHPShopGUI->setField('Заказ доставлен в пункт самовывоза', $PHPShopGUI->setSelect('statuses[pickup]', $status_pickup_value), 1, 'PICKUP'
                ) .
                $PHPShopGUI->setField('Заказ оформлен, но еще не оплачен', $PHPShopGUI->setSelect('statuses[unpaid]', $status_unpaid_value), 1, 'UNPAID'
                ) .
                $PHPShopGUI->setField('Заказ подтвержден, его можно начать обрабатывать', $PHPShopGUI->setSelect('statuses[processing_started]', $status_started_value), 1, 'PROCESSING STARTED'
                ) .
                $PHPShopGUI->setField('Служба доставки не смогла доставить заказ', $PHPShopGUI->setSelect('statuses[cancelled_delivery_service_undelivered]', $status_delivery_service_undelivered_value), 1, 'CANCELLED DELIVERY_SERVICE_UNDELIVERED'
                ) .
                $PHPShopGUI->setField('Магазин не обработал заказ в течение семи дней', $PHPShopGUI->setSelect('statuses[cancelled_processing_expired]', $status_processing_expired_value), 1, 'CANCELLED PROCESSING_EXPIRED'
                ) .
                $PHPShopGUI->setField('Покупатель решил заменить товар другим по собственной инициативе', $PHPShopGUI->setSelect('statuses[cancelled_replacing_order]', $status_replacing_order_value), 1, 'CANCELLED REPLACING_ORDER'
                ) .
                $PHPShopGUI->setField('Покупатель не завершил оформление зарезервированного заказа в течение 10 минут', $PHPShopGUI->setSelect('statuses[cancelled_reservation_expired]', $status_reservation_expired_value), 1, 'CANCELLED RESERVATION_EXPIRED'
                ) .
                $PHPShopGUI->setField('Магазин не подтвердил, что готов принять заказ', $PHPShopGUI->setSelect('statuses[cancelled_reservation_failed]', $status_reservation_failed_value), 1, 'CANCELLED RESERVATION_FAILED'
                ) .
                $PHPShopGUI->setField('Магазин не может выполнить заказ', $PHPShopGUI->setSelect('statuses[cancelled_shop_failed]', $status_shop_failed_value), 1, 'CANCELLED SHOP_FAILED'
                ) .
                $PHPShopGUI->setField('Покупатель отменил заказ по собственным причинам', $PHPShopGUI->setSelect('statuses[cancelled_user_changed_mind]', $status_user_changed_mind_value), 1, 'CANCELLED USER_CHANGED_MIND'
                ) .
                $PHPShopGUI->setField('Покупатель не оплатил заказ (для типа оплаты PREPAID) в течение двух часов', $PHPShopGUI->setSelect('statuses[cancelled_user_not_paid]', $status_user_not_paid_value), 1, 'CANCELLED USER_NOT_PAID'
                ) .
                $PHPShopGUI->setField('Покупателя не устраивают условия доставки', $PHPShopGUI->setSelect('statuses[cancelled_user_refused_delivery]', $status_user_refused_delivery_value), 1, 'CANCELLED USER_REFUSED_DELIVERY'
                ) .
                $PHPShopGUI->setField('Покупателю не подошел товар', $PHPShopGUI->setSelect('statuses[cancelled_refused_product]', $status_user_refused_product_value), 1, 'CANCELLED USER_REFUSED_PRODUCT'
                ) .
                $PHPShopGUI->setField('Покупателя не устраивает качество товара', $PHPShopGUI->setSelect('statuses[cancelled_refused_quality]', $status_user_refused_quality_value), 1, 'CANCELLED USER_REFUSED_QUALITY'
                ) .
                $PHPShopGUI->setField('Не удалось связаться с покупателем', $PHPShopGUI->setSelect('statuses[cancelled_unreachable]', $status_user_unreachable_value), 1, 'CANCELLED USER_UNREACHABLE'
                ), null);
    }

    // Инструкция
    $Tab2 = $PHPShopGUI->loadLib('tab_info', $data, '../modules/' . $_GET['id'] . '/admpanel/');

    $Tab3 = $PHPShopGUI->setPay(false, false, $data['version'], true);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true, false, true), array("Инструкция", $Tab2), array("О Модуле", $Tab3));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;

    // Настройки витрины
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);
    $_POST['options']['statuses'] = $_POST['statuses'];
    $_POST['options']['payments'] = $_POST['payments'];

    $_POST['options_new'] = serialize($_POST['options']);
    $PHPShopOrm->debug = false;
    $_POST['region_data_new'] = 1;
    if (empty($_POST["use_params_new"]))
        $_POST["use_params_new"] = 0;
    if (empty($_POST["stop_new"]))
        $_POST["stop_new"] = 0;

    // Категории
    if (is_array($_POST['categories']) and $_POST['categories'][0] != 'null') {

        foreach ($_POST['categories'] as $v)
            if (!empty($v) and ! strstr($v, ','))
                $cat_array[] = $v;

        if (is_array($cat_array)) {
            $where = array('category' => ' IN ("' . implode('","', $cat_array) . '")');
            $PHPShopOrmProducts = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
            $PHPShopOrmProducts->debug = false;
            $PHPShopOrmProducts->update(array('yml_new' => intval($_POST['enabled_all'])), $where);
        }
    }

    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&id=' . $_GET['id']);
    return $action;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>