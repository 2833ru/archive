<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  ������ ������ ����-�����           |
+-------------------------------------+
*/

// ��������� ����� ��� �����������
function ReturnCIDmeta2($n,$flag,$tip){
global $LoadItems;

$cat=$LoadItems['Catalog'][$n]['parent_to'];

if($cat != 0){
if($tip == 0) $Shablon=$LoadItems['System'][$flag.'_shablon'];
elseif($tip == 1) $Shablon=ReturnData("","where id=".$n,$flag);
elseif($tip == 2) $Shablon=ReturnData("","where id=".$n,$flag.'_shablon');
}else {
       if($tip == 0) $Shablon=$LoadItems['System'][$flag.'_shablon3'];
       elseif($tip == 1) $Shablon=ReturnData("","where id=".$n,$flag);
       elseif($tip == 2) $Shablon=ReturnData("","where id=".$n,$flag.'_shablon3');
       }

if($tip !=1){


if($cat != 0){
$Catalog=$LoadItems['Catalog'][$cat]['name'];
$Podcatalog=$LoadItems['Catalog'][$n]['name'];
$Title=$LoadItems['System'][$flag];


$Shablon=str_replace("@Catalog@", $Catalog, $Shablon);
$Shablon=str_replace("@Podcatalog@", $Podcatalog, $Shablon);
$Shablon=str_replace("@System@", $Title, $Shablon);
}
 else {
      $Catalog=$LoadItems['Catalog'][$n]['name'];
      $Title=$LoadItems['System'][$flag];
      $Shablon=str_replace("@Catalog@", $Catalog, $Shablon);
      $Shablon=str_replace("@Podcatalog@", $Podcatalog, $Shablon);
      $Shablon=str_replace("@System@", $Title, $Shablon);
 }

if($flag == "keywords"){
$Generator=GetProductContent("","where id=".$n,"content");
$Shablon=str_replace("@Generator@", $Generator, $Shablon);}
}

return $Shablon;
}


// ��������� ����� ��� ������
function ReturnUIDmeta($n,$flag,$tip){
global $LoadItems;


if($tip == 0) $Shablon=$LoadItems['System'][$flag.'_shablon2'];
elseif($tip == 1) $Shablon=ReturnData(2,"where id=".$n,$flag);
elseif($tip == 2) $Shablon=ReturnData(2,"where id=".$n,$flag.'_shablon');


if($tip !=1){
$cat=$LoadItems['Product'][$n]['category'];
$parent=$LoadItems['Catalog'][$cat]['parent_to'];
$Catalog=$LoadItems['Catalog'][$parent]['name'];
$Podcatalog=$LoadItems['Catalog'][$cat]['name'];
$Product=$LoadItems['Product'][$n]['name'];
$Title=$LoadItems['System'][$flag];


$Shablon=str_replace("@Catalog@", $Catalog, $Shablon);
$Shablon=str_replace("@Podcatalog@", $Podcatalog, $Shablon);
$Shablon=str_replace("@Product@", $Product, $Shablon);
$Shablon=str_replace("@System@", $Title, $Shablon);
if($flag == "keywords"){
$Generator=GetProductContent("2","where id=".$n,"content");
$Shablon=str_replace("@Generator@", $Generator, $Shablon);}
}

// 404 ������
if(empty($LoadItems['Product'][$n]['name'])) $Shablon=$GLOBALS['SysValue']['lang']['error_page'];

return $Shablon;
}


// ����� ������
function ReturnData($from,$sql,$pole)// ����� ���� ��� �������
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name'.$from]." ".$sql."";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
$name=$row[$pole];
@$SysValue['sql']['num']++;
return @$name;
}



