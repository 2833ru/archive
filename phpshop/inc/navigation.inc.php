<?

function topMenuFull($c){
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name11']." where category=$c and enabled='1' $sort order by num";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result))
{
// Определяем переменые
$SysValue['other']['topMenuName']= $row['name'];
$SysValue['other']['topMenuLink']= $row['link'];

// Подключаем шаблон
@$dis.="<LI><A href='/page/".$row['link'].".html'>".$row['name']."</A></LI>";
}
$dis="<UL>$dis</UL>";
return $dis;
}


$SysValue['other']['menuA']= topMenuFull(2);
$SysValue['other']['menuB']= topMenuFull(3);
$SysValue['other']['menuV']= topMenuFull(4);
$SysValue['other']['menuV']= topMenuFull(5);
?>
