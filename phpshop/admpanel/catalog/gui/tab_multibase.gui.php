<?php
/**
 * Панель мультибазы каталога
 * @param array $data массив данных
 * @return string 
 */
function tab_multibase($option) {
    global $PHPShopGUI;

    $value=array();
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['servers']);
    $data = $PHPShopOrm->select(array('*'), array('enabled'=>"='1'"), array('order' => 'id'), array('limit' => 1000));
    
    $data[1000] = array('host'=>'Главный сайт', 'id'=>1000);
    if (is_array($data)) {
        foreach ($data as $row) {
            $server = preg_split('/i/', $option['servers'], -1, PREG_SPLIT_NO_EMPTY);
            $sel=false;
            if (is_array($server))
                foreach ($server as $v) {
                    if ($row['id'] == $v)
                        $sel = "selected";
                }
            $value[] = array($row['host'], $row['id'], $sel);
        }
        return  $PHPShopGUI->setSelect('servers[]', $value, '300', false, false, false, '300', false,true);
    }
    else return $PHPShopGUI->setHelp('Нет дополнительных витрин. <a href="?path=system.servers&action=new">Создать витрину</a>.');
    
}

?>