<?php
/**
 * Вывод стоимости доставки
 * @package PHPShopDepricated
 * @param id $deliveryID ИД доставки
 * @param float $sum сумма заказа
 * @param float $weight вес
 * @return float 
 */
function GetDeliveryPrice($deliveryID,$sum,$weight=0) {
    global $SysValue;
    
    $deliveryID=TotalClean($deliveryID,1);
    if(!empty($deliveryID)) {
        $sql="select * from ".$SysValue['base']['table_name30']." where id='$deliveryID' and enabled='1'";
        $result=mysql_query($sql);
        $num=mysql_numrows($result);
        $row = mysql_fetch_array($result);
        if($num == 0) {
            $sql="select * from ".$SysValue['base']['table_name30']." where flag='1' and enabled='1'";
            $result=mysql_query($sql);
            $row = mysql_fetch_array($result);
        }
    } else {
        $sql="select * from ".$SysValue['base']['table_name30']." where flag='1' and enabled='1'";
        $result=mysql_query($sql);
        $row = mysql_fetch_array($result);
    }
    
    $SysValue['sql']['num']++;
    
    if($row['price_null_enabled'] == 1 and $sum>=$row['price_null']) {
        return 0;
    } else {
        if ($row['taxa']>0) {
            $addweight=$weight-500;
            if ($addweight<0) {
                $addweight=0;
                $at='';
            } else {
                $at='';
                //$at='Вес: '.$weight.' гр. Превышение: '.$addweight.' гр. Множитель:'.ceil($addweight/500).' = ';
            }
            $addweight=ceil($addweight/500)*$row['taxa'];
            $endprice=$row['price']+$addweight;
            return $at.$endprice;
        } else return $row['price']; 
    }
}

/**
 * Вывод данных по доставке доставки
 * @package PHPShopDepricated
 * @param int $deliveryID ИД доставки
 * @param string $name поле для вывода
 * @return string
 */
function GetDeliveryBase($deliveryID,$name) {
    global $SysValue;
    
    $sql="select * from ".$SysValue['base']['table_name30']." where id='$deliveryID'";
    $result=mysql_query($sql);
    $row = mysql_fetch_array($result);
    
    return $row[$name];
}

/**
 * Вывод методов оплаты
 * @package PHPShopDepricated
 * @return <type> 
 */
function GetOplataMetod() {
    global $SysValue;
    
    $dis=null;
    $sql="select * from ".$SysValue['base']['table_name48']." where enabled='1' order by num";
    $result=mysql_query($sql);
    while($row = mysql_fetch_array($result)) {
        $name=$row['name'];
        $id=$row['id'];
        $dis.='<option value="'.$id.'" >'.$name.'</option>';
    }
    $disp='<select name="order_metod">'.$dis.'</select>';
    
    return $disp;
}

/**
 * Выдача папки размещения API оплаты
 * @package PHPShopDepricated
 * @param int $id ИД оплаты
 * @return array 
 */
function GetPathOrdermetod($id) {
    global $SysValue;
    
    $order_metod = TotalClean($id,1);
    $sql="select name,path from ".$SysValue['base']['table_name48']." where id=".$order_metod." and enabled='1'";
    $result=mysql_query($sql);
    $row = mysql_fetch_array($result);
    $array['name']=$row['name'];
    $array['path']=$row['path'];
    
    return $array;
}

/**
 * Выдача массива данных по валюте
 * @package PHPShopDepricated
 * @param int $n ИД валюты
 * @return array 
 */
function GetValutaValue($n) {
    global $SysValue;
    
    $sql="select $n from ".$SysValue['base']['table_name3'];
    $result=mysql_query($sql);
    $row = mysql_fetch_array($result);
    
    return $row[$n];
}

/**
 * Вывод кода валюты по умочанию
 * @package PHPShopDepricated
 * @return <type> 
 */
function GetIsoValutaOrder() {
    global $SysValue;
    
    $sql="select code from ".$SysValue['base']['table_name24']." where id=".GetValutaValue("kurs");
    $result=mysql_query($sql);
    $row = mysql_fetch_array($result);
    
    return $row['code'];
}

/**
 * Расчет суммы для безналичной оплаты заказа
 * @package PHPShopDepricated
 * @param float $sum сумма
 * @param float $disc скидка
 * @return float 
 */
