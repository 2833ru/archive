<?php

/**
 * Вывод городов доставки
 * @package PHPShopCoreFunction
 * @param int $deliveryID ИД доставки
 * @return string
 */
function delivery($obj, $deliveryID, $sum = 0) {
    global $SysValue, $link_db;

    $pred = $br = $my = $alldone = $waytodo = $disp = null;

    if(is_null($GLOBALS['SysValue']['templates']['delivery']) or
        !is_file(dirname(dirname(__DIR__)) . '/templates/' . $_SESSION['skin'] . '/' . $GLOBALS['SysValue']['templates']['delivery'])) {
        $template = dirname(dirname(__DIR__)) . '/lib/templates/order/delivery.tpl';
    } else {
        $template = dirname(dirname(__DIR__)) . '/templates/' . $_SESSION['skin'] . '/' . $GLOBALS['SysValue']['templates']['delivery'];
    }

    // Мультибаза
    if (defined("HostID")) {
        $servers= " and servers REGEXP 'i" . HostID . "i'";
    } elseif (defined("HostMain"))
        $servers= " and (servers = '' or servers REGEXP 'i1000i')";
    else $servers = null;

    if (empty($SysValue['nav'])) {
        $engineinc = 0;
        $pathTemplate = $GLOBALS['SysValue']['dir']['dir'] . chr(47) . $GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'];
    } else {
        $engineinc = 1;
        $pathTemplate = $GLOBALS['SysValue']['dir']['dir'] . $GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'];
    }

    $table = $SysValue['base']['delivery'];

    if ($deliveryID > 0) {
        $sql = "select * from " . $table . " where (enabled='1' and id='" . $deliveryID . "') ".$servers." order by num,city";
        $result = mysqli_query($link_db, $sql);
        $row = mysqli_fetch_array($result);
        $isfolder = $row['is_folder'];
        $comment = $row['comment'];
        $PID = $row['PID'];

        if ($isfolder) { //Если прислали папку, то варианты будут потомки папки
            $sqlvariants = "select * from " . $table . " where (enabled='1' and PID='" . $deliveryID . "') ".$servers." order by num,city";
            $PIDpr = $deliveryID; //Начальный предок, для приглашения
            $stop = 0;
        } else { //Если прислали вариант, то варианты будут соседи
            $sqlvariants = "select * from " . $table . " where (enabled='1' and PID='" . $row['PID'] . "') ".$servers." order by num,city";
            $PIDpr = $row['PID']; //Начальный предок, для приглашения
            $stop = 1;
        }
    } else { //Если не прислали, значит стартовый набор все корневые доставки
        $stop = 0;
        $isfolder = 1; //Если не прислали ID, значит прислали 0 идентификатор, который является папкой корневых
        $PID = false;
        $deliveryID = 0; //Присваиваем нулевой идентификатор, если ничего не прислали
        $sqlvariants = "select * from " . $table . " where (enabled='1' and PID='0') ".$servers." order by num,city";
    }
    
    $resultvariants = mysqli_query($link_db, $sqlvariants); // Принимаем варианты

    if ($PID !== false) { //Если есть предки, формируем навигацию
        $pred = '';
        $num = 0;
        while ($PIDpr != 0) {//Делаем пока не дойдем до самого верхнего уровня
            $num++;

            //Получаем первого предка
            $sqlpr = "select * from " . $SysValue['base']['delivery'] . " where (enabled='1' and id='" . $PIDpr . "') ".$servers." order by num,city";
            $resultpr = mysqli_query($link_db, $sqlpr);
            $rowpr = mysqli_fetch_array($resultpr);

            $PIDpr = $rowpr['PID']; //Меняем идентификатор предка. На уровень выше

            $city = $rowpr['city'];

            $predok = $rowpr['city'] . ' > ' . $predok; //Довесок, который будем дописывать каждому варианту

            //Получаем количество соседей у вышестоящего.
            $sqlprr = "select * from " . $table . " where (enabled='1' and PID='" . $PIDpr . "') ".$servers." order by num,city";
            $resultprr = mysqli_query($link_db, $sqlprr);
            $ii = 0;
            while ($rowsos = mysqli_fetch_array($resultprr)) {
                $sqlsosed = "select * from " . $table . " where (enabled='1' and PID='" . $rowsos['id'] . "') ".$servers." order by num,city";
                $resultsosed = mysqli_query($link_db, $sqlsosed);
                $sosed = mysqli_num_rows($resultsosed);
                $sosfolder = $rowsos['is_folder'];
                if ($sosfolder) {
                    if ($sosed) {
                        $ii++;
                    }
                } else {
                    $ii++;
                }
            }

            //Если ((есть соседи, т.е. на верхнем уровне можно выбрать что-то другое)
            // И (уровень доставки больше первого)), то показываем приглашение перейти на уровень выше
            if (($ii > 1) && ($num > 0)) { //Показывать кнопку "снять" если больше 1 вариант выбора у верхнего И (либо есть потомки либо уровень доставки больше первого)
                $pred = __('Выбрано') . ': ' . $city . ' <A href="javascript:UpdateDeliveryJq(\'' . $PIDpr . '\',this)"><img src="' . $pathTemplate . '/images/shop/check_green.svg" alt="" border="0" align="absmiddle">&nbsp;' . __('Выбрать другой способ доставки') . '</A> <BR> ' . $pred;
            }
        }
        if (strlen($pred)) {
            $br = '<BR>';
        } else {
            $br = '';
        } //если хоть одно приглашение есть, то Добавление переноса строки,
    } //Если есть предки, формируем навигацию


    $varamount = 0;
    $chkdone = 0; //По дефолту умолчательная доставка не указана
    while ($row = mysqli_fetch_array($resultvariants)) {

        PHPShopParser::set('deliveryActive', '');
        PHPShopParser::set('deliveryChecked', '');
        PHPShopParser::set('deliveryIcon', '');
        PHPShopParser::set('deliveryMaxSum', '');


        if (!empty($deliveryID)) {//Если присылали идентификатор
            if ($row['id'] == $deliveryID) {
                PHPShopParser::set('deliveryActive', 'active');
                PHPShopParser::set('deliveryChecked', 'checked');
            } else {
                if ($isfolder) { //Если присланный идентификатор папка и работает стартовый файл
                    if ($row['flag'] == 1) { //На случай доставки по умолчанию
                        PHPShopParser::set('deliveryActive', 'active');
                        PHPShopParser::set('deliveryChecked', 'checked');
                        $chkdone = $row['id']; //Если выводится умолчательная доставка, то пометить что выбор завершен
                    }
                }
            }
        } elseif ($engineinc) {//Если НЕ присылали идентификатор, но производится стартовый запуск
            if ($row['flag'] == 1) { //На случай доставки по умолчанию
                PHPShopParser::set('deliveryActive', 'active');
                PHPShopParser::set('deliveryChecked', 'checked');
                $chkdone = $row['id']; //Если выводится умолчательная доставка, то пометить что выбор завершен
            }
        }


        if (((int) $deliveryID > 0 && (int) $row['id'] === $deliveryID) || ($isfolder && (int) $row['flag'] === 1)) {
            PHPShopParser::set('deliveryActive', 'active');
            PHPShopParser::set('deliveryChecked', 'checked');
        }
        if($isfolder && (int) $row['flag'] === 1) {
          //  $chkdone = $row['id']; //Если выводится доставка по умолчанию, то пометить что выбор завершен
        }

        // Получаем количество соседей у вышестоящего.
        $sqlpot = "select * from " . $table . " where (enabled='1' and PID='" . $row['id'] . "') ".$servers." order by num,city";
        $resultpot = mysqli_query($link_db, $sqlpot);
        $pot = mysqli_num_rows($resultpot);

        if ((empty($row['is_folder'])) || ($pot)) {

            PHPShopParser::set('deliveryId', $row['id']);
            PHPShopParser::set('deliveryPayment', $row['payment']);
            PHPShopParser::set('deliveryIcon', $row['icon']);
            PHPShopParser::set('deliveryTitle', $row['city']);

            // Проверка максимальной суммы
            if (!empty($row['sum_max']) and !empty($sum) and $row['sum_max'] <= $sum) {
                PHPShopParser::set('deliveryMaxSum',  __('Превышена максимальная сумма заказа'));
            }

            $varamount++;
            $curid = $row['id'];

            $disp .= ParseTemplateReturn($template, true);
        }
    }

    $query = "select data_fields,city_select from " . $SysValue['base']['delivery'] . " where id=$deliveryID";
    $row = mysqli_fetch_array(mysqli_query($link_db, $query));
    $adresDisp_save = getAdresFields(unserialize($row['data_fields']), $row['city_select']);
    $adresDisp = __("Для заполнения адреса, пожалуйста, выберите удобный способ доставки.");

    if ($varamount === 0) {
        $makechoise = '&nbsp;<input type=radio value=0  name="dostavka_metod" id="dostavka_metod">' . __('[Доставка по умолчанию]');
        $alldone = '<INPUT TYPE="HIDDEN" id="makeyourchoise" VALUE="DONE">';
        $adresDisp = $adresDisp_save;
        $deliveryID = 0;
        $curid = $deliveryID;
    } elseif ($varamount >= 1) {
        //$makechoise = '<OPTION value="' . $deliveryID . '" id="makeyourchoise">Выберите доставку</OPTION>';
        $alldone = '';
    } else {
        $alldone = '<INPUT TYPE="HIDDEN" id="makeyourchoise" VALUE="DONE">';
        $adresDisp = $adresDisp_save;
    }

    if ($varamount == 1) {
        if (!(($curid == $deliveryID)))
            $waytodo = '<IMG onload="UpdateDeliveryJq(' . $curid . ',this);" SRC="' . $pathTemplate . '/images/shop/flag_green.gif" style="display:none;">';
    }

    if ($stop) {
        $makechoise = '';
        $alldone = '<INPUT TYPE="HIDDEN" id="makeyourchoise" VALUE="DONE">';
        $adresDisp = $adresDisp_save;
    } else {
        if ($chkdone)
            $waytodo = '<IMG onload="UpdateDeliveryJq(' . $chkdone . ',this);" SRC="' . $pathTemplate . '/images/shop/flag_green.gif"  style="display:none;">';
    }


    $disp = '<DIV id="seldelivery">' . $pred . $br . $my . '
' . $makechoise . '
' . $disp . '
' . $alldone . $waytodo . '</DIV>';
    
    // Комментарий
    if(!empty($comment)){
    $adresDisp='<div class="well well-sm delivery-comment">'.$comment.'</div>'.$adresDisp;
    }

    if ($obj)
        $obj->set('orderDelivery', $disp);
    else {
        return array("dellist" => $disp, "adresList" => $adresDisp);
    }
}

