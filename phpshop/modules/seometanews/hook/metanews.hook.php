<?php
 
 
/**
 * Изменение meta title description keywords новостей на странице
 */
function ID_hook($obj, $data, $rout) {
 
        //Переменные и их обработка
        $title = trim($data['meta_title']);
        $keywords = trim($data['meta_keywords']);
        $description = trim($data['meta_description']);

        // Мета
        if($title!='')
            $obj->title = $title;
        if($keywords!='')
            $obj->keywords = $keywords;
        if($description!='')
            $obj->description = $description;
}
 
$addHandler=array
        (
            'ID' => 'ID_hook'
 
);
?>