<?php

/**
 * Промоакции
 * @package PHPShopAjaxElements
 */
session_start();

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("modules");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("product");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("string");
PHPShopObj::loadClass("cart");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("user");

// Подключаем библиотеку поддержки JsHttpRequest
if ($_REQUEST['type'] != 'json') {
    require_once $_classPath . "/lib/Subsys/JsHttpRequest/Php.php";
    $JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");
} else {
    $_REQUEST['promocode'] = PHPShopString::utf8_win1251($_REQUEST['promocode']);
    $_REQUEST['sum'] = PHPShopString::utf8_win1251($_REQUEST['sum']);
    $_REQUEST['ssum'] = PHPShopString::utf8_win1251($_REQUEST['ssum']);
    $_REQUEST['tipoplcheck'] = PHPShopString::utf8_win1251($_REQUEST['tipoplcheck']);
    $_REQUEST['wsum'] = PHPShopString::utf8_win1251($_REQUEST['wsum']);
}


// Подключаем библиотеку доставки
require_once $_classPath . "core/order.core/delivery.php";

// Функции для заказа
$PHPShopOrder = new PHPShopOrderFunction();

// Модули
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

// Системные настройки
$PHPShopSystem = new PHPShopSystem();
$currency = $PHPShopSystem->getDefaultValutaCode();

function GetDeliveryPrice($deliveryID, $sum, $weight = 0) {
    global $SysValue, $link_db;

    if (!empty($deliveryID)) {
        $sql = "select * from " . $SysValue['base']['delivery'] . " where id='$deliveryID' and enabled='1'";
        $result = mysqli_query($link_db, $sql);
        $num = mysqli_num_rows($result);
        $row = mysqli_fetch_array($result);

        if ($num == 0) {
            $sql = "select * from " . $SysValue['base']['delivery'] . " where flag='1' and enabled='1'";
            $result = mysqli_query($link_db, $sql);
            $row = mysqli_fetch_array($result);
        }
    } else {
        $sql = "select * from " . $SysValue['base']['delivery'] . " where flag='1' and enabled='1'";
        $result = mysqli_query($link_db, $sql);
        $row = mysqli_fetch_array($result);
    }

    if ($row['price_null_enabled'] == 1 and $sum >= $row['price_null']) {
        return 0;
    } else {
        if ($row['taxa'] > 0) {
            $addweight = $weight - 500;
            if ($addweight < 0) {
                $addweight = 0;
                $at = '';
            } else {
                $at = '';
                //$at='Вес: '.$weight.' гр. Превышение: '.$addweight.' гр. Множитель:'.ceil($addweight/500).' = ';
            }
            $addweight = ceil($addweight / 500) * $row['taxa'];
            $endprice = $row['price'] + $addweight;
            return $at . $endprice;
        } else {
            return $row['price'];
        }
    }
}

$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.promotions.promotions_forms"));
$PHPShopOrm->debug = false;
$where['code'] = '="' . PHPShopSecurity::TotalClean(trim($_REQUEST['promocode'])) . '"';
$where['enabled'] = '="1"';
$data = $PHPShopOrm->select(array('*'), $where, array('order' => 'id'));

