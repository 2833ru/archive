<?php

/**
 * Панель заголовков 
 * @param array $row массив данных
 * @return string 
 */
function tab_headers($data, $option) {
    global $PHPShopGUI;

    $disp = null;

    if ($option == 'catalog') {
        
        $disp.=$PHPShopGUI->setField("Title", '
<textarea class="form-control" style="height:100px;" name="title_shablon3_new">' . $data['title_shablon3'] . '</textarea>
<div class="btn-group" role="group" aria-label="...">
<input type="button" value="Каталог" data-seo="@Catalog@" data-target="title_shablon3_new" class="seo-button btn btn-default btn-sm">
<input type="button" value="Общий" data-seo="@System@" data-target="title_shablon3_new" class="seo-button btn btn-default btn-sm">
</div>
');

        $disp.=$PHPShopGUI->setField("Description", '
<textarea class="form-control" style="height:100px" name="descrip_shablon3_new" id="ShablonD">' . $data['descrip_shablon3'] . '</textarea>
    <div class="btn-group" role="group" aria-label="...">
<input type="button" value="Каталог" data-seo="@Catalog@" data-target="descrip_shablon3_new" class="seo-button btn btn-default btn-sm">
<input type="button" value="Общий" data-seo="@System@" data-target="descrip_shablon3_new" class="seo-button btn btn-default btn-sm">
 </div>');

        $disp.=$PHPShopGUI->setField("Keywords", '
<textarea class="form-control" style="height:100px" name="keywords_shablon3_new" id="ShablonK">' . $data['keywords_shablon3'] . '</textarea>
     <div class="btn-group" role="group" aria-label="...">
<input type="button" value="Каталог" data-seo="@Catalog@" data-target="keywords_shablon3_new" class="seo-button btn btn-default btn-sm">
<input type="button" value="Общий" data-seo="@System@" data-target="keywords_shablon3_new" class="seo-button btn btn-default btn-sm">
<input type="button" value="Автоподбор" data-seo="@Generator@" data-target="keywords_shablon3_new" class="seo-button btn btn-default btn-sm">
</div>');
    }



    if ($option == 'podcatalog') {

        $disp.=$PHPShopGUI->setField("Title", '
<textarea class="form-control" style="height:100px" name="title_shablon_new">' . $data['title_shablon'] . '</textarea>
    <div class="btn-group" role="group" aria-label="...">
 <input type="button" value="Каталог" data-seo="@Catalog@" data-target="title_shablon_new" class="seo-button btn btn-default btn-sm">
<input type="button" value="Подкаталог" data-seo="@Podcatalog@" data-target="title_shablon_new" class="seo-button btn btn-default btn-sm">
<input type="button" value="Общий" data-seo="@System@" data-target="title_shablon_new" class="seo-button btn btn-default btn-sm">
</div>
');

        $disp.=$PHPShopGUI->setField("Description", '
<textarea class="form-control" style="height:100px" name="descrip_shablon_new">' . $data['descrip_shablon'] . '</textarea>
    <div class="btn-group" role="group" aria-label="...">
 <input type="button" value="Каталог" data-seo="@Catalog@" data-target="descrip_shablon_new" class="seo-button btn btn-default btn-sm">
<input type="button" value="Подкаталог" data-seo="@Podcatalog@" data-target="descrip_shablon_new" class="seo-button btn btn-default btn-sm">
<input type="button" value="Общий" data-seo="@System@" data-target="descrip_shablon_new" class="seo-button btn btn-default btn-sm">
 </div>');

        $disp.=$PHPShopGUI->setField("Keywords", '
<textarea class="form-control" style="height:100px" name="keywords_shablon_new">' . $data['keywords_shablon'] . '</textarea>
     <div class="btn-group" role="group" aria-label="...">
 <input type="button" value="Каталог" data-seo="@Catalog@" data-target="keywords_shablon_new" class="seo-button btn btn-default btn-sm">
<input type="button" value="Подкаталог" data-seo="@Podcatalog@" data-target="keywords_shablon_new" class="seo-button btn btn-default btn-sm">
<input type="button" value="Общий" data-seo="@System@" data-target="keywords_shablon_new" class="seo-button btn btn-default btn-sm">
<input type="button" value="Автоподбор" data-seo="@Generator@" data-target="keywords_shablon_new" class="seo-button btn btn-default btn-sm">
</div>');
    }
    
    if ($option == 'product') {

        $disp.=$PHPShopGUI->setField("Title", '
<textarea class="form-control" style="height:100px" name="title_shablon2_new">' . $data['title_shablon2'] . '</textarea>
    <div class="btn-group" role="group" aria-label="...">
 <input type="button" value="Каталог" data-seo="@Catalog@" data-target="title_shablon2_new" class="seo-button btn btn-default btn-sm">
<input type="button" value="Подкаталог" data-seo="@Podcatalog@" data-target="title_shablon2_new" class="seo-button btn btn-default btn-sm">
<input type="button" value="Товар" data-seo="@Product@" data-target="title_shablon2_new" class="seo-button btn btn-default btn-sm">
<input type="button" value="Общий" data-seo="@System@" data-target="title_shablon2_new" class="seo-button btn btn-default btn-sm">
</div>
');

        $disp.=$PHPShopGUI->setField("Description", '
<textarea class="form-control" style="height:100px" name="descrip_shablon2_new">' . $data['descrip_shablon2'] . '</textarea>
    <div class="btn-group" role="group" aria-label="...">
 <input type="button" value="Каталог" data-seo="@Catalog@" data-target="descrip_shablon2_new" class="seo-button btn btn-default btn-sm">
<input type="button" value="Подкаталог" data-seo="@Podcatalog@" data-target="descrip_shablon2_new" class="seo-button btn btn-default btn-sm">
<input type="button" value="Товар" data-seo="@Product@" data-target="descrip_shablon2_new" class="seo-button btn btn-default btn-sm">
<input type="button" value="Общий" data-seo="@System@" data-target="descrip_shablon2_new" class="seo-button btn btn-default btn-sm">
 </div>');

        $disp.=$PHPShopGUI->setField("Keywords", '
<textarea class="form-control" style="height:100px" name="keywords_shablon2_new">' . $data['keywords_shablon2'] . '</textarea>
     <div class="btn-group" role="group" aria-label="...">
 <input type="button" value="Каталог" data-seo="@Catalog@" data-target="keywords_shablon2_new" class="seo-button btn btn-default btn-sm">
<input type="button" value="Подкаталог" data-seo="@Podcatalog@" data-target="keywords_shablon2_new" class="seo-button btn btn-default btn-sm">
<input type="button" value="Товар" data-seo="@Product@" data-target="keywords_shablon2_new" class="seo-button btn btn-default btn-sm">
<input type="button" value="Общий" data-seo="@System@" data-target="keywords_shablon2_new" class="seo-button btn btn-default btn-sm">
</div>');
    }


    return $disp;
}

?>