<?php
 
 
/**
 * Изменение количества новостей на странице
 */
function index_multilanguages_hook($obj, $data, $rout) {
    if($rout=='END') {
        
        $multilanguages = unserialize($data['multilanguages']);

        if($multilanguages['multilanguages_name'][ $_SESSION['lang_id'] ]!='')
            $obj->set('pageTitle', $multilanguages['multilanguages_name'][ $_SESSION['lang_id'] ]);

        if($multilanguages['multilanguages_content'][ $_SESSION['lang_id'] ]!='')
            $obj->set('pageContent', Parser(stripslashes($multilanguages['multilanguages_content'][ $_SESSION['lang_id'] ])));

    }
}
 
$addHandler=array
        (
            'index' => 'index_multilanguages_hook'
 
);
?>