function ReturnSummaBeznal($sum,$disc){
    $kurs=GetKursOrderBeznal();
    $sum*=$kurs;
    $sum=$sum-($sum*$disc/100);
    
    return number_format($sum,"2",".","");
}

/**
 * Расчет суммы для наличной оплаты заказа
 * @package PHPShopDepricated
 * @param float $sum сумма
 * @param float $disc скидка
 * @return float 
 */
function ReturnSummaNal($sum,$disc){
    global $LoadItems;
    
    $formatPrice = unserialize($LoadItems['System']['admoption']);
    $format=$formatPrice['price_znak'];
    
    $kurs=GetKursOrderNal();
    $sum*=$kurs;
    $sum=$sum-($sum*$disc/100);
    
    return number_format($sum,$format,".","");
}

/**
 * Расчет суммы с учетом курса
 * @package PHPShopDepricated
 * @param float $sum сумма
 * @param float $disc скидка
 * @return float 
 */
function ReturnSumma($sum,$disc) { 
    $kurs=GetKursOrder();
    $sum*=$kurs;
    $sum=$sum-($sum*$disc/100);
    
    return number_format($sum,"2",".","");
}

/**
 * Расчет суммы с учетом курса для заказа
 * @package PHPShopDepricated
 * @param float $sum сумма
 * @param float $disc скидка
 * @return float 
 */
function ReturnSummaOrder($sum,$disc) { 
    $sum=$sum-($sum*$disc/100);
    return number_format($sum,"2",".","");
}

/**
 * Выдача скидки зарегистрированного пользователя
 * @package PHPShopDepricated
 * @param int $n ИД пользователя
 * @return float 
 */
function GetUsersDiscount($n) {
    global $SysValue;
    
    $sql="select discount from ".$SysValue['base']['table_name28']." where id='$n'";
    $result=mysql_query($sql);
    $row = mysql_fetch_array($result);
    
    return $row['discount'];
}

/**
 * Проверка сумму на накипительную скидку
 * @package PHPShopDepricated
 * @param float $mysum сумма
 * @return array 
 */
function ChekDiscount($mysum){
    global $SysValue;
    
    $maxsum=0;
    $sql="select * from ".$SysValue['base']['table_name23']." where sum < '$mysum' and enabled='1'";
    $result=mysql_query($sql);
    while($row = mysql_fetch_array($result)) {
        $sum=$row['sum'];
        if($sum>$maxsum) {
            $maxsum=$sum;
            $maxdiscount=$row['discount'];
        }
    }
    
    $userdiscount=GetUsersDiscount(@$_SESSION['UsersStatus']);
    if($userdiscount>@$maxdiscount) @$maxdiscount=$userdiscount;
    
    $sum=$mysum-($mysum*@$maxdiscount/100);
    $array=array(0+@$maxdiscount,number_format($sum,"2",".",""));
    
    return $array;
}

/**
 * Выдача цены с учетом валюты основной
 * @package PHPShopDepricated
 * @param float $price сумма
 * @param int $format параметр форматирования суммы
 * @return float 
 */
function GetPriceOrder($price,$format=2) {
    global $LoadItems;
    
    $valuta=$LoadItems['System']['kurs'];
    $price*=$LoadItems['Valuta'][$valuta]['kurs'];
    
    return number_format($price,$format,".","");
}

/**
 * Выдача цены с учетом выбранной валюты
 * @package PHPShopDepricated
 * @param float $price сумма
 * @param int $format параметр форматирования суммы
 * @return float  
 */
function GetPriceSort($price,$format=2) {
    global $LoadItems;
    
    if(isset($_SESSION['valuta'])) $valuta=$_SESSION['valuta'];
    else $valuta=$LoadItems['System']['dengi'];
    
    $price/=$LoadItems['Valuta'][$valuta]['kurs'];
    return number_format($price,$format,".","");
}

/**
 * Выдача кода валюты для заказа из настроек
 * @package PHPShopDepricated
 * @return string
 */
function GetValutaOrder() {
    global $LoadItems;
    
    $valuta=$LoadItems['System']['kurs'];
    return  $LoadItems['Valuta'][$valuta]['code'];
}

