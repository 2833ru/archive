<?php

/**
 * Хук замены обозначения страниц в пагинаторе
 * @param Obj $obj объект
 * @param string $old_paginator содеражание пагинатора начального
 * @param string $rout 
 */
function setPaginator_hook($obj, $old_paginator, $rout) {
    if ($rout == 'END') {
      
      // Какие фразы менять
      $old=array('1-3','3-6');
      
      // На что менять
      $new=array('1','2');
      
      $obj->set('productPageNav', str_replace($old, $new, $old_paginator));
    }
}

$addHandler = array
    (
    'setPaginator' => 'setPaginator_hook'
);
?>
