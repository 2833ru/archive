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
    global $UserStatus;
    $return=array();
    $sql='SELECT a.*, b.key FROM '.$GLOBALS['SysValue']['base']['modules'].' AS a LEFT OUTER JOIN '.$GLOBALS['SysValue']['base']['modules_key'].' AS b ON a.path = b.path where a.path="'.$path.'"';

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
        $return[3]=$row['key'];
    }
    else {

        $return[0]="white";
        $return[1]= "
<BUTTON style=\"width: 10em; height: 2.2em; margin-left:5\"  onclick=\"DoUpdateModules('on','$path')\">
<img src=\"img/icon-activate.gif\"  border=\"0\" align=\"absmiddle\">
Установить
</BUTTON>
                ";
        $return[2]=null;
        $return[3]=$row['key'];
    }
    return $return;
}

function actionStart() {
    global $PHPShopInterface,$UserStatus;
    $PHPShopInterface->razmer="height:600px;";

    if(CheckedRules($UserStatus["module"],0))
        $PHPShopInterface->setCaption(array("Название","20%"),array("Описание","50%"),array("Установлено","10%"),array("Директория","10%"));
    else $PHPShopInterface->setCaption(array("Управление","10%"),array("Название","20%"),array("Описание","50%"),array("Установлено","10%"),array("Директория","10%"));


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

                    if(!empty($Info['trial']) and empty($ChekInstallModule[3])) {
                        $trial=' (Trial 30 дней)';
                    }
                    else $trial=null;

                    $ModuleHomePage='<img src="../modules/'.$file.'/install/'.$Info['icon'].'" align="absmiddle">
<a href="http://wiki.phpshop.ru/index.php/Modules#'.str_replace(' ', '_', $Info['name']).'" target="_blank" title="Описание модуля" class="blue">'.$Info['name'].' '.$Info['version'].$trial.'</a>';

                    if(CheckedRules($UserStatus["module"],0))
                        $PHPShopInterface->setRow($i,$ModuleHomePage,$Info['description'],$InstallDate,"phpshop/modules/".$file);
                    else  $PHPShopInterface->setRow($i,$ChekInstallModule[1],$ModuleHomePage,$Info['description'],$InstallDate,"phpshop/modules/".$file);
                    $i++;
                }
            }
        }
        closedir($dh);
    }
    $PHPShopInterface->Compile();
}


?>