/**
 * Выдача iso валюты для заказа из настроек
 * @package PHPShopDepricated
 * @return <type> 
 */
function GetValutaIsoOrder() {
    global $LoadItems;
    
    $valuta=$LoadItems['System']['kurs'];
    return  $LoadItems['Valuta'][$valuta]['iso'];
}

/**
 * Выдача курса для безналичного расчета в заказе
 * @package PHPShopDepricated
 * @return float
 */
function GetKursOrderBeznal() {
    global $LoadItems;
    
    $valuta=$LoadItems['System']['kurs_beznal'];
    return  $LoadItems['Valuta'][$valuta]['kurs'];
}

/**
 * Выдача курса для наличного расчета в заказе
 * @package PHPShopDepricated
 * @return <type> 
 */
function GetKursOrderNal() {
    global $LoadItems;
    $valuta=$LoadItems['System']['kurs'];
    return  $LoadItems['Valuta'][$valuta]['kurs'];
}

/**
 * Выдача курса в заказе
 * @package PHPShopDepricated
 * @return <type> 
 */
function GetKursOrder() {
    global $LoadItems;
    
    if(isset($_SESSION['valuta'])) $valuta=$_SESSION['valuta'];
    else $valuta=$LoadItems['System']['dengi'];
    
    return  $LoadItems['Valuta'][$valuta]['kurs'];
}

/**
 * Выдача цены с учетом валюты товара
 * @package PHPShopDepricated
 * @param float $price стоимость
 * @param int $formats кол-во знаков после запятой
 * @param int $baseinputvaluta ИД валюты товара
 * @return float 
 */
function GetPriceValuta($price,$formats=0,$baseinputvaluta="") {
    global $SysValue,$LoadItems;
    
    $formatPrice = unserialize($LoadItems['System']['admoption']);
    $format=$formatPrice['price_znak'];
    
    // Исходную цену
    if ($baseinputvaluta) { //Если прислали баз. валюту
        if ($baseinputvaluta!==$LoadItems['System']['dengi']) {//Если присланная валюта отличается от базовой
            $price=$price/$LoadItems['Valuta'][$baseinputvaluta]['kurs']; //Приводим цену в базовую валюту
        }
    } 

    if(isset($_SESSION['valuta'])) $valuta=$_SESSION['valuta'];
    else $valuta=$LoadItems['System']['dengi'];
    
    $price=$price*$LoadItems['Valuta'][$valuta]['kurs'];
    return number_format($price,$format,'.', ' ');
}

/**
 * Выдача названия валюты по умочанию
 * @package PHPShopDepricated
 * @return string 
 */
function GetValuta() {
    global $LoadItems;
    
    if(isset($_SESSION['valuta'])) $valuta=$_SESSION['valuta'];
    else $valuta=$LoadItems['System']['dengi'];
    
    return  $LoadItems['Valuta'][$valuta]['code'];
}


/**
 * Формирования массива для оформления заказа
 * @package PHPShopClassDepricated
 */
class OrderWrite {
    var $MAS;
    var $NUM;

    /**
     * Формирование массива корзины
     * @param array $cart массив корзины
     * @param float $Delivery
     * @return array
     */
    function OrderCart($cart,$Delivery) {
        global $LoadItems,$SysValue;

        if(is_array($cart))
        $cid=array_keys($cart);
        $sum=0;
        $num=0;

        for ($i=0,$n=1; $i<count($cid); $i++,$n++) {
            $j=$cid[$i];
            $sum+=$cart[$j]['price']*$cart[$j]['num'];
            $num+=$cart[$j]['num'];
            
            // Определение и суммирование веса
            $goodid=$cart[$j]['id'];
            $goodnum=$cart[$j]['num'];
            $wsql='select weight from '.$SysValue['base']['table_name2'].' where id=\''.$goodid.'\'';
            $wresult=mysql_query($wsql);
            $wrow=mysql_fetch_array($wresult);
            $cweight=$wrow['weight']*$goodnum;
            if (!$cweight) {
                $zeroweight=1;
            } // Один из товаров имеет нулевой вес!
            $weight+=$cweight;
        }
        
        // Обнуляем вес товаров, если хотя бы один товар был без веса
        if ($zeroweight) {
            $weight=0;
        }
        
        $sum=number_format($sum,"2",".","");
        $array=array(
                "cart"=>$cart,
                "num"=>$num,
                "sum"=>$sum,
                "weight"=>@$weight,
                "dostavka"=>$Delivery);
        
        $this->NUM = $num;
        return $array;
    }

