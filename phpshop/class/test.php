<?

function Test(){
$arg = func_get_args();
foreach($arg as $v) echo $v;
}


class A{

	function A(){
	$this->B(func_get_args());
	}
	
    function B($arg){
    foreach($arg as $v) echo $v;
	}
	


}


//$test = new A(1,2,3);

class C extends A{

      function C(){
	  parent::B(func_get_args());
	  }
}

//$test2 = new C(1,2,3,5,6);

include("obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("category");
$PHPShopBase = new PHPShopBase("../inc/config.ini");


class Test extends PHPShopArray{
     
	 function Test(){
	 $this->objBase=$GLOBALS['SysValue']['base']['table_name'];
	 parent::PHPShopArray("id","parent_to","name");
	 }
	 
	 

}
/*
$catalog = new PHPShopCategory();
$array=$catalog->getArray();
echo "<pre>";
//print_r($array);

$parent=$catalog->getKey("id.parent_to");
print_r($parent);


function Keys($id){
global $parent,$array;
if(!empty($parent[$id])) {
   @$str.=$array[$id]['name'].";";
   @$str.=Keys($parent[$id]);
   }
   else return $array[$id]['name'].";";
return $str;
}
*/

function pdsCat($id,$parent,$array){
if(!empty($parent[$id])) {
   @$str.=$array[$id]['name'].";";
   @$str.=pdsCat($parent[$id]);
   }
   else return $array[$id]['name'].";";
return $str;
}

$PC = new PHPShopCategory();
$parent=$PC->getKey("id.parent_to");
  $pdsCat=pdsCat(3,$parent,$PC->getArray());
	echo $pdsCat;
?>