<?php
/**
 * Библиотека офорфмления текста
 * @version 1.1
 * @package PHPShopClass
 */
class PHPShopText {

    function b($string) {
        return '<b>'.$string.'</b>';
    }

    function notice($string,$icon=false) {
        if(!empty($icon)) $img=PHPShopText::img($icon);
        return $img.'<font color="red">'.$string.'</font>';
    }

    function message($string,$icon=false) {
        if(!empty($icon)) $img=PHPShopText::img($icon);
        return $img.'<font color="green">'.$string.'</font>';
    }

    function img($src,$hspace=5,$align='left') {
        return '<img src="'.$src.'" hspace="'.$hspace.'" align="'.$align.'">';
    }

    function a($href,$text,$title=false,$color=false,$size=false) {
        $style='text-decoration:underline;';
        if($size) $style.='font-size:'.$size.'px;';
        if($color) $style.='color:'.$color;
        if(empty($title)) $title=$text;
        return '<a href="'.$href.'" title="'.$title.'" style="'.$style.'">'.$text.'</a>';
    }

    function h1($string) {
        return '<h1>'.$string.'</h1>';
    }

    function ul($string) {
        return '<ul>'.$string.'</ul>';
    }

    function li($string,$href=false) {
        if(!empty($href)) {
            $text=PHPShopText::a($href,$string);
            $li='<li>'.$text.'</li>';
        }
        else $li='<li>'.$string.'</li>';
        return $li;
    }

    function tr(){
        $Arg=func_get_args();
        $tr='<tr class=tablerow>';
        foreach($Arg as $val) {
            $tr.='<td class=tablerow>'.$val.'</td>';
        }
        $tr.='</tr>';
        return $tr;
    }

    function th($string){
        return '<th>'.$string.'</th>';
    }
    
    function div($string,$align="left",$style=false){
        return '<div align="'.$align.'" style="'.$style.'">'.$string.'</div>';
    }

    function strike($string){
        return '<strike>'.$string.'</strike>';
    }

    function comment($type='<'){
        if($type == '<') return '<!--';
        else return '-->';
    }

    function p($string='<br>',$style=false){
        return '<p style="'.$style.'">'.$string.'</p>';
    }

}
?>