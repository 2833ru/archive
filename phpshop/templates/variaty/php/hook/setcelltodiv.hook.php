<?php
/*
* Изменение сетки между товарами
*/
function setcell_div_hook($obj,$arg) {
 
    $div=null;
    $panel=array('panel_l','panel_r','panel_l','panel_r','panel_l');
 
    foreach($arg as $key=>$val) {
        if(!empty($val)) {
            $div.='<li class="">'.$val.'</li>';
        }
    }
 
    return $div;
}
 
$addHandler=array
        (
        'setCell'=>'setcell_div_hook'
 
);
?>