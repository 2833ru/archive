<?
if(isset($backup)){
require("../../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase")or @die("Невозможно подсоединиться к базе");
require("../../enter_to_admin.php");
if(CheckedRules($UserStatus["sql"],2) == 1){
header('Content-Type: application/force-download'); 
header('Content-Disposition: attachment; filename="'.$backup.'"'); 
header('Content-Length: '.filesize($backup));
readfile($backup);
}}
else header("Location ./");
?>
