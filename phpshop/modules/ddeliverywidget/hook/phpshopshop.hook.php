<?php
function UID_ddeliverywidget_hook($obj, $dataArray, $rout) {
    if ($rout == 'MIDDLE') {
        
        // API
        include_once 'phpshop/modules/ddeliverywidget/class/ddeliverywidget.class.php';
        $ddeliverywidget = new ddeliverywidget();
        $option = $ddeliverywidget->option();
        
        if($option['prod_enabled']=='1'&&!empty($option['key'])) {
    
            $html = ParseTemplateReturn($GLOBALS['SysValue']['templates']['ddeliverywidget']['ddelivery_prod_template'], true);    
            $obj->set('ddeliveryCart', $html);
        }
    }
}

$addHandler = array
(
    'UID' => 'UID_ddeliverywidget_hook',
);


?>