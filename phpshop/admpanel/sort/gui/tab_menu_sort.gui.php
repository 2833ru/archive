<?php

/**
 * Дополнительная навигация
 */
function tab_menu_sort() {
    global $PHPShopInterface, $SortCategoryArray, $help;

    $tree = '<table class="tree table table-hover">
        <tr class="treegrid-0 data-tree">
		<td class="no_tree"><a href="?path=sort">'.__('Показать все').'</a></td>
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

    $help = '<p class="text-muted">'.__('Размер в одном каталоге может быть 1200*1000, а в другом 1мм. Чтобы не выводить в фильтре все значения, используйте Группы. Добавьте в Группу свой набор хар-к, и привяжите к нужным <a href="?path=catalog&action=new" class=""><span class="glyphicon glyphicon-share-alt"></span> Каталогам</a>.<br><br>Чтобы вручную не создавать Группы, включите опцию <a href="?path=system" target="_blank">Кешировать значения фильтра</a>, или модуль <a href="https://docs.phpshop.ru/moduli/prodazhi/umniy-poisk-elastica" target="_blank">Умный поиск</a> – он скроет пустые значения автоматически').'.</p>';

    return $tree;
}

?>