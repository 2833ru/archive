<?php

include($_classPath . "modules/retailcrm/class/Tools.php");
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.retailcrm.retailcrm_system"));

function actionUpdate() {
    global $PHPShopOrm;
    $post = Tools::clearArray($_POST);
    if ('/' != substr($post["siteurl"], strlen($post["siteurl"]) - 1, 1)) {
        $post["siteurl"] .= '/';
    }
    if ('/' != substr($post["url"], strlen($post["url"]) - 1, 1)) {
        $post["url"] .= '/';
    }
    $PHPShopOrm->sql = "update phpshop_modules_retailcrm_system set value='" . serialize($post) . "' where code='options'";
    $action = $PHPShopOrm->update();
    header('Location: ?path=modules&install=check');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    $data = $PHPShopOrm->select();

    $value = unserialize($data['value']);


    $help = '<p>Для автоматической синхронизации изменений с retailCRM требуется настроить планировщика команд <a href="https://ru.wikipedia.org/wiki/Cron" target="_blank">Cron</a> на своем хостинге.</p>
            <ol>
<li>Получение изменений по заказам из CRM: <code>*/7 * * * * /usr/bin/php /path/to/site/phpshop/modules/retailcrm/cli/cli.php -e history</code></li>
<li>Выгрузка каталога номеклатуры: <code>* */3 * * * /usr/bin/php /path/to/site/phpshop/modules/retailcrm/cli/cli.php -e icml</code></li>
</ol>';

    $tab1 = $PHPShopGUI->setField('Название магазина', $PHPShopGUI->setInputText(false, 'shopname', $value["shopname"], 500));
    $tab1.= $PHPShopGUI->setField('Компания', $PHPShopGUI->setInputText(false, 'companyname', $value["companyname"], 500));
    $tab1.= $PHPShopGUI->setField('URL сайта магазина', $PHPShopGUI->setInputArg(array('type' => 'text', 'name' => 'siteurl', 'value' => $value["siteurl"], 'size' => 500, 'placeholder' => 'http://' . $_SERVER['SERVER_NAME'])));

    $tab1 .= $PHPShopGUI->setField('API URL', $PHPShopGUI->setInputArg(array('type' => 'text', 'name' => 'url', 'value' => $value["url"], 'size' => 500, 'placeholder' => 'https://name.retailcrm.ru/')));
    $tab1 .= $PHPShopGUI->setField('API KEY', $PHPShopGUI->setInputText(false, 'key', $value["key"], 500));

    if (isset($value["url"]) && isset($value["key"]) && $helper = new ApiHelper($value["url"], $value["key"])) {
        $field1 = "";
        $tab2 = "";
        // Способы доставки
        try {
            $response = $helper->api->deliveryTypesList();
        } catch (CurlException $e) {
            Tools::logger("Сетевые проблемы. Ошибка подключения к retailCRM: " . $e->getMessage(), "connect");
        }

        $deliveryTypes[] = array("", "", false);
        if ($response->isSuccessful()) {
            foreach ($response->deliveryTypes as $code => $params) {
                $deliveryTypes[] = array(Tools::iconvArray($params["name"], "UTF-8", "WINDOWS-1251"), $params["code"], false);
            }
        } else {
            Tools::logger("Сетевые проблемы. Ошибка подключения к retailCRM: " . $e->getMessage(), "connect");
        }

        $deliveryOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['delivery']);
        $delivery = $deliveryOrm->select(array('*'), array('is_folder' => "='0'"));
        $deliveryOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['delivery']);
        $pdelivery = $deliveryOrm->select(array('*'), array('is_folder' => "='1'"));

        $parents = array();
        foreach ($pdelivery as $parent) {
            $parents[$parent["id"]]['title'] = $parent["city"];
            foreach ($delivery as $del) {
                if ($parent["id"] == $del["PID"]) {
                    $tmpDeliveryTypes = $deliveryTypes;
                    if (isset($value["delivery"][$del["id"]])) {
                        foreach ($tmpDeliveryTypes as $key => $val) {
                            if ($val[1] == $value["delivery"][$del["id"]]) {
                                $tmpDeliveryTypes[$key][2] = "selected";
                                break;
                            }
                        }
                    }

                    $tmp = "";
                    $tmp .= $PHPShopGUI->setDiv("left", $del["city"] . ":");
                    $tmp .= $PHPShopGUI->setSelect('delivery[' . $del["id"] . ']', $tmpDeliveryTypes) . "<br>";
                    $parents[$parent["id"]]["items"] .= $tmp;
                }
            }
            if (!isset($parents[$parent["id"]]["items"]) || count($parents[$parent["id"]]["items"]) < 1) {
                unset($parents[$parent["id"]]);
            }
        }

        foreach ($parents as $delivery) {
            $field1 .= $PHPShopGUI->setField($delivery["title"], $delivery["items"]);
        }

        //$tab2 .= $PHPShopGUI->setField('Способы доставки', $field1, "none", 0, true);
        $tab2 .= $PHPShopGUI->setCollapse('Способы доставки', $field1);

        // Способы оплаты
        try {
            $response = $helper->api->paymentTypesList();
        } catch (CurlException $e) {
            Tools::logger("Сетевые проблемы. Ошибка подключения к retailCRM: " . $e->getMessage(), "connect");
        }

        $paymentTypes[] = array("", "", false);
        if ($response->isSuccessful()) {
            foreach ($response->paymentTypes as $code => $params) {
                $paymentTypes[] = array(Tools::iconvArray($params["name"], "UTF-8", "WINDOWS-1251"), $params["code"], false);
            }
        } else {
            Tools::logger("Сетевые проблемы. Ошибка подключения к retailCRM: " . $e->getMessage(), "connect");
        }

        $paymentOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['payment_systems']);
        $payment = $paymentOrm->select(array('*'));

        $field2 = "";
        foreach ($payment as $paymentValue) {
            $tmpPaymentTypes = $paymentTypes;
            if (isset($value["payment"][$paymentValue["id"]])) {
                foreach ($tmpPaymentTypes as $key => $val) {
                    if ($val[1] == $value["payment"][$paymentValue["id"]]) {
                        $tmpPaymentTypes[$key][2] = "selected";
                        break;
                    }
                }
            }

            $field2 .= $PHPShopGUI->setField($paymentValue["name"], $PHPShopGUI->setSelect('payment[' . $paymentValue["id"] . ']', $tmpPaymentTypes));
        }

        $tab2 .= $PHPShopGUI->setCollapse('Способы оплаты', $field2);

        try {
            $response = $helper->api->statusesList();
        } catch (CurlException $e) {
            Tools::logger("Сетевые проблемы. Ошибка подключения к retailCRM: " . $e->getMessage(), "connect");
        }

        $statuses[] = array("", "", false);
        if ($response->isSuccessful()) {
            foreach ($response->statuses as $code => $params) {
                $statuses[] = array(Tools::iconvArray($params["name"], "UTF-8", "WINDOWS-1251"), $params["code"], false);
            }
        } else {
            Tools::logger("Сетевые проблемы. Ошибка подключения к retailCRM: " . $e->getMessage(), "connect");
        }

        $statusesOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['order_status']);
        $status = $statusesOrm->select(array('*'));

        $field3 = "";
        array_unshift($status, array("id" => "new", "name" => "Новый заказ"));
        foreach ($status as $statusValue) {
            $tmpStatuses = $statuses;
            if (isset($value["status"][$statusValue["id"]])) {
                foreach ($tmpStatuses as $key => $val) {
                    if ($val[1] == $value["status"][$statusValue["id"]]) {
                        $tmpStatuses[$key][2] = "selected";
                        break;
                    }
                }
            }

            $field3 .= $PHPShopGUI->setField($statusValue["name"], $PHPShopGUI->setSelect('status[' . $statusValue["id"] . ']', $tmpStatuses));
        }


        $tab2 .= $PHPShopGUI->setCollapse('Статусы', $field3);

        if (isset($GLOBALS['SysValue']['base']['oneclick'])) {
            $field3 = "";
            $status = array(
                array("id" => 1, "name" => "Новая"),
                array("id" => 2, "name" => "Просили перезвонить"),
                array("id" => 3, "name" => "Недоступен"),
                array("id" => 4, "name" => "Выполнен"),
            );
            foreach ($status as $statusValue) {
                $tmpStatuses = $statuses;
                if (isset($value["status-oneclick"][$statusValue["id"]])) {
                    foreach ($tmpStatuses as $key => $val) {
                        if ($val[1] == $value["status-oneclick"][$statusValue["id"]]) {
                            $tmpStatuses[$key][2] = "selected";
                            break;
                        }
                    }
                }

                $field3 .= $PHPShopGUI->setField($statusValue["name"], $PHPShopGUI->setSelect('status-oneclick[' . $statusValue["id"] . ']', $tmpStatuses));
            }

            $tab2 .= $PHPShopGUI->setCollapse('Статусы (заказ в один клик)', $field3);
        }

        if (isset($GLOBALS['SysValue']['base']['returncall'])) {
            $field3 = "";
            $status = array(
                array("id" => 1, "name" => "Новая"),
                array("id" => 2, "name" => "Просили перезвонить"),
                array("id" => 3, "name" => "Недоступен"),
                array("id" => 4, "name" => "Выполнен"),
            );
            foreach ($status as $statusValue) {
                $tmpStatuses = $statuses;
                if (isset($value["status-oneclick"][$statusValue["id"]])) {
                    foreach ($tmpStatuses as $key => $val) {
                        if ($val[1] == $value["status-returncall"][$statusValue["id"]]) {
                            $tmpStatuses[$key][2] = "selected";
                            break;
                        }
                    }
                }

                $field3 .= $PHPShopGUI->setField($statusValue["name"], $PHPShopGUI->setSelect('status-returncall[' . $statusValue["id"] . ']', $tmpStatuses));
            }

            $tab2 .= $PHPShopGUI->setCollapse('Статусы (обратный звонок)', $field3);
        }


        $PHPShopGUI->setTab(
                array("Настройки", $tab1, true), array("Справочники", $tab2), array("Инструкция", $PHPShopGUI->setInfo($help)), array("О Модуле", $PHPShopGUI->setPay()));
    } else {
        $PHPShopGUI->setTab(array("Настройки", $tab1, true), array("Инструкция", $PHPShopGUI->setInfo($help)), array("О Модуле", $PHPShopGUI->setPay()));
    }

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