//Если промо код уникальный
if ($_REQUEST['promocode'] != '*') {

    //Если код сходится
    if ($data['code'] != ''):
        //есть ли скидка
        if ($data['discount_check'] == 1):
            //Проверяем схождения типа оплаты
            if ($_REQUEST['tipoplcheck'] != $data['delivery_method'] and $data['delivery_method_check'] == 1) {

                //узнаем тип оплаты
                $sq_pay = 'select name from ' . $SysValue['base']['payment_systems'] . ' where id=' . $data['delivery_method'];
                $qu_pay = mysqli_query($link_db, $sq_pay);
                $ro_pay = mysqli_fetch_array($qu_pay);

                $messageinfo = '<b style="color:#7e7a13;">Не подходит тип оплаты!</b><br> Для данного промо-кода тип оплаты может быть только <b>' . $ro_pay['name'] . '</b>. Выберите этот тип оплаты и нажмите снова кнопку ОК для применения скидки';
                $action = '1'; //выполним перенаправление на список оплат
                $status = '0'; //не применена скидка
            } else {
                //Массив категорий для промо кода
                if ($data['categories_check'] == 1):
                    //категории массив
                    $category_ar = explode(',', $data['categories']);
                endif;

                if ($data['products_check'] == 1):
                    //категории массив
                    $products_ar = explode(',', $data['products']);
                endif;

                foreach ($_SESSION['cart'] as $rs => $valuecart) {

                    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
                    $row = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($valuecart['id'])), array('order' => 'id desc'), array('limit' => 1));

                    //узнаем по каким категориям брать товары из корзины
                    if (isset($category_ar)) {
                        foreach ($category_ar as $val_c) {
                            if ($val_c == $row['category']) {
                                $sumche = 1;
                                $info_prod_d_f .= $row['name'] . ', ';
                                break;
                            } else {
                                $sumche = 0;
                            }
                        }
                    }

                    //узнаем по каким товарам брать товары из корзины
                    if (isset($products_ar)) {
                        foreach ($products_ar as $val_p) {
                            if ($val_p == $row['id']) {
                                $sumchep = 1;
                                $info_prod_d_f .= $row['name'] . ', ';
                                break;
                            } else {
                                $sumchep = 0;
                            }
                        }
                    }

                    if ($sumche == 1 or $sumchep == 1):
                        $sumnew += $valuecart['price'] * $valuecart['num'];

                        //если процент
                        if ($data['discount_tip'] == 1) {
                            // скидка и тип
                            $discount = $data['discount'];
                            $tip_disc = '%';
                            $idgg = intval($valuecart['id']);
                            if ($idgg >= 1) {
                                // скидку в сессию (кол-во | тип)
                                $_SESSION['cart'][$rs]['discount'] = $discount;
                                $_SESSION['cart'][$rs]['discount_tip'] = $tip_disc;
                            }
                        } else { //если сумма
                            //скидка и тип
                            $discount_sum = $data['discount'];
                            $tip_disc = $currency;
                            $idgg = intval($valuecart['id']);
                            if ($idgg >= 1) {
                                // скидку в сессию (кол-во | тип)
                                $_SESSION['cart'][$rs]['discount_sum'] = $discount;
                                $_SESSION['cart'][$rs]['discount_tip_sum'] = $tip_disc;
                            }
                        }
                    else:
                        $sumoldi += $valuecart['price'] * $valuecart['num'];
                    endif;
                }
                //информация о товарах к которым применена скидка
                if ($info_prod_d_f != '') {
                    $info_prod_d = '<hr><b>Скидка применена для товаров:</b> ' . $info_prod_d_f;
                }

                //если процент
                if ($data['discount_tip'] == 1) {
                    //считаем скидку
                    $discount = $data['discount'] / 100;
                    //сумма на которую производим скидку
                    $sumtot_new = $sumnew - ($sumnew * $discount);
                    //сумма без скидки
                    $sumtot_old = $sumoldi;
                    //тип скидки
                    $tip_disc = '%';
                    //информация в корзину
                    $discountAll = $data['discount'] . ' ' . $tip_disc;
                    //скидку в сессию
                    $_SESSION['discpromo'] = $data['discount'];
                    $_SESSION['tip_disc'] = 1;
                } else { //если сумма
                    //сумма скидки
                    $discount_sum = $data['discount'];
                    //сумма на которую производим скидку
                    $sumtot_new = $sumnew - $discount_sum;
                    //если вдруг сумма ушла в минус, то ставим нуль
                    if ($sumtot_new < 0) {
                        $sumtot_new = 0;
                    }
                    //сумма без скидки
                    $sumtot_old = $sumoldi;
                    //тип скидки
                    $tip_disc = $currency;
                    //информация в корзину
                    $discountAll = $data['discount'] . ' ' . $tip_disc;
                    $_SESSION['discpromo'] = $data['discount'];
                    $_SESSION['tip_disc'] = 0;
                }


                $versphp = phpversion(); //5.3.0
                //$versphp = "4.1.1";
                $version_status = version_compare($versphp, "5.3.0");

                if ($version_status != '-1') {
                    //Проверим активность по дате
                    if ($data['active_check'] == 1) {
                        //дата сегодня
                        $date_today = date("d-m-Y");
                        //даты от и до
                        $date_ot = $data['active_date_ot'];
                        $date_do = $data['active_date_do'];
                        //меням формат от и до
                        $d_ot_ar = explode('-', $data['active_date_ot']);
                        $d_do_ar = explode('-', $data['active_date_do']);
                        $date_f_ot = $d_ot_ar[2] . '-' . $d_ot_ar[1] . '-' . $d_ot_ar[0];
                        $date_f_do = $d_do_ar[2] . '-' . $d_do_ar[1] . '-' . $d_do_ar[0];
                        //массив дат
                        $begin = new DateTime($date_f_ot);
                        $end = new DateTime($date_f_do);
                        $end = $end->modify('+1 day');
                        $interval = new DateInterval('P1D');
                        $daterange = new DatePeriod($begin, $interval, $end);

                        if (isset($daterange)) {
                            foreach ($daterange as $date) {
                                $data_interval = $date->format("d-m-Y");
                                if ($date_today == $data_interval) {
                                    $date_act = 1;
                                    break;
                                }
                            }
                        }
                    } else {
                        $date_act = 1; //ставим принудительно активность если вдруг дата отключена в настройках
                    }
                } else {
                    $date_act = 1; //ставим принудительно активность если вдруг дата отключена в настройках
                }

                //если не применена скидка
                if ($sumtot_new != ''):
                    //если дата сошлась с интервалом
                    if ($date_act == 1) {
                        //общая сумма
                        $totalsumma_t = $sumtot_new + $sumtot_old;
                        //проверяем сумму от
                        if ($data['sum_order_check'] == 1):
                            if ($totalsumma_t >= $data['sum_order']) {
                                $sumordercheck = 1;
                            } else {
                                $sumordercheck = 0;
                            }
                        else:
                            $sumordercheck = 1; //ставим активной сумму от если галочка в настройках не установлена
                        endif;

                        //проверяем бесплатную доставку
                        if ($data['free_delivery'] == 1):
                            $freedelivery = 0;
                            $_SESSION['freedelivery'] = 0;
                        else:
                            //галочка убрана для бесплатной доставки
                            $_SESSION['freedelivery'] = 1;
                            if ($_REQUEST['dostavka'] > 0)
                                $dostavka = GetDeliveryPrice(intval($_REQUEST['dostavka']), $totalsumma_t, $_REQUEST['wsum']);
                        endif;

                        if ($sumordercheck == 1):
                            $status = '1'; //скидка применена
                            //система оплаты
                            if ($data['delivery_method_check'] == 1) {
                                $delivery_method_check = 1;

                                //$totalsummainput = $sumtot_new + $sumtot_old;
                            } else {
                                $delivery_method_check = 0;
                            }
                            $totalsumma = $sumtot_new + $sumtot_old;

                            if ($_REQUEST['sum'] > $totalsumma) {

                                $totalsummainput = $sumtot_new + $sumtot_old;
                                $_SESSION['totalsumma'] = $totalsumma;
                                $_SESSION['promocode'] = $data['code'];
                                $_SESSION['codetip'] = $data['code_tip'];
                                //обнулим если код верный
                                foreach ($_SESSION['cart'] as $is => $valcar) {
                                    //сбросим инфу о скидка
                                    unset($_SESSION['cart'][$is]['discount']);
                                    unset($_SESSION['cart'][$is]['discount_tip']);
                                    unset($_SESSION['cart'][$is]['id_sys']);
                                }
                                $messageinfo = '<b style="color:#137e15;">Поздравляем с приобретением!</b><br> Промо код указан верно! Ваша скидка ' . $data['discount'] . ' ' . $tip_disc . $info_cat_d . $info_prod_d;
                            } else {
                                $totalsumma = $_REQUEST['sum'];
                                $totalsummainput = $_REQUEST['sum'];
                            }


                        else:
                            $messageinfo = '<b style="color:#7e7a13;">Не применена!</b><br> Промо код указан верно.<br> Но сумма заказа должна быть от ' . $data['sum_order'] . ' ' . $currency;
                            $status = '0'; //скидка не применена
                            $_SESSION['totalsumma'] = '0';
                        endif;
                    }
                    else { //если дата не сошлась
                        $messageinfo = '<b style="color:#7e7a13;">Не применена!</b><br> Промо код указан верно.<br> Но срок действия акции закончен';
                        $status = '0'; //скидка не применена
                        $_SESSION['totalsumma'] = '0';
                    }
                else:
                    $messageinfo = '<b style="color:#7e7a13;">Не применена!</b><br> Промо код указан верно.<br> Но ни один из товаров в вашей корзине не участвует в акции';
                    $status = '0'; //скидка не применена
                    $_SESSION['totalsumma'] = '0';
                endif;
            }
        else:
            $messageinfo = '<b style="color:#7e7a13;">Ошибка!</b><br> Скидка для промокода не установлена, свяжитесь с нами для подробной информации!';
            $status = '0'; //не применена скидка
            $_SESSION['totalsumma'] = '0';
        endif;
    else:
        $messageinfo = '<b style="color:#7e7a13;">Ошибка!</b><br> Данного промо-кода в базе данных не обнаружено!';
        $status = '0'; //не применена скидка
    endif;

    //соберем массив скидок для JS
    $numc = 3; //для пересчета таблицы корзины
    if (is_array($_SESSION['cart']))
        foreach ($_SESSION['cart'] as $cartjs) {
            $discountcart[$cartjs['id']]['n'] = $numc;
            $numc++;
        }
} elseif ($_REQUEST['promocode'] == '*') {//Если применяем без промо кода скидку
    //двумерный массив если запись одна
    if ($data[0]['code'] == '') {
        $data[0] = $data;
    }

    foreach ($_SESSION['cart'] as $is => $valcar) {
        //сбросим инфу о скидка
        $id = intval($valcar['id']);

        if ($id >= 1) {
            unset($_SESSION['cart'][$is]['discount']);
            unset($_SESSION['cart'][$is]['discount_tip']);
            unset($_SESSION['cart'][$is]['id_sys']);
        }
    }

    foreach ($data as $pro) {
        //Проверяем схождения типа оплаты
        if ($_REQUEST['tipoplcheck'] != $pro['delivery_method'] and $pro['delivery_method_check'] == 1) {
            //просто ничего не делаем, если тип оплаты не сходится
        } else {


            $sumpo_s = '';
            $sumpo_p = '';

            foreach ($_SESSION['cart'] as $valuecart) {

                $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
                $row = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($valuecart['id'])), array('order' => 'id desc'), array('limit' => 1));

                //Массив категорий для промо кода
                if ($pro['categories_check'] == 1):
                    //категории массив
                    $category_ar = explode(',', $pro['categories']);
                endif;

                if ($pro['products_check'] == 1):
                    //категории массив
                    $products_ar = explode(',', $pro['products']);
                endif;

                $sumche = 0;
                $sumchep = 0;

                //узнаем по каким категориям брать товары из корзины
                if (isset($category_ar)) {
                    foreach ($category_ar as $val_c) {
                        if ($val_c == $row['category']) {
                            $sumche = 1;
                            $info_prod_d_f .= $row['name'] . ', ';
                            break;
                        } else {
                            $sumche = 0;
                        }
                    }
                }

                //узнаем по каким товарам брать товары из корзины
                if (isset($products_ar)) {
                    foreach ($products_ar as $val_p) {
                        if ($val_p == $row['id']) {
                            $sumchep = 1;
                            $info_prod_d_f .= $row['name'] . ', ';
                            break;
                        } else {
                            $sumchep = 0;
                        }
                    }
                }

                unset($category_ar);
                unset($products_ar);

                if ($sumche == 1 or $sumchep == 1) {
                    $sumnew += $valuecart['price'] * $valuecart['num'];

                    //если процент
                    if ($pro['discount_tip'] == 1) {
                        // скидка и тип
                        $discount = $pro['discount'];
                        $tip_disc = '%';
                        // скидку в сессию (кол-во | тип)
                        //Если скидка есть, то сравниваем
                        if ($_SESSION['cart'][$valuecart['id']]['discount'] != '') {
                            if ($tip_disc == $_SESSION['cart'][$valuecart['id']]['discount_tip']) {
                                $ddnew = max($_SESSION['cart'][$valuecart['id']]['discount'], $discount);
                                $discount_v_p = $ddnew;
                                $tip_disc_v_p = $tip_disc;
                            } else {
                                $discount_v_p = $discount;
                                $tip_disc_v_p = $tip_disc;
                            }
                        } else {
                            $discount_v_p = $discount;
                            $tip_disc_v_p = $tip_disc;
                        }
                        $dispr = $discount / 100;
                        $sumpo_p = ($valuecart['price'] * $valuecart['num']) - ( ($valuecart['price'] * $valuecart['num']) * $dispr );
                    }

                    if ($pro['discount_tip'] == 0) { //если сумма
                        //скидка и тип
                        $discount_sum = $pro['discount'];
                        $tip_disc = $currency;
                        // скидку в сессию (кол-во | тип)

                        if ($_SESSION['cart'][$valuecart['id']]['discount'] != '') {
                            if ($tip_disc == $_SESSION['cart'][$valuecart['id']]['discount_tip']) {
                                //если сходятся типы (если не сходятся, значит оставляем как есть, т.е процент)
                                $ddnew = max($_SESSION['cart'][$valuecart['id']]['discount'], $discount_sum);
                                $discount_v_s = $ddnew;
                                $tip_disc_v_s = $tip_disc;
                            } else {
                                $discount_v_s = $discount_sum;
                                $tip_disc_v_s = $tip_disc;
                            }
                        } else {
                            $discount_v_s = $discount_sum;
                            $tip_disc_v_s = $tip_disc;
                        }

                        $sumpo_s = ($valuecart['price'] * $valuecart['num']) - $discount_sum;
                    }

                    //Намерено ставим больше
                    if ($sumpo_s == '') {
                        $sumpo_s = ($valuecart['price'] * $valuecart['num']) * 100;
                    }
                    if ($sumpo_p == '') {
                        $sumpo_p = ($valuecart['price'] * $valuecart['num']) * 100;
                    }

                    //узнаем минимальную сумму
                    $sumitogn = min($sumpo_s, $sumpo_p);

                    $sumitogn . '|' . $sumpo_p . '!';
                    $sumitogn . '|' . $sumpo_s . '!';

                    //выясняем какая скидка применена
                    if ($sumitogn == $sumpo_p) {
                        $_SESSION['cart'][$valuecart['id']]['discount'] = $discount_v_p;
                        $_SESSION['cart'][$valuecart['id']]['discount_tip'] = $tip_disc_v_p;
                    }
                    if ($sumitogn == $sumpo_s) {
                        $_SESSION['cart'][$valuecart['id']]['discount'] = $discount_v_s;
                        $_SESSION['cart'][$valuecart['id']]['discount_tip'] = $tip_disc_v_s;
                        $_SESSION['cart'][$valuecart['id']]['id_sys'] = $pro['id'];
                    }
                } else {
                    $sumoldi += $valuecart['price'] * $valuecart['num'];
                }
            }
            //информация о товарах к которым применена скидка
            if ($info_prod_d_f != '') {
                $info_prod_d = '<hr><b>Скидка применена для товаров:</b> ' . $info_prod_d_f;
            }

            //если процент
            if ($pro['discount_tip'] == 1) {
                //считаем скидку
                $discount = $pro['discount'] / 100;
                //сумма на которую производим скидку
                $sumtot_new = $sumnew - ($sumnew * $discount);
                //сумма без скидки
                $sumtot_old = $sumoldi;
                //тип скидки
                $tip_disc = '%';
                //информация в корзину
                $discountAll = 'общая';
                //скидку в сессию
                $_SESSION['discpromo'] = $pro['discount'];
                $_SESSION['tip_disc'] = 1;
            } else { //если сумма
                //сумма скидки
                $discount_sum = $pro['discount'];
                //сумма на которую производим скидку
                $sumtot_new = $sumnew - $discount_sum;
                //если вдруг сумма ушла в минус, то ставим нуль
                if ($sumtot_new < 0) {
                    $sumtot_new = 0;
                }
                //сумма без скидки
                $sumtot_old = $sumoldi;
                //тип скидки
                $tip_disc = $currency;
                //информация в корзину
                $discountAll = 'общая';
                $_SESSION['discpromo'] = $pro['discount'];
                $_SESSION['tip_disc'] = 0;
            }

            //Проверим активность по дате
            if ($pro['active_check'] == 1) {
                //дата сегодня
                $date_today = date("d-m-Y");
                //даты от и до
                $date_ot = $pro['active_date_ot'];
                $date_do = $pro['active_date_do'];
                //меням формат от и до
                $d_ot_ar = explode('-', $pro['active_date_ot']);
                $d_do_ar = explode('-', $pro['active_date_do']);
                $date_f_ot = $d_ot_ar[2] . '-' . $d_ot_ar[1] . '-' . $d_ot_ar[0];
                $date_f_do = $d_do_ar[2] . '-' . $d_do_ar[1] . '-' . $d_do_ar[0];
                //массив дат
                $begin = new DateTime($date_f_ot);
                $end = new DateTime($date_f_do);
                $end = $end->modify('+1 day');
                $interval = new DateInterval('P1D');
                $daterange = new DatePeriod($begin, $interval, $end);

                if (isset($daterange)) {
                    foreach ($daterange as $date) {
                        $data_interval = $date->format("d-m-Y");
                        if ($date_today == $data_interval) {
                            $date_act = 1;
                            break;
                        } else {
                            $date_act = 0;
                        }
                    }
                }
            } else {
                $date_act = 1; //ставим принудительно активность если вдруг дата отключена в настройках
            }


            //если не применена скидка
            if ($sumtot_new != '0'):
                //если дата сошлась с интервалом
                if ($date_act == 1) {
                    //общая сумма
                    $totalsumma_t = $sumtot_new + $sumtot_old;
                    //проверяем сумму от
                    if ($pro['sum_order_check'] == 1):
                        if ($totalsumma_t >= $pro['sum_order']) {
                            $sumordercheck = 1;
                        } else {
                            $sumordercheck = 0;
                        }
                    else:
                        $sumordercheck = 1; //ставим активной сумму от если галочка в настройках не установлена
                    endif;

                    //проверяем бесплатную доставку
                    if ($pro['free_delivery'] == 1):
                        $free_delivery_inf = 1;
                    endif;

                    if ($sumordercheck == 1):
                        $status = '9'; //скидка применена, общая
                        $_SESSION['promocode'] = $pro['code'];
                        $_SESSION['codetip'] = $pro['code_tip'];
                        //система оплаты
                        if ($pro['delivery_method_check'] == 1) {
                            $delivery_method_check = 1;
                        } else {
                            $delivery_method_check = 0;
                        }
                    else:
                        $status = '0'; //скидка не применена
                    endif;
                }
                else { //если дата не сошлась
                    $status = '0'; //скидка не применена
                }
            else:
                $status = '0'; //скидка не применена
            endif;
        }
    }

    //пересчитаем бонусы если в сумме
    $nr = 1;
    foreach ($_SESSION['cart'] as $ca) {
        if (intval($ca['id_sys']) >= 1) {
            $ndiscsum_ar[$ca['id_sys']] += $nr;
        }
    }

    //перезаписываем session если в сумме
    foreach ($_SESSION['cart'] as $caupd) {
        if (intval($caupd['id_sys']) >= 1) {
            $count_d = $ndiscsum_ar[$caupd['id_sys']];
            if ($count_d >= 2) {
                $sum_sd = $_SESSION['cart'][$caupd['id']]['discount'];
                $_SESSION['cart'][$caupd['id']]['discount'] = round($sum_sd, 2);
            }
        }
    }


    //соберем массив скидок для JS
    $numc = 3; //для пересчета таблицы корзины
    $totalsumma = '0'; //обнуляем на всякий
    foreach ($_SESSION['cart'] as $cartjs) {
        $discountcart[$cartjs['id']]['n'] = $numc;
        $discountcart[$cartjs['id']]['discount'] = $cartjs['discount'];
        $discountcart[$cartjs['id']]['discount_tip'] = PHPShopString::win_utf8($cartjs['discount_tip']);
        $discountcart[$cartjs['id']]['num_product'] = $cartjs['num'];

        unset($tsumItogAr);

        //Заодно и сумму общую пересчитаем тут
        if ($cartjs['discount_tip'] == '%') {
            $sucart = $cartjs['price'] * $cartjs['num'];
            $dit = $cartjs['discount'] / 100;
            $tsumItogAr[] = $sucart - ($sucart * $dit);
        }

        if ($cartjs['discount_tip'] == $currency) {
            if ($cartjs['discount'] != '') {
                $tsumItogAr[] = ($cartjs['price'] * $cartjs['num']) - ($cartjs['discount'] * $cartjs['num']);
            } else {
                $tsumItogAr[] = $cartjs['price'] * $cartjs['num'];
            }
            if ($tsum < 0) {
                $tsumItogAr[] = 0;
            }
        }

        if ($cartjs['discount_tip'] == '') {
            $tsumItogAr[] = $cartjs['price'] * $cartjs['num'];
        }

        if (isset($tsumItogAr))
            $tsumItog = min($tsumItogAr);

        $tsum = $tsumItog;

        $totalsumma += $tsum;
        $numc++;
    }
    $_SESSION['totalsumma'] = $totalsumma;
    $totalsummainput = $totalsumma;

    if ($_SESSION['promocode'] == '*') {
        $status = 9; //применяем скидку
    } else {
        $status = 0; //не применяем
    }

    //проверяем бесплатную доставку
    if ($free_delivery_inf == 1):
        $freedelivery = 0;
        $_SESSION['freedelivery'] = 0;
    else:
        //галочка убрана для бесплатной доставки
        $_SESSION['freedelivery'] = 1;
    endif;
}


// Результат
$_RESULT = array(
    'delivery' => $GetDeliveryPrice,
    'dellist' => $dellist,
    'adresList' => $adresList,
    'total' => $totalsumma,
    'discount' => $data['discount'],
    'discountall' => $discountAll,
    'mes' => $messageinfo,
    'action' => $action,
    'status' => $status,
    'freedelivery' => $freedelivery,
    'totalsummainput' => $totalsummainput,
    'deliverymethodcheck' => $delivery_method_check,
    'discountcart' => $discountcart,
    'success' => 1
);


// JSON 
if ($_REQUEST['type'] == 'json') {
    $_RESULT['mes'] = PHPShopString::win_utf8($_RESULT['mes']);
    $_RESULT['discountall'] = PHPShopString::win_utf8($_RESULT['discountall']);
    $_RESULT['mesclean'] = strip_tags($_RESULT['mes']);
    //$_RESULT['discountcart']=PHPShopString::win_utf8($_RESULT['discountcart']);
}
echo json_encode($_RESULT);
?>