<?php

$TitlePage = __("О программе");


// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopModules,$version,$PHPShopBase;

    $licFile=PHPShopFile::searchFile('../../license/', 'getLicense',true);
    @$License = parse_ini_file("../../license/" .$licFile , 1);

    $TechPodUntilUnixTime = $License['License']['SupportExpires'];
    if (is_numeric($TechPodUntilUnixTime))
        $TechPodUntil = PHPShopDate::get($TechPodUntilUnixTime);
    else
        $TechPodUntil = " ознакомительный режим ";

    $DomenLocked = $License['License']['DomenLocked'];
    if (empty($DomenLocked))
        $DomenLocked = $_SERVER['SERVER_NAME'];


    $LicenseUntilUnixTime = $License['License']['Expires'];
    if (is_numeric($LicenseUntilUnixTime))
        $LicenseUntil = PHPShopDate::get($LicenseUntilUnixTime);
    else
        $LicenseUntil = " без ограничений ";

    if($License['License']['Pro'] == 'Start'){
        $product_name = 'Start';
        $mod_limit = 'максимум <b>5</b> модулей. <a href="http://www.phpshop.ru/order/?from='.$_SERVER['SERVER_NAME'].'" target="_blank">Снять ограничение Start?</a>';
    }
    else{
        if($License['License']['Pro'] == 'Enabled')
        $product_name = 'Pro 1C';
        else $product_name = 'Enterprise';
        $mod_limit = 'без ограничений';
    }
    

    // Размер названия поля
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->addJSFiles('./system/gui/system.gui.js'); 
    $PHPShopGUI->setActionPanel(__("О программе PHPShop"), false, false);


    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setCollapse(__('Информация'), 
            $PHPShopGUI->setField("Название программы", '<a class="btn btn-sm btn-default" href="http://www.phpshop.ru/page/compare.html?from='.$_SERVER['SERVER_NAME'].'" target="_blank"><span class="glyphicon glyphicon-info-sign"></span> PHPShop '.$product_name.'</a>').
            $PHPShopGUI->setField("Версия программы", '<a class="btn btn-sm btn-default" href="http://www.phpshop.ru/docs/update.html?from='.$_SERVER['SERVER_NAME'].'" target="_blank"><span class="glyphicon glyphicon-info-sign"></span> ' .  substr($version, 0, strlen($version)-1) .'</a>').
            $PHPShopGUI->setField("Подключаемые модули", $mod_limit, false, false, false, 'text-right') .
            $PHPShopGUI->setField("Окончание поддержки", $TechPodUntil, false, false, false, 'text-right') .
            $PHPShopGUI->setField("Окончание лицензии", $LicenseUntil, false, false, false, 'text-right').
            $PHPShopGUI->setField("Файл лицензии", '/license/'.$licFile, false, false, false, 'text-right').
            $PHPShopGUI->setField("Серийный номер", $License['License']['Serial'], false, __('Требуется для активации Pro 1С'), false, 'text-right').
            $PHPShopGUI->setField("Версия PHP",  phpversion(), false, false, false, 'text-right').
            $PHPShopGUI->setField("Версия MySQL",  @mysqli_get_server_info($PHPShopBase->link_db), false, false, false, 'text-right').
            $PHPShopGUI->setField(false, '</form><form method="post" target="_blank" enctype="multipart/form-data" action="http://www.phpshop.ru/order.html" name="product_upgrade" id="product_support" style="display:none">
<input type="hidden" value="supportenterprise" name="addToCartFromPages" id="addToCartFromPages">             
<input type="hidden" value="'.$DomenLocked.'" name="addToCartFromPagesDomen" id="addToCartFromPagesDomen">
</form><form><a class="btn btn-sm btn-primary pay-support" href="#" target="_blank"><span class="glyphicon glyphicon-ruble"></span> '.__('Приобрести техническую поддержку').'</a>')
    );





    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__,$License);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1),array("Лицензионное соглашение", $PHPShopGUI->loadLib('tab_license', false, './system/'),true));


    // Футер
    $PHPShopGUI->Compile();
    return true;
}

?>