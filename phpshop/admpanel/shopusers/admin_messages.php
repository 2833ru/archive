<?php

$TitlePage = __('Пользователи') . ' / ' . __("Сообщения");

function format_mysql_date($datetime, $style = "y.m.d h:i:s") {
    if (mb_strlen($datetime, "UTF-8") != 19)
        return $datetime;
    $ex = explode(" ", $datetime);
    $ex_date = explode("-", $ex[0]);
    $ex_time = explode(":", $ex[1]);
    if ((count($ex_date) == 3) && (count($ex_time) == 3)) {
        $text_month = "";
        switch ($ex_date[1]) {
            case 1: $text_month = "января";
                break;
            case 2: $text_month = "февраля";
                break;
            case 3: $text_month = "марта";
                break;
            case 4: $text_month = "апреля";
                break;
            case 5: $text_month = "мая";
                break;
            case 6: $text_month = "июня";
                break;
            case 7: $text_month = "июля";
                break;
            case 8: $text_month = "августа";
                break;
            case 9: $text_month = "сентября";
                break;
            case 10: $text_month = "октября";
                break;
            case 11: $text_month = "ноября";
                break;
            case 12: $text_month = "декабря";
                break;
        }
        $style = str_replace("y", $ex_date[0], $style);
        $style = str_replace("m", $ex_date[1], $style);
        $style = str_replace("d", $ex_date[2], $style);
        $style = str_replace("f", $text_month, $style);
        $style = str_replace("h", $ex_time[0], $style);
        $style = str_replace("i", $ex_time[1], $style);
        $style = str_replace("s", $ex_time[2], $style);
        return $style;
    }
    return $datetime;
}

function actionStart() {
    global $PHPShopInterface;

    $PHPShopInterface->addJSFiles('./shopusers/gui/shopusers.gui.js');


    $PHPShopInterface->action_select['Поиск'] = array(
        'name' => 'Расширенный поиск',
        'action' => 'search enabled'
    );


    $PHPShopInterface->setActionPanel(__('Пользователи') . ' / ' . __("Сообщения"), array('Поиск', '|', 'Удалить выбранные'), false);
    $PHPShopInterface->setCaption(array(null, "2%"), array("Имя", "20%"), array("E-mail", "15%"), array("Тема", "40%"), array("Дата", "10%"), array("", "10%"), array("Статус &nbsp;&nbsp;&nbsp;", "10%", array('align' => 'right')));

    // Поиск
    $where = null;
    $limit = 300;
    if (is_array($_GET['where'])) {
        foreach ($_GET['where'] as $k => $v) {
            if ($v != '' and $v != 'none')
                $where.= ' ' . PHPShopSecurity::TotalClean($k) . ' like "%' . PHPShopSecurity::TotalClean($v) . '%" or';
        }

        if ($where)
            $where = 'where' . substr($where, 0, strlen($where) - 2);

        $limit = 1000;
    }

    // Таблица с данными
    $PHPShopOrm = new PHPShopOrm();
    $PHPShopOrm->debug = false;
    $PHPShopOrm->sql = 'SELECT a.*, b.name, b.login FROM ' . $GLOBALS['SysValue']['base']['messages'] . ' AS a 
        LEFT JOIN ' . $GLOBALS['SysValue']['base']['shopusers'] . ' AS b ON a.UID = b.id ' . $where . ' ORDER BY a.DateTime desc limit ' . $limit;

    $data = $PHPShopOrm->select();
    if (is_array($data))
        foreach ($data as $row) {

            if (empty($row['enabled']))
                $status = '<span class="glyphicon glyphicon-envelope" style="color:red"></span>';
            else
                $status = '<span class="glyphicon glyphicon-envelope"></span>';

            $PHPShopInterface->setRow(
                    $row['ID'], array('name' => $row['name'], 'link' => '?path=shopusers.messages&id=' . $row['ID'], 'align' => 'left'), array('name' => $row['login'], 'link' => '?path=shopusers&id=' . $row['UID'] . '&return=' . $_GET['path']), $row['Subject'], array('order' => strtotime($row['DateTime']), 'name' => format_mysql_date($row['DateTime'], 'd-m-y h:i')), array('action' => array('edit', 'delete', 'id' => $row['ID']), 'align' => 'center'), array('order' => $row['enabled'], 'name' => $status, 'align' => 'right'));
        }
    $PHPShopInterface->Compile();
}

/**
 * Поиск сообщений расширенный
 */
function actionAdvanceSearch() {
    global $PHPShopInterface;

    $PHPShopInterface->field_col = 2;

    $searchforma = $PHPShopInterface->setInputArg(array('type' => 'text', 'name' => 'where[b.name]', 'placeholder' => __('Имя'), 'class' => 'pull-left', 'value' => $_REQUEST['words']));
    $searchforma.='<br><br>';
    $searchforma.= $PHPShopInterface->setInputArg(array('type' => 'text', 'name' => 'where[b.login]', 'size' => 300, 'placeholder' => __('E-mail'), 'class' => 'pull-left', 'value' => $_REQUEST['words']));
    $searchforma.='<br><br>';
    $searchforma.= $PHPShopInterface->setInputArg(array('type' => 'text', 'name' => 'where[a.Subject]',  'placeholder' => __('Заголовок'), 'class' => 'pull-left', 'value' => $_REQUEST['words']));
    $searchforma.='<br><br>';
        $searchforma.= $PHPShopInterface->setInputArg(array('type' => 'text', 'name' => 'where[a.Message]',  'placeholder' => __('Сообщение'), 'class' => 'pull-left', 'value' => $_REQUEST['words']));
    $searchforma.= $PHPShopInterface->setInputArg(array('type' => 'hidden', 'name' => 'path', 'value' => 'shopusers.messages'));

    $PHPShopInterface->_CODE.=$searchforma;

    exit($PHPShopInterface->getContent() . '<p class="clearfix"> </p>');
}

// Обработка событий
$PHPShopInterface->getAction();
?>