<?php

$TitlePage="Модули";



// Информация по модулю
function GetModuleInfo($name) {
    $path="../../modules/".$name."/install/module.xml";
    if(function_exists("xml_parser_create")) {
        if(@$db=readDatabase($path,"module"))
            return $db[0];
    }
}



function ChekInstallModule($path) {
    $sql="select * from ".$GLOBALS['SysValue']['base']['modules']." where path='$path'";
    $result=mysql_query($sql);
    $row = mysql_fetch_array($result);
    if(mysql_num_rows($result)>0) {
        $return[0]="#C0D2EC";
        $return[1]= "
<BUTTON style=\"width: 10em; height: 2.2em; margin-left:5\"  onclick=\"DoUpdateModules('off','$path')\">
<img src=\"img/icon-deactivate.gif\" border=\"0\" align=\"absmiddle\">
Отключить
</BUTTON>
                ";
        $return[2]=$row['date'];
    }
    else {
        $return[0]="white";
        $return[1]= "
<BUTTON style=\"width: 10em; height: 2.2em; margin-left:5\"  onclick=\"DoUpdateModules('on','$path')\">
<img src=\"img/icon-activate.gif\"  border=\"0\" align=\"absmiddle\">
Установить
</BUTTON>
";
        $return[2]="";
    }
    return $return;
}

function actionStart() {
    global $PHPShopInterface;
    $PHPShopInterface->razmer="height:600;";

    $PHPShopInterface->setCaption(array("Управление","10%"),array("Название","20%"),array("Описание","50%"),array("Установлено","10%"),array("Директория","10%"));

    $path="../../modules/";
    $i=1;
    if (@$dh = opendir($path)) {
        while (($file = readdir($dh)) !== false) {
            if ($file != "." && $file != "..") {

                if(is_dir($path.$file)) {

                    // Информация по модулю
                    $Info = GetModuleInfo($file);
                   
                    $ChekInstallModule=ChekInstallModule($file);
                    // Дата установки
                    if(!empty($ChekInstallModule[2])) $InstallDate=date("d-m-y H:s",$ChekInstallModule[2]);
                    else $InstallDate="";

                    $ModuleHomePage='<img src="../modules/'.$file.'/install/'.$Info['icon'].'" align="absmiddle">
<a href="http://wiki.phpshop.ru/index.php/Modules#'.$Info['name'].'" target="_blank" title="Описание модуля" class="blue">'.$Info['name'].' '.$Info['version'].'</a>';
                    $PHPShopInterface->setRow($i,$ChekInstallModule[1],$ModuleHomePage,$Info['description'],$InstallDate,"phpshop/modules/".$file);
                    $i++;
                }
            }
        }
        closedir($dh);
    }
    $PHPShopInterface->Compile();
}


?>