<?php

session_start();
if (!empty($_SESSION['idPHPSHOP']) and !empty($_REQUEST['words'])) {
    $_classPath = "../../";
    include($_classPath . "class/obj.class.php");
    PHPShopObj::loadClass('xml');

    $path = 'http://faq.phpshop.ru/base-xml-manager/search/xml.php?json=true&words=' . $_REQUEST['words'];
    $db = @xml2array($path, "row", true);
    

    if(!empty($db['name']))
        $array[0] = $db;
    else $array= $db;
    

    $result = null;
    if (is_array($array))
        foreach ($array as $row) {
            $result.= '<a class="list-group-item" href="http://faq.phpshop.ru/page/' . $row['link'] . '.html" target="_blank">' . $row['name'] . '</a>';
        }

    if ($result)
        echo '<div class="list-group search-list">' . $result . '</div>';
}
?>