switch ($SysValue['nav']['path']){

     case("shop"):

	 if($SysValue['nav']['nav']=="CID"){
$title_enabled=$LoadItems['Catalog'][$SysValue['nav']['id']]['title_enabled'];
$descrip_enabled=$LoadItems['Catalog'][$SysValue['nav']['id']]['descrip_enabled'];
$keywords_enabled=$LoadItems['Catalog'][$SysValue['nav']['id']]['keywords_enabled'];
	   $title=ReturnCIDmeta2($SysValue['nav']['id'],"title",$title_enabled);
	   $keywords=ReturnCIDmeta2($SysValue['nav']['id'],"keywords",$keywords_enabled);
	   $metas=ReturnCIDmeta2($SysValue['nav']['id'],"descrip",$descrip_enabled);
	  }
	  if($SysValue['nav']['nav']=="UID") {
	$title_enabled=$LoadItems['Product'][$SysValue['nav']['id']]['title_enabled'];
	$descrip_enabled=$LoadItems['Product'][$SysValue['nav']['id']]['descrip_enabled'];
    $keywords_enabled=$LoadItems['Product'][$SysValue['nav']['id']]['keywords_enabled'];
		$title=ReturnUIDmeta($SysValue['nav']['id'],"title",$title_enabled);
		$metas=ReturnUIDmeta($SysValue['nav']['id'],"descrip",$descrip_enabled);
		$keywords=ReturnUIDmeta($SysValue['nav']['id'],"keywords",$keywords_enabled);
	  }
	 break;

     case("page"):
	 if(isset($SysValue['nav']['name']))
     {
	 $nameSTR=Vivod_page_meta_title("where link='".$SysValue['nav']['name']."'");
     $title=$nameSTR[0]." ".$LoadItems['System']['title'];
	 $metas=$nameSTR[1]." ".$LoadItems['System']['descrip'];
	 $keywords=$nameSTR[2]." ".$LoadItems['System']['keywords'];
	 }
	 break;
	 
	 
	 case("gbook"):
	 $title="������ - ".$LoadItems['System']['title']." ".$LoadItems['System']['name'];
	 $metas="������ ".$LoadItems['System']['descrip'];
     $keywords=$LoadItems['System']['keywords'];
	 break;
	 
	 
	 case("news"):
	 if($SysValue['nav']['nav']=="ID"){
	 $nameSTR=Vivod_page_meta(8,"where id='".$SysValue['nav']['id']."'","zag","kratko");
     $title=$nameSTR[0]." - ������� - ".$LoadItems['System']['title']." ".$LoadItems['System']['name'];
	 $metas=$nameSTR[0];
	 $keywords=GetProductContent("8","where id='".$SysValue['nav']['id']."'","podrob");
	 }
	 else{
     $title="������� - ".$LoadItems['System']['title']." ".$LoadItems['System']['name'];
	 $metas="������� - ".$LoadItems['System']['descrip']." ".$LoadItems['System']['name'];
	 $keywords=" ������� ".$LoadItems['System']['title'].", ".$LoadItems['System']['keywords'];
     }
	 break;

	 
	 case("search"):
	 $title="����� �� ����� - ".$LoadItems['System']['name'];
     $metas="����� �� ����� - ".$LoadItems['System']['descrip'];
	 $keywords="����� �� ����� ".$LoadItems['System']['title'].", ".$LoadItems['System']['keywords'];
     break;
	 
	 case("pricemail"):
	 $title="������������ �� ���� - ".$LoadItems['System']['name'];
     $metas="������������ �� ���� - ".$LoadItems['System']['descrip'];
	 $keywords="������������ �� ���� ".$LoadItems['System']['title'].", ".$LoadItems['System']['keywords'];
     break;
	 
	 
	  case("forma"):
	 $title="����� ����� - ".$LoadItems['System']['name'];
     $metas="����� ����� - ".$LoadItems['System']['descrip'];
	 $keywords="����� ����� ".$LoadItems['System']['title'].", ".$LoadItems['System']['keywords'];
     break;
	 
	 case("price"):
	 $title="�����-���� - ".$LoadItems['System']['name'];
     $metas="�����-���� - ".$LoadItems['System']['descrip'];
	 $keywords=$LoadItems['System']['keywords'];
     break;
	 
	 case("links"):
	 $title="����� �������� - ".$LoadItems['System']['title']." ".$LoadItems['System']['name'];
     $metas="����� �������� - ".$LoadItems['System']['descrip'];
	 $keywords="����� ��������, ".$LoadItems['System']['keywords'];
     break;
	 
	 case("map"):
	 $title="����� ����� - ".$LoadItems['System']['title']." ".$LoadItems['System']['name'];
	 $metas="����� ����� - ".$LoadItems['System']['descrip'];
	 $keywords="����� �����, ".$LoadItems['System']['keywords'];
     break;
	 
	 case("clients"):
	 $title="On-line �������� ��������� ������ - ".$LoadItems['System']['name'];
	 $metas="On-line �������� ��������� ������ - ".$LoadItems['System']['descrip'];
	 $keywords="On-line �������� ��������� ������, ".$LoadItems['System']['keywords'];
     break;
	 
	  case("users"):
	 $title="������ ������� - ".$LoadItems['System']['name'];
	 $metas="������ ������� - ".$LoadItems['System']['descrip'];
	 $keywords="On-line �������� ��������� ������, ".$LoadItems['System']['keywords'];
     break;
	 
	 case("opros"):
	 $title="������ - ".$LoadItems['System']['name'];
	 $metas="������ - ".$LoadItems['System']['descrip'];
	 $keywords="������, ".$LoadItems['System']['keywords'];
     break;
	 
	 case("newtip"):
	 $title="����� ����������� - ".$LoadItems['System']['title']." ".$LoadItems['System']['name'];
	 $metas="����� ����������� - ".$LoadItems['System']['descrip'];
	 $keywords="�������, ".$LoadItems['System']['keywords'];
     break;
	 
	 case("spec"):
	 $title="��������������� - ".$LoadItems['System']['title']." ".$LoadItems['System']['name'];
	 $metas="��������������� - ".$LoadItems['System']['descrip'];
	 $keywords="���������������, ".$LoadItems['System']['keywords'];
     break;
	 
	  case("newprice"):
	 $title="���������� - ".$LoadItems['System']['title']." ".$LoadItems['System']['name'];
	 $metas="���������� - ".$LoadItems['System']['descrip'];
	 $keywords="����������, ".$LoadItems['System']['keywords'];
     break;
	 
	 case("print"):
     $title=$metas=$keywords=$LoadItems['Product'][$SysValue['nav']['id']]['name'];
     break;

default:
    $title=$LoadItems['System']['title']." - ".$LoadItems['System']['name'];
	$metas=$LoadItems['System']['descrip'];
	$keywords=$LoadItems['System']['keywords'];

}


