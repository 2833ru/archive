<?
/*
+-------------------------------------+
|  Имя: PHPShopString                 |
|  Разработчик: PHPShop Software      |
|  Использование: Enterprise          |
|  Назначение: Операции со строками   |
|  Версия: 1.0                        |
|  Тип: class                         |
|  Зависимости: нет                   |
|  Вызов: Function                    |
+-------------------------------------+
*/

class PHPShopString {


      // Кодировка Win 1251 - UTF8
      function win_utf8 ($in_text){ 
      $output=""; 
      $other[1025]="Ё"; 
      $other[1105]="ё"; 
      $other[1028]="Є"; 
      $other[1108]="є"; 
      $other[1030]="I"; 
      $other[1110]="i"; 
      $other[1031]="Ї"; 
      $other[1111]="ї"; 

      for ($i=0; $i<strlen($in_text); $i++){ 
      if (ord($in_text{$i})>191){ 
         $output.="&#".(ord($in_text{$i})+848).";"; 
      } else { 
           if (array_search($in_text{$i}, $other)===false){ 
             $output.=$in_text{$i}; 
           } else { 
               $output.="&#".array_search($in_text{$i}, $other).";"; 
                  } 
        } } 
      return $output; 
      } 

      // Перевод в латиницу
      function StrToLatin($str){
      $str=strtolower($str);
      $str=str_replace("/", "", $str);
      $str=str_replace("\\", "", $str);
      $str=str_replace("(", "", $str);
      $str=str_replace(")", "", $str);
      $str=str_replace(":", "", $str);
      $str=str_replace("-", "", $str);
      $str=str_replace(" ", "", $str);

$_Array=array("а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d","е"=>"e","ё"=>"e","ж"=>"gh","з"=>"z","и"=>"i","й"=>"i","к"=>"k","л"=>"l","м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r","с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h","ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"sh","ъ"=>"i","ы"=>"yi","ь"=>"i","э"=>"a","ю"=>"u","я"=>"ya","А"=>"a","Б"=>"b","В"=>"v","Г"=>"g","Д"=>"d","Ё"=>"e","Ж"=>"gh","З"=>"z","И"=>"i","Й"=>"i","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n","О"=>"o","П"=>"п","Р"=>"r","С"=>"s","Т"=>"t","У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"c","Ч"=>"ch","Ш"=>"sh","Щ"=>"sh","Э"=>"a","Ю"=>"u","Я"=>"ya","."=>"_","$"=>"i","%"=>"i","&"=>"and");

      $chars = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);

      foreach($chars as $val)
            if(empty($_Array[$val])) @$new_str.=$val;

      return @$new_str;
      }
	  
	  // Отрезаем до точки
      function Substr($str,$a,$dot=false){
	  $T="";
      if(empty($str)) return $str;
      $str = htmlspecialchars(strip_tags($str));
      for ($i = 1; $i <= $a; $i++) {
	     if(!empty($str{$i}))
	     if($str{$i} == ".") $T=$i;
      }
      if($T<1) {
	         if($dot) return substr($str, 0, $a)."...";
			   else return substr($str, 0, $a);
	         }
        else return substr($str, 0, $T+1);
      }

}


?>
