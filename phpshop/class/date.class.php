<?php
/**
 * Ѕиблиотека работы с датами
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopClass
 * @subpackage Helper
 */
class PHPShopDate {
    
    /**
     * ѕреобразование даты из Unix к строковый вид
     * @param int $nowtime формат даты в Unix
     * @param bool $full вывод часов и минут
     * @param bool $revers обратна€ строка даты
     * @return string
     */
    function dataV($nowtime=false,$full=true,$revers=false) {

        if(!$nowtime) $nowtime = date("U");

        $Months = array("01"=>"€нвар€","02"=>"феврал€","03"=>"марта",
                "04"=>"апрел€","05"=>"ма€","06"=>"июн€", "07"=>"июл€",
                "08"=>"августа","09"=>"сент€бр€",  "10"=>"окт€бр€",
                "11"=>"но€бр€","12"=>"декабр€");
        $curDateM = date("m",$nowtime);
        $delim='-';
        $d_array=array(
            'y'=>date("Y",$nowtime),
            'm'=>date("m",$nowtime),
            'd'=>date("d",$nowtime),
            'h'=>date("h:i",$nowtime)
        );

        if(!empty($revers)) $time=$d_array['y'].$delim.$d_array['m'].$delim.$d_array['d'];
        else $time=$d_array['d'].$delim.$d_array['m'].$delim.$d_array['y'];

        if(!empty($full)) $time.=" ".$d_array['h'];

        return $time;
    }

    /**
     * ѕреобразование даты из строкового вида в Unix
     * @param string $data дата в формате строки
     * @param string $delim разделитель даты [-] или [.]
     * @return <type>
     */
    function GetUnixTime($data,$delim='-') {
        $array=explode($delim,$data);
        return @mktime(12, 0, 0, $array[1], $array[0], $array[2]);
    }

}
?>