    /**
     * Очистка от плохих символов
     * @param string $str строка
     * @return string
     */
    function CleanStr($str) {
        $str=str_replace("/","|",$str);
        $str=str_replace("\\","|",$str);
        $str=str_replace("\"","*",$str);
        $str=str_replace("'","*",$str);
        return htmlspecialchars(stripslashes($str));
    }

    /**
     * Формирование массива заказчика
     * @param float $Discount скидка
     * @return array
     */
    function OrderMas($Discount) {
        $array=array(
                "ouid"=>$_POST['ouid'],
                "data"=>date("U"),
                "time"=>date("H:s a"),
                "mail"=>$_POST['mail'],
                "name_person"=>$this->CleanStr($_POST['name_person']),
                "org_name"=>$this->CleanStr($_POST['org_name']),
                "org_inn"=>$this->CleanStr($_POST['org_inn']),
                "org_kpp"=>$this->CleanStr($_POST['org_kpp']),
                "tel_code"=>$this->CleanStr($_POST['tel_code']),
                "tel_name"=>$this->CleanStr($_POST['tel_name']),
                "adr_name"=>$this->CleanStr($_POST['adr_name']),
                "dostavka_metod"=>$_POST['dostavka_metod'],
                "discount"=>$Discount,
                "user_id"=>$_SESSION['UsersId'],
                "dos_ot"=>$this->CleanStr($_POST['dos_ot']),
                "dos_do"=>$this->CleanStr($_POST['dos_do']),
                "order_metod"=>$_POST['order_metod']);
        return $array;
    }
    
    /**
     * Формирование заказа для записи в БД
     * @param float $Discount скидка
     * @param int $UserId ИД пользователя покупателя
     * @param float $Delivery стоимость доставки
     */
    function OrderWrite($Discount,$Delivery) {
        global $LoadItems,$SysValue;
        
        $array=array(
                "Cart"=>$this->OrderCart($_SESSION['cart'],$Delivery),
                "Person"=>$this->OrderMas($Discount)
        );
        $this->MAS = serialize($array);
    }
    
}

/**
 * Библиотека преобразует числовое представление денег в строковое
 */
class inwords {
    
    var $diw=Array(    0 =>    Array(    0  => Array( 0=> "ноль",    1=>1), 
                            1  => Array( 0=> "",        1=>2), 
                            2  => Array( 0=> "",        1=>3), 
                            3  => Array( 0=> "три",        1=>0), 
                            4  => Array( 0=> "четыре",    1=>0), 
                            5  => Array( 0=> "пять",    1=>1), 
                            6  => Array( 0=> "шесть",    1=>1), 
                            7  => Array( 0=> "семь",    1=>1), 
                            8  => Array( 0=> "восемь",    1=>1), 
                            9  => Array( 0=> "девять",    1=>1), 
                            10 => Array( 0=> "десять",    1=>1), 
                            11 => Array( 0=> "одинадцать",    1=>1), 
                            12 => Array( 0=> "двенадцать",    1=>1), 
                            13 => Array( 0=> "тринадцать",    1=>1), 
                            14 => Array( 0=> "четырнадцать",1=>1), 
                            15 => Array( 0=> "пятнадцать",    1=>1), 
                            16 => Array( 0=> "шестнадцать",    1=>1), 
                            17 => Array( 0=> "семнадцать",    1=>1), 
                            18 => Array( 0=> "восемнадцать",1=>1), 
                            19 => Array( 0=> "девятнадцать",1=>1) 
            ), 
            1 =>    Array(    2  => Array( 0=> "двадцать",    1=>1), 
                            3  => Array( 0=> "тридцать",    1=>1), 
                            4  => Array( 0=> "сорок",    1=>1), 
                            5  => Array( 0=> "пятьдесят",    1=>1), 
                            6  => Array( 0=> "шестьдесят",    1=>1), 
                            7  => Array( 0=> "семьдесят",    1=>1), 
                            8  => Array( 0=> "восемьдесят",    1=>1), 
                            9  => Array( 0=> "девяносто",    1=>1)  
            ), 
            2 =>    Array(    1  => Array( 0=> "сто",        1=>1), 
                            2  => Array( 0=> "двести",    1=>1), 
                            3  => Array( 0=> "триста",    1=>1), 
                            4  => Array( 0=> "четыреста",    1=>1), 
                            5  => Array( 0=> "пятьсот",    1=>1), 
                            6  => Array( 0=> "шестьсот",    1=>1), 
                            7  => Array( 0=> "семьсот",    1=>1), 
                            8  => Array( 0=> "восемьсот",    1=>1), 
                            9  => Array( 0=> "девятьсот",    1=>1) 
            ) 
    ); 
    
