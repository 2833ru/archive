<?php

if (!defined("OBJENABLED"))
    exit(header('Location: /?error=OBJENABLED'));

/**
 * В двумерный массив
 */
function promotion_fix_array($in) {
    $data = array();
    $data[0] = $in;
    
    return $data;
}

/**
 * Возвращает дату числом вида ГГГГММДД или 0
 */
function promotion_conv_date($date) {
    $arr = explode('-', $date);
    $date_new = $arr[2] . $arr[1] . $arr[0];
    if(is_numeric($date_new)){
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
    if($active == 1) {
        $date_start = promotion_conv_date($start);     
        $date_end = promotion_conv_date($end);
        
        if(!empty($date_start) || !empty($date_end)) {
            // Если текущая дата больше даты окончания промоакции
            if($now > $date_end && !empty($date_end)) {
                $result = 0;
            }
            // Если текущая дата меньше даты начала промоакции
            if($now < $date_start) {
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
function promotion_check_userstatus($active, $statuses) {
    // По-умолчанию 
    $result = true;
    if($active == 1 && is_array($statuses)) {
        if(isset($_SESSION['UsersStatus'])) {
            $us = $_SESSION['UsersStatus'];   
        } else {
            $us = '-';
        }
        $result = in_array($us, $statuses);   
    }
    return $result;    
}

/**
 * Возвращает информацию о скидках по действующим промоакциям
 * $with_desc - возвращать описание (для страницы товара) 
 */
function promotion_get_discount($row, $with_desc = false) {
    global $promotionslist;
    
    //двумерный массив если запись одна
    if (!isset($promotionslist[0]['code'])) {
        $data[0] = $promotionslist;
    } else {
        $data = $promotionslist;    
    }
    
    $promo_discount = $promo_discountsum = 0; 
    $description = $lab = '';
    $labels = $descriptions = array();
    
    if (isset($data)) {
        foreach ($data as $key => $pro) {
            // Проверим активность промоакции                
            $date_act = promotion_check_activity($pro['active_check'], $pro['active_date_ot'], $pro['active_date_do']);
            $user_act = promotion_check_userstatus($pro['status_check'], unserialize($pro['statuses']));
            
            if($date_act == 1 && $user_act) {
                //Массив категорий для промо кода
                if ($pro['categories_check'] == 1):
                    //категории массив
                    $category_ar = explode(',', $pro['categories']);
                endif;

                if ($pro['products_check'] == 1):
                    //категории массив
                    $products_ar = explode(',', $pro['products']);
                endif;

                $sumche = $sumchep = 0;

                // Не нулевая цена или выключен режим проверки нулевой цены
                if (empty($row['price_n']) or empty($pro['block_old_price'])) {
                    
                    //узнаем по каким категориям
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
    
                    //узнаем по каким товарам
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
                }
                
                //обнуляем категории и товары
                unset($category_ar);
                unset($products_ar);

                if ($sumche == 1 || $sumchep == 1) {
                    //если процент
                    if ($pro['discount_tip'] == 1) {
                        $discount[] = $pro['discount'];
                        $labels[$pro['discount']] = $pro['label'];
                        if ($with_desc) $descriptions[$pro['id']] = '<div>' . $pro['description'] . '</div>';
                    }
                    if ($pro['discount_tip'] == 0) {
                        $discountsum[] = $pro['discount'];
                        $labels[$pro['discount']] = $pro['label'];
                        if ($with_desc) $descriptions[$pro['id']] = '<div>' . $pro['description'] . '</div>';
                    }
                }
            }
        }
        
        //Берем самую большую скидку
        if (isset($discount)) {
            $promo_discount = max($discount) / 100;
            $lab = $labels[$promo_discount*100];
        }

        if (isset($discountsum)) {
            $promo_discountsum = max($discountsum);
            $lab = $labels[$promo_discountsum];            
        }
        
        if ($with_desc && !empty($descriptions))
            $description = implode('', $descriptions);
    }
    
    return array('percent' => $promo_discount, 'sum' => $promo_discountsum, 'label' => $lab, 'description' => $description);
}

class AddToTemplateRegionElement extends PHPShopElements {

    var $debug = false;

    function display() {
        global $PHPShopModules;

            $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.promotions.promotions_forms"));
            $PHPShopOrm->debug=false;
            $where['code'] = '="'.PHPShopSecurity::TotalClean(trim('*')).'"';
            $where['enabled'] = '="1"';
            $GLOBALS['promotionslist'] = $promotionslist = $PHPShopOrm->select(array('*'),$where,array('order'=>'id'));

            $whereCode['code'] = '!="*"';
            $whereCode['enabled'] = '="1"';
            $GLOBALS['promotionslistCode'] = $promotionslistCode = $PHPShopOrm->select(array('*'),$whereCode,array('order'=>'id'),array('limit'=>'300'));

    }

}

$AddToTemplateRegionElement = new AddToTemplateRegionElement();
$AddToTemplateRegionElement->display();
?>