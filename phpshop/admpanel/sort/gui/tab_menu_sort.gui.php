<?php

/**
 * Дополнительная навигация
 */
function tab_menu_sort() {
    global $PHPShopInterface, $SortCategoryArray, $help;

    $tree = '<table class="tree table table-hover">
        <tr class="treegrid-0 data-tree">
		<td class="no_tree"><a href="?path=sort">Показать все</a></td>
	</tr>';
    if (is_array($SortCategoryArray))
        foreach ($SortCategoryArray as $k => $v) {
            $tree.='<tr class="treegrid-' . $k . ' data-tree">
		<td class="no_tree"><a href="?path=sort&cat=' . $k . '">' . $v['name'] . '</a><span class="pull-right">' . $PHPShopInterface->setDropdownAction(array('edit', '|', 'delete', 'id' => $k)) . '</span></td>
	</tr>';
        }
    $tree.='</table><script>
    var cat="' . intval($_GET['cat']) . '";
    </script>';

    $help = '<p class="text-muted">Для отображения характеристик у товаров, необходимо объединить их в группы и выбрать эти группы у <a href="?path=catalog" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-share-alt"></span> Каталогов товаров</a>. Характеристики из выбранных групп появятся в товарах указанных каталогов.</p>';

    return $tree;
}

?>