<?php

/**
 * Изменение количества новостей на странице
 */
function news_element_hook($obj, $data, $rout) {
 
         if($rout == 'START'){
            $obj->disp_only_index=false; 
         }    
}
 
$addHandler=array
        (
            'index' => 'news_element_hook'
 
);

?>
