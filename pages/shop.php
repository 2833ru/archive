<?

if($SysValue['nav']['nav']=="CID"){

   if($SysValue['nav']['id'] == "00")
   header("Location: ../map/");
   
  // Проверка вложенности
  $podcatalog_id = array_keys($LoadItems['CatalogKeys'],$SysValue['nav']['id']);
  if(count($podcatalog_id)>0) {
	$SysValue['other']['DispShop']=DispCatalogTree($SysValue['nav']['id']);
	$SysValue['other']['DispShop'].=DispKratko($podcatalog_id,1,$SysValue['nav']['id']);       //MOD!!
  } else {
	$SysValue['other']['DispShop']=DispKratko($SysValue['nav']['id']);
  }
  }
       elseif($SysValue['nav']['nav']=="UID")
          $SysValue['other']['DispShop']=DispPodrobno($SysValue['nav']['id']);
			       else include("pages/error.php");
				   

if($SysValue['other']['DispShop'] == 404){
header("HTTP/1.0 404 Not Found");
header("Status: 404 Not Found");
include("pages/error.php");
}
				   
// Подключаем шаблон 
@ParseTemplate($SysValue['templates']['shop']);
?>

	