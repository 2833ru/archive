<?php

/**
 * Цены меняем
 */
function UID_odnotip_hook($obj, $row, $rout) {
    // Если нет новой цены
    global $PHPShopModules, $promotionslist, $SysValue;

    $category = $row['category'];
    $uid = $row['id'];



    if ($rout == "MIDDLE") {


        //список промо
        $data = $promotionslist;

        //двумерный массив если запись одна
        if ($data[0]['code'] == '') {
            $data[0] = $data;
        }


        if (isset($data)) {
            foreach ($data as $key => $pro) {

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

                //узнаем по каким категориям
                if (isset($category_ar)) {
                    foreach ($category_ar as $val_c) {
                        if ($val_c == $category) {
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
                        if ($val_p == $uid) {
                            $sumchep = 1;
                            break;
                        } else {
                            $sumchep = 0;
                        }
                    }
                }
                //обнуляем категории и товары
                unset($category_ar);
                unset($products_ar);

                if ($sumche == 1 or $sumchep == 1):
                    //если процент
                    if ($pro['discount_tip'] == 1) {
                        $pro['discount'];
                        $discount[] = $pro['discount'];
                    }
                    if ($pro['discount_tip'] == 0) {
                        $pro['discount'];
                        $discountsum[] = $pro['discount'];
                    }
                endif;
            }
            //Берем самую большую скидку
            if (isset($discount))
                $discount = max($discount) / 100;

            if (isset($discountsum))
                $discountsum = max($discountsum);
        }

        //Если есть скидка
        if ($discount != '' or $discountsum != '') {

            $priceDiscount[] = $obj->price($row) - ($obj->price($row) * $discount);
            $priceDiscount[] = $obj->price($row) - $discountsum;
            $priceDiscounItog = min($priceDiscount);
            $priceDiscount = $priceDiscounItog;
            //Обнуляем если в минус уходит
            if ($priceDiscount < 0) {
                $priceDiscount = 0;
            }

            $productPrice = $priceDiscount;
            $productPriceNew = $obj->price($row, true);
            $obj->set('productPrice', $productPrice);
            $obj->set('productPriceRub', PHPShopText::strike($obj->price($row) . " " . $obj->currency()));

            //ставим лэйбл
            $obj->set('promotionsIcon', '<span class="sale-icon" style="background-color: rgba(115, 41, 2, 0.29) !important;">Промо-акция</span>');
        }




        //выведем информацию о скидках
        $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.promotions.promotions_forms"));
        $PHPShopOrm->debug = false;
        $where['enabled'] = '="1"';
        $data = $PHPShopOrm->select(array('*'), $where, array('order' => 'id'), array('limit' => 500));

        if (isset($data)) {
            foreach ($data as $value) {
                //массив товаров промо-кодов
                if ($value['categories_check'] == 1):
                    //категории массив
                    $category_ar = explode(',', $value['categories']);
                    //Создаем запрос для создания массива товаров по ID категорий
                    if (isset($category_ar)):
                        $sqlyou = '';
                        $val_cat = '';
                        foreach ($category_ar as $val_cat) {
                            if ($val_cat != '') {
                                if ($sqlyou == '') {
                                    $sqlyou = '="' . $val_cat . '" ';
                                } else {
                                    $sqlyou .= 'OR category="' . $val_cat . '" ';
                                }
                            }
                        }
                    endif;
                    //узнаем ID товаров по ID категорий
                    $PHPShopOrmN = new PHPShopOrm($SysValue['base']['products']);
                    $PHPShopOrmN->debug = false;
                    $whereN['enabled'] = '="1"';
                    if ($sqlyou != '') {
                        $whereN['category'] = $sqlyou;
                    }
                    $data_prod = $PHPShopOrmN->select(array('id'), $whereN, array('order' => 'id'), array('limit' => 500));
                    if (isset($data_prod)) {
                        foreach ($data_prod as $val_prod) {
                            if ($row['id'] == $val_prod['id']) {
                                //массив с описанием акции
                                $inf_text_code[$value['id']] = '<div>' . $value['description'] . '</div>';
                            }
                        }
                    }
                endif;
                //массив категорий промо-кодов
                if ($value['products_check'] == 1):
                    //категории массив
                    $products_ar = explode(',', $value['products']);
                    foreach ($products_ar as $prod_id) {
                        if ($row['id'] == $prod_id) {
                            //массив с описанием акции
                            $inf_text_code[$value['id']] = '<div>' . $value['description'] . '</div>';
                        }
                    }
                endif;
            }
        }
        if (isset($inf_text_code)):
            foreach ($inf_text_code as $value_text) {
                $inf_text_code_all .= $value_text;
            }
        endif;

        $obj->set('promotionInfo', $inf_text_code_all);
    }
}

/**
 * Форматируем описание товара до 250 символов в длину
 * @param array $obj объект
 */
function product_grid_n_hook($obj, $row) {

    global $PHPShopModules, $promotionslist;

    $category = $row['category'];
    $uid = $row['id'];


    //список промо
    $data = $promotionslist;

    //двумерный массив если запись одна
    if ($data[0]['code'] == '') {
        $data[0] = $data;
    }



    if (isset($data)) {
        foreach ($data as $key => $pro) {
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

            //узнаем по каким категориям
            if (isset($category_ar)) {
                foreach ($category_ar as $val_c) {
                    if ($val_c == $category) {
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
                    if ($val_p == $uid) {
                        $sumchep = 1;
                        break;
                    } else {
                        $sumchep = 0;
                    }
                }
            }
            //обнуляем категории и товары
            unset($category_ar);
            unset($products_ar);

            if ($sumche == 1 or $sumchep == 1):
                //если процент
                if ($pro['discount_tip'] == 1) {
                    $pro['discount'];
                    $discount[] = $pro['discount'];
                }
                if ($pro['discount_tip'] == 0) {
                    $pro['discount'];
                    $discountsum[] = $pro['discount'];
                }
            endif;
        }
        //Берем самую большую скидку
        if (isset($discount))
            $discount = max($discount) / 100;

        if (isset($discountsum))
            $discountsum = max($discountsum);
    }

    //Если есть скидка
    if ($discount != '' or $discountsum != '') {

        $priceDiscount[] = $obj->price($row) - ($obj->price($row) * $discount);
        $priceDiscount[] = $obj->price($row) - $discountsum;
        $priceDiscounItog = min($priceDiscount);
        $priceDiscount = $priceDiscounItog;
        //Обнуляем если в минус уходит
        if ($priceDiscount < 0) {
            $priceDiscount = 0;
        }

        $productPrice = $priceDiscount;
        $productPriceNew = $obj->price($row, true);
        $obj->set('productPrice', $productPrice);
        $obj->set('productPriceRub', PHPShopText::strike($obj->price($row) . " " . $obj->currency()));

        //ставим лэйбл
        $obj->set('promotionsIcon', '<span class="sale-icon" style="background-color: rgba(115, 41, 2, 0.29) !important;">Промо-акция</span>');
    }
}

$addHandler = array
    (
    'UID' => 'UID_odnotip_hook',
    'product_grid' => 'product_grid_n_hook'
);
?>