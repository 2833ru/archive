<?php

/**
 * Шаблон вывода характеристик
 * @param int $value значение характеристики
 * @param int $n ИД характеристики
 * @param int $title заголовок характеристики
 * @param array $vendor массив характеристик
 */
function sorttemplate($value, $n, $title, $vendor) {
    global $PHPShopGUI;
    $i = 1;
    //$value_new[0]=array(__('Нет данных'),false, 'none');
    
    if (is_array($value)) {
        sort($value);
        foreach ($value as $p) {
            $sel = null;
            if (is_array($vendor[$n])) {
                foreach ($vendor[$n] as $value) {

                    if ($value == $p[1])
                        $sel = "selected";
                }
            }elseif ($vendor[$n] == $p[1])
                $sel = "selected";

            $value_new[$i] = array($p[0], $p[1], $sel);
            $i++;
        }
    }

    $value = $PHPShopGUI->setSelect('vendor_array_new[' . $n . '][]', $value_new, 300, null, false, $search = true, false, $size = 1, $multiple = true);

    $disp = $PHPShopGUI->setField($title, $value) .
            $PHPShopGUI->setField(null, $PHPShopGUI->setInputArg(array('type' => 'text', 'placeholder' => __('Ввести другое'), 'size' => '300', 'name' => 'vendor_array_add[' . $n . ']','class'=>'vendor_add')));

    return $disp;
}

/**
 * Панель хактеристик товара
 * @param array $row массив данных
 * @return string 
 */
function tab_sorts($data) {
    global $PHPShopGUI;
    PHPShopObj::loadClass("sort");
    $PHPShopSort = new PHPShopSort($data['category'], false, false, 'sorttemplate', unserialize($data['vendor_array']), false, true);

    $sort = $PHPShopSort->disp;

    if (empty($sort))
        $sort =
                '<p class="text-muted">Для отображения характеристик у товаров необходимо объединить <a href="?path=sort" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-share-alt"></span> Характеристики в группы</a> и выбрать эти группы у <a href="?path=catalog" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-share-alt"></span> Каталогов товаров</a>. Характеристики из выбранных груп появятся в товарах указанных каталогов.</p>';


    return $PHPShopGUI->setCollapse('Характеристики', $sort, $collapse = 'none', $line = true, $icons = false);
}

?>
