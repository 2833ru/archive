<?php

/**
 * Изменение количества новостей на странице
 */
function news_element_hook($obj, $data, $rout) {
 
         if($rout == 'START'){
            $obj->limit=3; 
         }    
}
 
$addHandler=array
        (
            'index' => 'news_element_hook'
 
);

?>
