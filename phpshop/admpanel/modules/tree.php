<?php

$_classPath="../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("admgui");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
$PHPShopBase->chekAdmin();

PHPShopObj::loadClass("system");
$PHPShopSystem = new PHPShopSystem();

class MainCatalogTree extends CatalogTree {

    function MainCatalogTree($base) {
        if($_COOKIE['winOpenType'] == 'highslide') $this->dot="./catalog/";
        else $this->dot="";
        parent::CatalogTree($base);
    }

    function addcat($n,$id,$name,$link=false,$icon=false) {
        $name=__($name);
        $this->dis.="d.add($n,$id,'$name','$link','','','','$icon');";
    }

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "xhtml11.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?=$GLOBALS['PHPShopLangCharset']?>">
        <LINK href="../css/dtree.css" type=text/css rel=stylesheet>
        <SCRIPT language=JavaScript1.2 src="../java/dtree.js" type=text/javascript></SCRIPT>
        <script language="JavaScript1.2" src="../java/phpshop.js" type="text/javascript"></script>
    </head>
    <body bottommargin="0" rightmargin="0" topmargin="0" leftmargin="0" bgcolor="#ffffff">
        <div style="padding:7px">
            <?
            // Дерево каталогов
            $CatalogTree = new MainCatalogTree();
            $CatalogTree->addcat(0,-1,'<b>Модули</b>','');
            $CatalogTree->addcat(1,0,'Дизайн','','../img/imgfolder.gif');
            $CatalogTree->addcat(11,1,'Шаблоны','admin_modules_content.php?pid=template','../img/imgfolder.gif');
            $CatalogTree->addcat(12,1,'Блоки','admin_modules_content.php?pid=text','../img/imgfolder.gif');
            $CatalogTree->addcat(13,1,'Формы','admin_modules_content.php?pid=form','../img/imgfolder.gif');
            $CatalogTree->addcat(14,1,'Декорация','admin_modules_content.php?pid=decorate','../img/imgfolder.gif');
            $CatalogTree->addcat(20,0,'Утилиты','','../img/imgfolder.gif');
            $CatalogTree->addcat(271,20,'Редакторы','admin_modules_content.php?pid=wiswig','../img/imgfolder.gif');
            $CatalogTree->addcat(21,20,'Яндекс','admin_modules_content.php?pid=yandex','../img/imgfolder.gif');
            $CatalogTree->addcat(22,20,'Платежные системы','admin_modules_content.php?pid=pay','../img/imgfolder.gif');
            $CatalogTree->addcat(23,20,'Отладка кода','admin_modules_content.php?pid=develop','../img/imgfolder.gif');
            $CatalogTree->addcat(24,20,'Системные','admin_modules_content.php?pid=system','../img/imgfolder.gif');
            $CatalogTree->addcat(25,20,'SEO','admin_modules_content.php?pid=seo','../img/imgfolder.gif');
            $CatalogTree->addcat(26,20,'Отчеты','admin_modules_content.php?pid=stat','../img/imgfolder.gif');
            $CatalogTree->addcat(30,0,'Пользователи','','../img/imgfolder.gif');
            $CatalogTree->addcat(31,30,'Социальные сети','admin_modules_content.php?pid=soc','../img/imgfolder.gif');
            $CatalogTree->addcat(32,30,'Заказы','admin_modules_content.php?pid=order','../img/imgfolder.gif');
            $CatalogTree->addcat(33,30,'Фильтры','admin_modules_content.php?pid=sort','../img/imgfolder.gif');
            $CatalogTree->addcat(40,0,'Заработок','','../img/imgfolder.gif');
            $CatalogTree->addcat(41,40,'Партнерские программы','admin_modules_content.php?pid=partner','../img/imgfolder.gif');
            $CatalogTree->addcat(42,40,'Биржи ссылок','admin_modules_content.php?pid=links','../img/imgfolder.gif');

  
            $CatalogTree->disp();
            ?>
        </div>
    </body>
</html>

