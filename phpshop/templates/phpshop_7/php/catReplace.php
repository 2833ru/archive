<?php
function shopLeftCat_replace(){
	$content = Vivod_cats(); 
	$content1 = str_replace("podCatTiTOut","TiTOut",$content);
	$content2 = str_replace("podCatTiTOver","TiTOver",$content1);
	$content3 = str_replace("divCatId","divCatIdBot",$content2);
	
	return $content3;
}
?>