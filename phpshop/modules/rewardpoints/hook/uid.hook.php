<?php

/**
 * Цены меняем
 */
function UID_rewardpoints_hook($obj, $row, $rout) {
    global $PHPShopModules;
    if ($rout == "MIDDLE") {

        if($_SESSION['UsersId']!='') {
            //Курс балла
            $PHPShopOrmValutaf = new PHPShopOrm($GLOBALS['SysValue']['base']['currency']);
            $currency = $PHPShopOrmValutaf->select(array('*'));
            foreach ($currency as $cur) {
                if($obj->currency()==$cur['code'])
                    $price_point = $cur['price_point'];
            }
            //Настройки модуля
            $PHPShopOrmRew = new PHPShopOrm($PHPShopModules->getParam("base.rewardpoints.rewardpoints_system"));
            $system = $PHPShopOrmRew->select(array('*'), false, false, array('limit' => 1));
            $percent_add = $system['percent_add']/100;
            //Начисленные
            $pointsAccrued = round( ($row['price']*$percent_add)/$price_point );
            //Проверка на ручной ввод 
            if($pointsAccrued<$row['point'])
                $pointsAccrued = $row['point'];

            $obj->set('pointsAccrued', $pointsAccrued);
        }
        else {
            $obj->set('pointsAccruedComStart', '<!--');
            $obj->set('pointsAccruedComEnd', '-->');
        }
    }
}

/**
 * Форматируем описание товара до 250 символов в длину
 * @param array $obj объект
 */
function product_grid_rewardpoints_hook($obj, $row) {
    if($_SESSION['UsersId']!='') {
        //Курс балла
        $PHPShopOrmValutaf = new PHPShopOrm($GLOBALS['SysValue']['base']['currency']);
        $currency = $PHPShopOrmValutaf->select(array('*'));
        foreach ($currency as $cur) {
            if($obj->currency()==$cur['code'])
                $price_point = $cur['price_point'];
        }
        //Настройки модуля
        $PHPShopOrmRew = new PHPShopOrm($PHPShopModules->getParam("base.rewardpoints.rewardpoints_system"));
        $system = $PHPShopOrmRew->select(array('*'), false, false, array('limit' => 1));
        $percent_add = $system['percent_add']/100;
        //Начисленные
        $pointsAccrued = round( ($row['price']*$percent_add)/$price_point );
        //Проверка на ручной ввод 
        if($pointsAccrued<$row['point'])
            $pointsAccrued = $row['point'];


        $obj->set('pointsAccrued', $pointsAccrued);
        $obj->set('pointsAccruedComStart', '');
        $obj->set('pointsAccruedComEnd', '');
    }
    else {
        $obj->set('pointsAccrued', '');
        $obj->set('pointsAccruedComStart', '<!--');
        $obj->set('pointsAccruedComEnd', '-->');
    }
}

$addHandler = array
    (
    'UID' => 'UID_rewardpoints_hook',
    'product_grid' => 'product_grid_rewardpoints_hook'
);
?>