<?php
/**
 * Ѕиблиотека работы с датами
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopClass
 */
class PHPShopDate {
    /**
     * ѕреобразование даты из Unix к строковый вид
     * @param int $nowtime формат даты в Unix
     * @return string
     */
    function dataV($nowtime) {
        $Months = array("01"=>"€нвар€","02"=>"феврал€","03"=>"марта",
                "04"=>"апрел€","05"=>"ма€","06"=>"июн€", "07"=>"июл€",
                "08"=>"августа","09"=>"сент€бр€",  "10"=>"окт€бр€",
                "11"=>"но€бр€","12"=>"декабр€");
        $curDateM = date("m",$nowtime);
        $t=date("d",$nowtime)."-".$curDateM."-".date("y",$nowtime)." ".date("H:i ",$nowtime);
        return $t;
    }

    /**
     * ѕреобразование даты из строкового вида в Unix
     * @param string $data дата в формате строки
     * @param string $delim разделитель даты [-] или [.]
     * @return <type>
     */
    function GetUnicTime($data,$delim='-') {
        $array=explode($delim,$data);
        return @mktime(12, 0, 0, $array[1], $array[0], $array[2]);
    }

}
?>