    var $nom=Array(    0 => Array(0=>"копейки",  1=>"копеек",    2=>"одна копейка", 3=>"две копейки"), 
            1 => Array(0=>"рубля",    1=>"рублей",    2=>"один рубль",   3=>"два рубля"), 
            2 => Array(0=>"тысячи",   1=>"тысяч",     2=>"одна тысяча",  3=>"две тысячи"), 
            3 => Array(0=>"миллиона", 1=>"миллионов", 2=>"один миллион", 3=>"два миллиона"), 
            4 => Array(0=>"миллиарда",1=>"миллиардов",2=>"один миллиард",3=>"два миллиарда"), 
            /* :))) */ 
            5 => Array(0=>"триллиона",1=>"триллионов",2=>"один триллион",3=>"два триллиона") 
    ); 
    
    var $out_rub; 
    
    function get($summ) { 
        if($summ>=1) $this->out_rub=0; 
        else $this->out_rub=1; 
        $summ_rub= doubleval(sprintf("%0.0f",$summ)); 
        if(($summ_rub-$summ)>0) $summ_rub--; 
        $summ_kop= doubleval(sprintf("%0.2f",$summ-$summ_rub))*100; 
        $kop=$this->get_string($summ_kop,0); 
        $retval=""; 
        for($i=1;$i<6&&$summ_rub>=1;$i++): 
            $summ_tmp=$summ_rub/1000; 
            $summ_part=doubleval(sprintf("%0.3f",$summ_tmp-intval($summ_tmp)))*1000; 
            $summ_rub= doubleval(sprintf("%0.0f",$summ_tmp)); 
            if(($summ_rub-$summ_tmp)>0) $summ_rub--; 
            $retval=$this->get_string($summ_part,$i)." ".$retval; 
        endfor; 
        if(($this->out_rub)==0) $retval.=" рублей"; 
        return $retval." ".$kop; 
    } 
    
    function get_string($summ,$nominal) { 
        $retval=""; 
        $nom=-1; 
        $summ=round($summ); 
        if(($nominal==0&&$summ<100)||($nominal>0&&$nominal<6&&$summ<1000)): 
            $s2=intval($summ/100); 
            if($s2>0): 
                $retval.=" ".$this->diw[2][$s2][0]; 
                $nom=$this->diw[2][$s2][1]; 
            endif; 
            $sx=doubleval(sprintf("%0.0f",$summ-$s2*100)); 
            if(($sx-($summ-$s2*100))>0) $sx--; 
            if(($sx<20&&$sx>0)||($sx==0&&$nominal==0)): 
                $retval.=" ".$this->diw[0][$sx][0]; 
                $nom=$this->diw[0][$sx][1]; 
            else: 
                $s1=doubleval(sprintf("%0.0f",$sx/10)); 
                if(($s1-$sx/10)>0)$s1--; 
                $s0=doubleval($summ-$s2*100-$s1*10); 
                if($s1>0): 
                    $retval.=" ".$this->diw[1][$s1][0]; 
                    $nom=$this->diw[1][$s1][1]; 
                endif; 
                if($s0>0): 
                    $retval.=" ".$this->diw[0][$s0][0]; 
                    $nom=$this->diw[0][$s0][1]; 
                endif; 
            endif; 
        endif; 
        if($nom>=0): 
            $retval.=" ".$this->nom[$nominal][$nom]; 
            if($nominal==1) $this->out_rub=1; 
        endif; 
        return trim($retval); 
    } 
    
} 
?>