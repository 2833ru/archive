<?php

/**
 * Форматируем описание товара до 250 символов в длину
 * @param array $obj объект
 */
function product_grid_hook($obj, $row) {
    global $PHPShopModules, $promotionslist;

    if(!empty($row['price_n'])) {$obj->set('promotionsIcon', ''); return false; }
    
    $category = $row['category'];
    $uid = $row['id'];

    //список промо
    $data = $promotionslist;

    //двумерный массив если запись одна
    if ($data[0]['code'] == '') {
        $data[0] = $data;
    }

    //обнуляем категории и товары
    unset($category_ar);
    unset($products_ar);

    if (isset($data)) { 
        $labels = array(); 
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
                    $labels[$pro['discount']] = $pro['label'];
                }
                if ($pro['discount_tip'] == 0) {
                    $pro['discount'];
                    $discountsum[] = $pro['discount'];
                    $labels[$pro['discount']] = $pro['label'];
                }

            endif;
        }
        
        //Берем самую большую скидку
        if (isset($discount)) {
            $discount = max($discount) / 100;
            $lab = $labels[$discount*100];
        }

        if (isset($discountsum)) {
            $discountsum = max($discountsum);
            $lab = $labels[$discountsum];           
        }
        
    }

    //Если есть скидка
    if (($discount != '' or $discountsum != '')) {

        $priceDiscount[] = $obj->price($row) - ($obj->price($row) * $discount);
        $priceDiscount[] = $obj->price($row) - $discountsum;
        $priceDiscounItog = min($priceDiscount);
        $priceDiscount = $priceDiscounItog;
        //Обнуляем если в минус уходит
        if ($priceDiscount < 0) {
            $priceDiscount = 0;
        }
        $productPrice = $priceDiscount;
        $productPriceNew = $obj->price($row);

        if ($productPrice < $productPriceNew) {
            $obj->set('productPrice', $productPrice);
            $obj->set('productPriceRub', PHPShopText::strike($productPriceNew . " " . $obj->currency()));
        }

        if ($productPrice < $productPriceNew||$discount==0||$discountsum==0) {
            //ставим лэйбл
            $obj->set('promotionsIcon', $lab);
        }
    } else {
        $obj->set('promotionsIcon', '');
    }
}

$addHandler = array
    (
    'product_grid' => 'product_grid_hook'
);
?>