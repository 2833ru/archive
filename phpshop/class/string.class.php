<?php

/**
 * Библиотека форматирования строк
 * @author PHPShop Software
 * @version 1.4
 * @package PHPShopClass
 * @subpackage Helper
 */
class PHPShopString {

    /**
     * Кодировка Win 1251 в UTF8
     * @param string $in_text
     * @return string
     */
    function win_utf8 ($in_text) {
        $output="";
        $other[1025]="Ё";
        $other[1105]="ё";
        $other[1028]="Є";
        $other[1108]="є";
        $other[1030]="I";
        $other[1110]="i";
        $other[1031]="Ї";
        $other[1111]="ї";

        for ($i=0; $i<strlen($in_text); $i++) {
            if (ord($in_text{$i})>191) {
                $output.="&#".(ord($in_text{$i})+848).";";
            } else {
                if (array_search($in_text{$i}, $other)===false) {
                    $output.=$in_text{$i};
                } else {
                    $output.="&#".array_search($in_text{$i}, $other).";";
                }
            }
        }

        return $output;
    }

    /**
     * Кодирование utf8 в win1251
     * @param string $s строка
     * @return string
     */
    function utf8_win1251($s) {
        $s= strtr ($s, array ("\xD0\xB0"=>"а", "\xD0\x90"=>"А", "\xD0\xB1"=>"б", "\xD0\x91"=>"Б", "\xD0\xB2"=>"в", "\xD0\x92"=>"В", "\xD0\xB3"=>"г", "\xD0\x93"=>"Г", "\xD0\xB4"=>"д", "\xD0\x94"=>"Д", "\xD0\xB5"=>"е", "\xD0\x95"=>"Е", "\xD1\x91"=>"ё", "\xD0\x81"=>"Ё", "\xD0\xB6"=>"ж", "\xD0\x96"=>"Ж", "\xD0\xB7"=>"з", "\xD0\x97"=>"З", "\xD0\xB8"=>"и", "\xD0\x98"=>"И", "\xD0\xB9"=>"й", "\xD0\x99"=>"Й", "\xD0\xBA"=>"к", "\xD0\x9A"=>"К", "\xD0\xBB"=>"л", "\xD0\x9B"=>"Л", "\xD0\xBC"=>"м", "\xD0\x9C"=>"М", "\xD0\xBD"=>"н", "\xD0\x9D"=>"Н", "\xD0\xBE"=>"о", "\xD0\x9E"=>"О", "\xD0\xBF"=>"п", "\xD0\x9F"=>"П", "\xD1\x80"=>"р", "\xD0\xA0"=>"Р", "\xD1\x81"=>"с", "\xD0\xA1"=>"С", "\xD1\x82"=>"т", "\xD0\xA2"=>"Т", "\xD1\x83"=>"у", "\xD0\xA3"=>"У", "\xD1\x84"=>"ф", "\xD0\xA4"=>"Ф", "\xD1\x85"=>"х", "\xD0\xA5"=>"Х", "\xD1\x86"=>"ц", "\xD0\xA6"=>"Ц", "\xD1\x87"=>"ч", "\xD0\xA7"=>"Ч", "\xD1\x88"=>"ш", "\xD0\xA8"=>"Ш", "\xD1\x89"=>"щ", "\xD0\xA9"=>"Щ", "\xD1\x8A"=>"ъ", "\xD0\xAA"=>"Ъ", "\xD1\x8B"=>"ы", "\xD0\xAB"=>"Ы", "\xD1\x8C"=>"ь", "\xD0\xAC"=>"Ь", "\xD1\x8D"=>"э", "\xD0\xAD"=>"Э", "\xD1\x8E"=>"ю", "\xD0\xAE"=>"Ю", "\xD1\x8F"=>"я", "\xD0\xAF"=>"Я"));
        return $s;
    }


    /**
     * Перевод в латиницу
     * @param string $str
     * @return string
     */
    function toLatin($str) {
        $str=strtolower($str);
        $str=str_replace("&nbsp;", "", $str);
        $str=str_replace("/", "", $str);
        $str=str_replace("\\", "", $str);
        $str=str_replace("(", "", $str);
        $str=str_replace(")", "", $str);
        $str=str_replace(":", "", $str);
        $str=str_replace("-", "", $str);
        $str=str_replace(" ", "_", $str);
        $str=str_replace("!", "", $str);
        $str=str_replace("|", "_", $str);
        $str=str_replace(".", "_", $str);
        $str=str_replace("№", "N", $str);
        $str=str_replace("?", "", $str);
        $str=str_replace("&nbsp", "_", $str);
        $str=str_replace("&amp;",'_',$str);
        $str=str_replace("ь", "", $str);
        $str=str_replace("Ь", "", $str);
        $str=str_replace("ъ", "", $str);
        $str=str_replace("«", "", $str);
        $str=str_replace("»", "", $str);
        $str=str_replace("“", "", $str);

        $new_str='';
        $_Array=array(" "=>"_","а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d","е"=>"e","ё"=>"e","ж"=>"zh","з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l","м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r","с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h","ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"i","ы"=>"y","ь"=>"i","э"=>"e","ю"=>"u","я"=>"ya","А"=>"a","Б"=>"b","В"=>"v","Г"=>"g","Д"=>"d","Е"=>"e","Ё"=>"e","Ж"=>"zh","З"=>"z","И"=>"i","Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n","О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t","Ы"=>"Y","У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"c","Ч"=>"ch","Ш"=>"sh","Щ"=>"sch","Э"=>"e","Ю"=>"u","Я"=>"ya","."=>"_","$"=>"i","%"=>"i","&"=>"_and_");

        $chars = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);

        foreach($chars as $val)
            if(empty($_Array[$val])) $new_str.=$val;
            else $new_str.=$_Array[$val];

        return trim($new_str);
    }

    // Отрезаем до точки
    function mySubstr($str,$a) {
        $len=strlen($str);
        $str = htmlspecialchars(strip_tags($str));

        if($len<$a) return $str;

        for ($i = 1; $i <= $a; $i++) {
            if($str{$i} == ".") $T=$i;
        }
        if($T<1) return substr($str, 0, $a)."...";
        else return substr($str, 0, $T+1);
    }

}

?>