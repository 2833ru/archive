<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Модуль Комментариев                |
+-------------------------------------+
*/


// Кол-во отзывов для статьи
function getCommentNum($n){
global $SysValue;
$num=0;
$sql="select id from ".$SysValue['base']['table_name36']." where parent_id=$n";
@$result=mysql_query(@$sql);
@$num=mysql_num_rows(@$result);
if($num > 0) return $num;
  else return "нет";
}

?>