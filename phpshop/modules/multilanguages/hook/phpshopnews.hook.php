<?php
 
 
/**
 * Изменение количества новостей на странице
 */
function index_news_multilanguages_hook($obj, $data, $rout) {
    if($rout=='MIDDLE') {
        
        $multilanguages = unserialize($data['multilanguages']);

        if($multilanguages['multilanguages_name'][ $_SESSION['lang_id'] ]!='')
            $obj->set('newsZag', $multilanguages['multilanguages_name'][ $_SESSION['lang_id'] ]);

        if($multilanguages['multilanguages_content'][ $_SESSION['lang_id'] ]!='')
            $obj->set('newsKratko', Parser(stripslashes($multilanguages['multilanguages_content'][ $_SESSION['lang_id'] ])));

    }
}
function ID_news_multilanguages_hook($obj, $data, $rout) {
    if($rout=='MIDDLE') {
        $multilanguages = unserialize($data['multilanguages']);
        
        if($multilanguages['multilanguages_name'][ $_SESSION['lang_id'] ]!='')
            $obj->set('newsZag', $multilanguages['multilanguages_name'][ $_SESSION['lang_id'] ]);

        if($multilanguages['multilanguages_description'][ $_SESSION['lang_id'] ]!='')
            $obj->set('newsKratko', Parser(stripslashes($multilanguages['multilanguages_description'][ $_SESSION['lang_id'] ])));

        if($multilanguages['multilanguages_content'][ $_SESSION['lang_id'] ]!='')
            $obj->set('newsPodrob', Parser(stripslashes($multilanguages['multilanguages_content'][ $_SESSION['lang_id'] ])));

    }
}
 
$addHandler=array
        (
            'index' => 'index_news_multilanguages_hook',
            'ID' => 'ID_news_multilanguages_hook'
 
);
?>