function getAdresFields($mass, $city_select = null) {
    global $SysValue, $link_db, $PHPShopBase;

    if (!is_array($mass))
        return __("Для данного типа доставки не требуется дополнительных данных", false);
    
    $num = $mass[num];
    asort($num);
    $enabled = $mass[enabled];

    if ($city_select) {
        $disp = "<div id='citylist'>";
        // Cтрана
        if ($city_select == 2) {
            $disabled = "disabled";
            $query = "SELECT country_id, name FROM " . $SysValue['base']['citylist_country'] . " Order BY name";
            $result = mysqli_query($link_db, $query);
            while ($row = mysqli_fetch_array($result)) {
                $disOpt .= "<option value='" . $row['name'] . "' for='" . $row['country_id'] . "'>" . $row['name'] . "</option>";
            }
            if ($enabled['country']['req']) {
                $req = "req";
                $star = '<span class="required">*</span>';
            } else {
                $req = "";
                $star = "";
            }
            $disp .= "$star " . $enabled['country']['name'] . "<p> <select name='country_new' class='citylist form-control $req '><option value='' for='0'>-----------</option>$disOpt</select></p>";
        }

        // регион
        $rfId = 3159; // ID РФ 
        $disOpt = "";
        $query = "SELECT region_id, name FROM " . $SysValue['base']['citylist_region'] . " WHERE country_id = $rfId Order BY name";
        $result = mysqli_query($link_db, $query);
        while ($row = mysqli_fetch_array($result)) {
            $disOpt .= "<option value='" . $row['name'] . "' for='" . $row['region_id'] . "'>" . $row['name'] . "</option>";
        }
        if ($enabled['state']['req']) {
            $req = "req";
            $star = '<span class="required">*</span>';
        } else {
            $req = "";
            $star = "";
        }
        $disp .= "$star " . $enabled['state']['name'] . "<p><select name='state_new' class='citylist form-control $req' $disabled><option value='' for='0'>-----------</option>$disOpt</select></p>";

        //Город
        $disp .= "$star " . $enabled['city']['name'] . "<p> <select name='city_new' class='citylist form-control $req' $disabled><option value='' for='0'>-----------</option></select></p></div>";
    }

    foreach ($num as $key => $value) {
        if ($city_select AND ($key == "state" OR $key == "city" OR $key == "country"))
            continue;
        if ($enabled[$key]['enabled'] == 1) {
            if ($enabled[$key]['req']) {
                $req = 'class="req form-control"';
                $star = '*';
                $required = 'required';
            } else {
                $req = 'class="form-control"';
                $star = "";
                $required = null;
            }
            $disp .= '<p><input type="text" ' . $req . ' value="" name="' . $key . '_new" ' .  $required . ' placeholder="' . $enabled[$key]['name'] . '"></p>';
        }
    }
    return $disp;
}

?>