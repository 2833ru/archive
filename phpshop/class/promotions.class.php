<?php

/**
 * Библиотека промоакций
 * @author PHPShop Software
 * @version 1.4
 * @package PHPShopClass
 */
class PHPShopPromotions {

    /**
     * Конструктор
     */
    function __construct() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['promotion']);
        $PHPShopOrm->debug = false;
        $where['enabled'] = '="1"';
        $this->promotionslist = $PHPShopOrm->select(array('*'), $where, array('order' => 'id'), array('limit' => 1000), __CLASS__, __FUNCTION__);
    }

    /**
     * Возвращает дату числом вида ГГГГММДД или 0
     */
    function promotion_conv_date($date) {
        $arr = explode('-', $date);
        $date_new = $arr[2] . $arr[1] . $arr[0];
        if (is_numeric($date_new)) {
            return intval($date_new);
        } else {
            return 0;
        }
    }

    /**
     * Проверяет активность промоакции, возвращает 1 - Активна или 0 - Неактивна
     */
    function promotion_check_activity($active, $start, $end) {

        // По-умолчанию включена
        $result = 1;
        $now = intval(date('Ymd'));

        // Если стоит учитывать период и переданы даты
        if ($active == 1) {
            $date_start = $this->promotion_conv_date($start);
            $date_end = $this->promotion_conv_date($end);

            if (!empty($date_start) || !empty($date_end)) {
                // Если текущая дата больше даты окончания промоакции
                if ($now > $date_end && !empty($date_end)) {
                    $result = 0;
                }
                // Если текущая дата меньше даты начала промоакции
                if ($now < $date_start) {
                    $result = 0;
                }
            }
        }
        // Возвращаем значение
        return $result;
    }

    /**
     * Проверяет условие статуса покупателя с указанными в настройках
     */
    function promotion_check_userstatus($statuses) {

        $result = true;
        if (is_array($statuses)) {

            if (isset($_SESSION['UsersStatus'])) {
                $us = $_SESSION['UsersStatus'];
            } else {
                $us = 0;
            }
            $result = in_array($us, $statuses);
        }
        return $result;
    }

    /**
     * Проверяет условие количества в корзине
     */
    function promotion_check_cart($num_check, $num=0) {
        if (!empty($num_check) and $num < $num_check)
            $result = false;
        else
            $result = true;

        return $result;
    }

    /**
     * Возвращает информацию о скидках по действующим промоакциям
     */
    function promotion_get_discount($row) {

        $data = $this->promotionslist;
        $promo_discount = $promo_discountsum = 0;
        $lab = null;
        $id = null;
        $labels = $ids = $hidePrices = array();

        if (isset($data)) {
            foreach ($data as $pro) {

                // Проверим активность промоакции
                $date_act = $this->promotion_check_activity($pro['active_check'], $pro['active_date_ot'], $pro['active_date_do']);

                // Проверяем статус пользователя
                $user_act = $this->promotion_check_userstatus(unserialize($pro['statuses']));

                if ($date_act == 1 && $user_act) {
                    $sum_order_check = $pro['sum_order_check'];
                    $num_check = $pro['num_check'];

                    // Массив категорий
                    if ($pro['categories_check'] == 1)
                        $category_ar = explode(',', $pro['categories']);

                    // Массив товаров
                    if ($pro['products_check'] == 1)
                        $products_ar = explode(',', $pro['products']);

                    $sumche = $sumchep = 0;

                    // Не нулевая цена или выключен режим проверки нулевой цены
                    if (empty($row['price_n']) or empty($pro['block_old_price'])) {

                        // узнаем по каким категориям
                        if (isset($category_ar)) {
                            foreach ($category_ar as $val_c) {
                                if ($val_c == $row['category']) {
                                    $sumche = 1;
                                    break;
                                } else {
                                    $sumche = 0;
                                }
                            }
                        }

                        // узнаем по каким товарам
                        if (isset($products_ar)) {
                            foreach ($products_ar as $val_p) {
                                if ($val_p == $row['id']) {
                                    $sumchep = 1;
                                    break;
                                } else {
                                    $sumchep = 0;
                                }
                            }
                        }

                        // обнуляем категории и товары
                        unset($category_ar);
                        unset($products_ar);

                        if ($sumche == 1 || $sumchep == 1) {

                            // если процент
                            if ($pro['discount_tip'] == 1) {

                                $discount[] = $pro['discount'];
                                $labels[$pro['discount']] = $pro['label'];
                                $hidePrices[$pro['discount']] = $pro['hide_old_price'];
                            }
                            // если скидка
                            else {

                                $discountsum[] = $pro['discount'];
                                $labels[$pro['discount']] = $pro['label'];
                                $hidePrices[$pro['discount']] = $pro['hide_old_price'];
                            }

                            $ids[$pro['discount']] = $pro['id'];
                        }
                    }
                }
            }

            // Берем самую большую скидку
            if (isset($discount)) {
                $promo_discount = max($discount) / 100;
                $lab = $labels[$promo_discount * 100];
                $hidePrice = $hidePrices[$promo_discount * 100];
                $id = $ids[$promo_discount * 100];
            }

            if (isset($discountsum)) {
                $promo_discountsum = max($discountsum);
                $lab = $labels[$promo_discountsum];
                $hidePrice = $hidePrices[$promo_discountsum];
                $id = $ids[$promo_discountsum];
            }
        }

        return array('status' => $sum_order_check, 'percent' => $promo_discount, 'sum' => $promo_discountsum, 'label' => $lab, 'hidePrice' => $hidePrice, 'num_check' => $num_check, 'id'=>$id);
    }

    /**
     * Вывод цены с учет промоакции
     * @param array $row массив данных товара
     * @return array
     */
    function getPrice($row) {

        // Получаем информацию о скидках по действующим промоакциям
        $discount_info = $this->promotion_get_discount($row);

        // Проверяем количество в корзине
        $isNeedCount = true;
        if ((int) $discount_info['num_check'] > 1) {
            // Сумма товаров по акции
            $isNeedCount = $this->promotion_check_cart((int) $discount_info['num_check'], $this->getCntPromoProdsInCart((int) $discount_info['id']));
        }

        $discount = $discount_info['percent'];
        $discountsum = $discount_info['sum'];
        $status = $discount_info['status'];

        // Если есть скидка
        if (!empty($discount) || !empty($discountsum)) {

            // Скидка
            if ($status == 0) {
                $priceDiscount[] = $row['price'] - ($row['price'] * $discount);
                $priceDiscount[] = $row['price'] - $discountsum;
                $priceDiscounItog = min($priceDiscount);
                $priceDiscount = $priceDiscounItog;

                // Обнуляем если в минус уходит
                if ($priceDiscount < 0) {
                    $priceDiscount = 0;
                }
            }
            // Наценка
            else {
                $priceDiscount[] = $row['price'] + ($row['price'] * $discount);
                $priceDiscount[] = $row['price'] + $discountsum;
                $priceDiscounItog = max($priceDiscount);
                $priceDiscount = $priceDiscounItog;
            }

            if($isNeedCount) {
                $productPrice = $priceDiscount;
                $productPriceNew = $row['price'];
            } else {
                $productPrice = $row['price'];
                $productPriceNew = $row['price_n'];
            }

            // Не показывать старую цену
            if ($discount_info['hidePrice'] == 1) {
                $productPriceNew = 0;
            }

            return array('price' => $productPrice, 'price_n' => $productPriceNew, 'label' => $discount_info['label'],'num_check'=>$discount_info['num_check'],'id'=>$discount_info['id']);
        }
    }

    private function getCntPromoProdsInCart($discountId)
    {
        $cart = new PHPShopCart();
        $num = 0;
        foreach ($cart->_CART as $k => $cartProduct) {
            $discount = $this->promotion_get_discount($cartProduct);
            if(!empty($discount['sum']) or !empty($discount['percent']) and (int) $discount['id'] === $discountId) {
                $num += $cartProduct['num'];
            }
        }

        return $num;
    }
}

?>