// ���������� ����
function GetProductContent($from,$sql,$f1){
global $SysValue;
include('phpshop/lib/autokeyword/class.autokeyword.php');
$sql="select ".$f1." from ".$SysValue['base']['table_name'.$from]." ".$sql;
$result=mysql_query($sql);
@$row = mysql_fetch_array(@$result);
$_data=strip_tags($row[$f1]);
$keyword = new autokeyword();
$params['_W'] = $_data; //page content
$params['_W1'] = 5;  //minimum length of single words
$params['_W2'] = 4;  //minimum length of words for 2 word phrases
$params['_W3'] = 3;  //minimum length of words for 3 word phrases
$params['_P2'] = 12; //minimum length of 2 word phrases
$params['_P3'] = 15; //minimum length of 3 word phrases
$return = $keyword->autokeyword($params);
return $return;
}

// ���������� ���������
@$SysValue['other']['pageTitl']= $title;
@$SysValue['other']['pageReg']= $RegTo['RegisteredTo'];
@$SysValue['other']['pageDomen']= $RegTo['DomenLocked'];
@$SysValue['other']['pageProduct']= $RegTo['ProductName'];
@$SysValue['other']['pageDesc']= $metas;
@$SysValue['other']['pageKeyw']= $keywords;
$SysValue['other']['pageCss']=$SysValue['dir']['templates'].chr(47).$_SESSION['skin'].chr(47).$SysValue['css']['default'];


function Vivod_page_meta($from,$sql,$f1,$f2)// ����� ���� ��� �������
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name'.$from]." ".$sql."";
$result=mysql_query($sql);
@$row = mysql_fetch_array(@$result);
if($row[$f1]) $name=$row[$f1];
$keywords=$row[$f2];
@$ar=array(@$name,@$keywords);
@$SysValue['sql']['num']++;
return @$ar;
}

function Vivod_page_meta_title($sql)// ����� ���� ��� �������
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name11']." ".$sql."";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);

if(empty($row["title"])) $name=$row["name"];
  else $name=$row["title"];

$description=$row["description"];
$keywords=$row["keywords"];
@$ar=array($name,$description,$keywords);
@$SysValue['sql']['num']++;
return @$ar;
}


function Kratko_metas($n,$flag)// ����� ������� �� ������� ����������� for metas
{
global $LoadItems,$SysValue;
$cat=$LoadItems['Podcatalog'][$n]['parent_to'];
if($flag==1)
@$tit=$LoadItems['Catalog'][$cat]['name']." ".$LoadItems['Podcatalog'][$n]['name'];
elseif($flag==2)
@$tit=$LoadItems['Podcatalog'][$n]['name']." ".$LoadItems['Catalog'][$cat]['name'];
else @$tit=$LoadItems['Catalog'][$n]['name'];
return @$tit;
}
?>
