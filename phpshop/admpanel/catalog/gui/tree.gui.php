<?php

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass(array("base", "category", "string", "array"));

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini", true, true);

$PHPShopCategoryArray = new PHPShopCategoryArray();
$CategoryArray = $PHPShopCategoryArray->getArray();

$CategoryArray[0]['name'] = '- Корневой уровень -';
$tree_array = array();
$i = 0;

foreach ($PHPShopCategoryArray->getKey('parent_to.id', true) as $k => $v) {
    foreach ($v as $cat) {
        $tree_array[$k]['sub'][$cat] = $CategoryArray[$cat]['name'];
    }
    $tree_array[$k]['name'] = $CategoryArray[$k]['name'];
    $tree_array[$k]['id'] = $k;
}

// Ссылки
if (is_numeric($_GET['id']) or $_GET['action'] == 'new') {
    $currentId = $_GET['id'];
    $prePath = '?path=catalog&id=';
    $addNodes = false;
} else {
    $currentId = $_GET['cat'];
    $prePath = '?path=catalog&cat=';
    $addNodes = true;
}


if (is_array($tree_array[0]['sub']))
    foreach ($tree_array[0]['sub'] as $k => $v) {

        $result[$i] = array(
            'text' => PHPShopString::win_utf8($v)
        );

        $nodes = treegenerator($tree_array[$k], 100);

        if (is_array($nodes)) {
            $result[$i]['nodes'] = $nodes;

            if (!empty($addNodes))
                $result[$i]['selectable'] = false;
            else
                $result[$i]['selectable'] = true;
        }

        if ($k == $currentId) {
            $result[$i]['state']['expanded'] = true;
            $result[$i]['state']['selected'] = true;
        }

        $result[$i]['href'] = $prePath . $k;
        $i++;
    }

// Построение дерева категорий
function treegenerator($array, $m) {
    global $tree_array, $currentId, $prePath,$addNodes;
    static $i;

    if (empty($i))
        $i = $m;

    if (is_array($array['sub'])) {
        foreach ($array['sub'] as $k => $v) {

            $nodes = treegenerator($tree_array[$k], $i);

            $result[$i] = array(
                'text' => PHPShopString::win_utf8($v).'<span class="hide">'.$k.'</span>'
            );

            if (is_array($nodes)) {
                $result[$i]['nodes'] = $nodes;
                $result[$i]['selectable'] = false;

                if (!empty($addNodes))
                    $result[$i]['selectable'] = false;
                else
                    $result[$i]['selectable'] = true;
            }

            if ($k == $currentId) {
                $result[$i]['state']['expanded'] = true;
                $result[$i]['state']['selected'] = true;
            }

            $result[$i]['href'] = $prePath . $k;
            $i++;
        }
    }
    return $result;
}

if (!empty($addNodes)) {



    $result[] = array(
        'text' => PHPShopString::win_utf8('Неопределенные товары'),
        'selectable' => false,
        'nodes' => array(
            1000001 => array(
                'text' => PHPShopString::win_utf8('Загруженные CRM'),
                'icon' => 'glyphicon glyphicon-hdd',
                'href' => '?path=catalog&cat=1000001',
            ),
            1000002 => array(
                'text' => PHPShopString::win_utf8('Загруженные CSV'),
                'icon' => 'glyphicon glyphicon-import',
                'href' => '?path=catalog&cat=0&sub=csv'
            )
            ,
            1000004 => array(
                'text' => PHPShopString::win_utf8('Удаленные'),
                'icon' => 'glyphicon glyphicon-trash',
                'href' => '?path=catalog&cat=1000004'
            )
        )
    );
}

if (in_array($_GET['cat'], array(1000001, 1000002, 1000004)) or $_GET['sub'] == 'csv') {
    if ($_GET['sub'] == 'csv')
        $_GET['cat'] = 1000002;
    $result[count($result) - 1]['nodes'][$_GET['cat']]['state']['expanded'] = true;
    $result[count($result) - 1]['nodes'][$_GET['cat']]['state']['selected'] = true;
}

header("Content-Type: application/json");
echo json_encode($result);
?>