<?php
/**
 * Элемент корзина покупок
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopElements
 */
class PHPShopCartElement extends PHPShopElements {

    function PHPShopCartElement() {
        parent::PHPShopElements();
    }

    function miniCart() {
        $cart=$_SESSION['cart'];
        $compare=$_SESSION['compare'];
        $sum=0;
        $sum_r=0;
        $num=0;
        $numcompare=0;

        if(count($cart)>0) {
            if(is_array($cart))
                foreach($cart as $j=>$v) {
                    $sum+=$cart[$j]['price']*$cart[$j]['num'];
                    $sum_r=$sum*$this->LoadItems['System']['kurs'];
                    $num+=$cart[$j]['num'];
                }
            $this->set('orderEnabled','block');
        }
        else {
            $sum="--";
            $num="--";
            $this->set('orderEnabled','none');
        }

        if(count($compare)>0) {
            if(is_array($compare)) {
                foreach($compare as $j=>$v) {
                    $numcompare=count($compare);
                }
            }
            $this->set('compareEnabled','block');
        } else {
            $numcompare="--";
            $this->set('compareEnabled','none');
        }

        // Определяем переменные
        $this->set('tovarNow',$this->getValue('lang.cart_tovar_now'));
        $this->set('summaNow',$this->getValue('cart_summa_now'));
        $this->set('orderNow',$this->getValue('cart_order_now'));
        $this->set('numcompare',$numcompare);
        $this->set('num',$num);
        $this->set('sum',GetPriceValuta($sum));
        //$this->set('productValutaName',GetValuta());
    }
}

class PHPShopCurrencyElement extends PHPShopElements {

    function PHPShopCurrencyElement() {
        parent::PHPShopElements();
    }

    function valutaDisp() {
        $name=null;

        if(isset($_SESSION['valuta'])) $valuta=$_SESSION['valuta'];
        else $valuta=$this->LoadItems['System']['dengi'];

        if(is_array($this->LoadItems['Valuta']))
            foreach($this->LoadItems['Valuta'] as $k=>$v) {
                if($valuta == $this->LoadItems['Valuta'][$k]['id']) $sel="selected";
                else $sel="";
                $name.= '<option value="'.$this->LoadItems['Valuta'][$k]['id'].'" '.$sel.' >'.$this->LoadItems['Valuta'][$k]['name'].'</option>';
            }

        // Определяем переменные
        $this->set('leftMenuName','Валюта');
        $this->set('leftMenuContent','<form name=ValutaForm method=post><select name="valuta" onchange="ChangeValuta()">'.$name.'</select></form>');

        // Подключаем шаблон
        $dis=$this->parseTemplate($this->getValue('templates.valuta_forma'));
        return $dis;
    }
}


/**
 * Элемент текстовые блоки
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopElements
 */
class PHPShopTextElement extends PHPShopElements {


    function PHPShopTextElement() {
        $this->debug=false;
        $this->objBase=$GLOBALS['SysValue']['base']['table_name14'];
        parent::PHPShopElements();
    }


    function leftMenu() {
        $dis='';
        $data = $this->PHPShopOrm->select(array('*'),array("flag"=>"='1'",'element'=>"='0'"),array('order'=>'num'),array("limit"=>20));
        if(is_array($data))
            foreach($data as $row) {

                if(empty($row['dir'])) {
                    // Определяем переменные
                    $this->set('leftMenuName',$row['name']);
                    $this->set('leftMenuContent',newParser($row['content']));
                    $dis.=$this->parseTemplate($this->getValue('templates.left_menu'));
                }
                else {
                    $dirs= explode(",",$row['dir']);
                    foreach($dirs as $dir)
                        if($dir==$_SERVER['REQUEST_URI']) {
                            $this->set('leftMenuName',$row['name']);
                            $this->set('leftMenuContent',$row['content']);
                            // Подключаем шаблон
                            $dis.=$this->parseTemplate($this->getValue('templates.left_menu'));
                        }
                }
            }
        return $dis;
    }


    function rightMenu() {
        $dis='';
        $PHPShopOrm = &new PHPShopOrm($this->objBase);
        $data = $PHPShopOrm->select(array('*'),array("flag"=>"='1'",'element'=>"='1'"),array('order'=>'num'),array("limit"=>20));
        if(is_array($data))
            foreach($data as $row) {

                if(empty($row['dir'])) {
                    // Определяем переменые
                    $this->set('leftMenuName',$row['name']);
                    $this->set('leftMenuContent',$row['content']);
                    $dis.=$this->parseTemplate($this->getValue('templates.right_menu'));
                }
                else {
                    $dirs= explode(",",$row['dir']);
                    foreach($dirs as $dir)
                        if($dir==$_SERVER['REQUEST_URI']) {
                            $this->set('leftMenuName',$row['name']);
                            $this->set('leftMenuContent',$row['content']);
                            // Подключаем шаблон
                            $dis.=$this->parseTemplate($this->getValue('templates.right_menu'));
                        }
                }
            }
        return $dis;
    }


    function topMenu() {
        $dis='';
        $objBase=$GLOBALS['SysValue']['base']['table_name11'];
        $PHPShopOrm = &new PHPShopOrm($objBase);
        $data = $PHPShopOrm->select(array('name','link'),array("category"=>"=1000"),array('order'=>'num'),array("limit"=>20));
        if(is_array($data))
            foreach($data as $row) {

                // Определяем переменые
                $this->set('topMenuName',$row['name']);
                $this->set('topMenuLink',$row['link']);
                $dis.=$this->parseTemplate($this->getValue('templates.top_menu'));

            }
        return $dis;
    }

}


/**
 * Элемент cмена шаблонов
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopElements
 */
class PHPShopSkinElement extends PHPShopElements {

    function PHPShopSkinElement() {
        parent::PHPShopElements();
    }

    function index() {
        $dis='';
        if($this->PHPShopSystem->getSerilizeParam("admoption.user_skin") == 1) {
            $dir=$this->getValue('dir.templates').chr(47);
            if (is_dir($dir)) {
                if (@$dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {

                        if($_SESSION['skin'] == $file)
                            $sel="selected";
                        else $sel="";

                        if($file!="." and $file!=".." and $file!="index.html")
                            @$name.= "<option value=\"$file\" $sel>Шаблон $file</option>";
                    }
                    closedir($dh);
                }
            }


            // Определяем переменые
            $forma="<div style=\"padding:10px\"><form name=SkinForm method=post><select name=\"skin\" onchange=\"ChangeSkin()\">".$name."</select></form></div>";
            $this->set('leftMenuContent',$forma);
            $this->set('leftMenuName',"Сменить дизайн");

            // Подключаем шаблон
            $dis=$this->parseTemplate($this->getValue('templates.left_menu'));
        }
        return $dis;
    }

}



/**
 * Элемент последние новости
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopElements
 */
class PHPShopNewsElement extends PHPShopElements {

    var $disp_only_index=false; // Показывать только на главной
    var $limit=3; // Кол-во новостей

    function PHPShopNewsElement() {
        $this->debug=false;
        $this->objBase=$GLOBALS['SysValue']['base']['table_name8'];
        parent::PHPShopElements();
    }

    // Последние новости
    function index() {
        $dis='';

        // Выполнение только на главной странице
        if($this->disp_only_index) {
            if($this->PHPShopNav->index()) $view=true;
            else $view=false;
        }
        else $view=true;

        if($view) {
            $data = $this->PHPShopOrm->select(array('id','zag','datas','kratko'),false,array('order'=>'id DESC'),array("limit"=>$this->limit));
            if(is_array($data))
                foreach($data as $row) {

                    // Определяем переменые
                    $this->set('newsId',$row['id']);
                    $this->set('newsZag',$row['zag']);
                    $this->set('newsData',$row['datas']);
                    $this->set('newsKratko',$row['kratko']);

                    // Подключаем шаблон
                    $dis.=$this->parseTemplate($this->getValue('templates.news_main_mini'));
                }
            return $dis;
        }
    }
}

/**
 * Элемент Форма опросов
 * @author PHPShop Software
 * @version 1.1
 * @package PHPShopElements
 */
class PHPShopOprosElement extends PHPShopElements {

    /**
     * Конструктор
     */
    function PHPShopOprosElement() {
        $this->debug=false;
        parent::PHPShopElements();
    }

    /**
     * Вывод формы голосования
     * @return string
     */
    function oprosDisp() {

        // Выборка данных
        $PHPShopOrm = &new PHPShopOrm($this->getValue('base.opros_categories'));
        $PHPShopOrm->debug=$this->debug;
        $dataArray=$PHPShopOrm->select(array('*'),array('flag'=>"='1'"),array('order'=>'id DESC'),array('limit'=>10));
        $content='';
        if(is_array($dataArray))
            foreach($dataArray as $row) {

                if(empty($row['dir'])) {
                    // Определяем переменные
                    $this->set('oprosName',$row['name']);
                    $this->set('oprosContent',$this->getOprosValue($row['id'],"FORMA"));

                    // Подключаем шаблон
                    $content.= $this->parseTemplate($this->getValue('templates.opros_list'));
                }
                else {

                    // Если через запятую укзано
                    if(strpos($row['dir'], ",")) $dirs = explode(",",$row['dir']);
                    else $dirs[] = $row['dir'];

                    foreach($dirs as $dir)
                        if($dir==$_SERVER['REQUEST_URI']) {

                            // Определяем переменные
                            $this->set('oprosName',$row['name']);
                            $this->set('oprosContent',$this->getOprosValue($row['id'],"FORMA"));

                            // Подключаем шаблон
                            $content.= $this->parseTemplate($this->getValue('templates.opros_list'));
                        }
                }
            }

        // Подключаем шаблон
        return $content;
    }

    /**
     * Вывод ответов
     * @param int $n ИД опроса
     * @param string $flag [FORMA|RESULT] опция места вывода (форма опроса или результат опросов)
     * @return string
     */
    function getOprosValue($n,$flag) {
        $dis='';
        $PHPShopOrm = &new PHPShopOrm($this->getValue('base.opros'));
        $PHPShopOrm->comment='getOprosValue';
        $PHPShopOrm->debug=$this->debug;
        $this->dataArray=$PHPShopOrm->select(array('*'),array('category'=>'='.$n),array('order'=>'num'),array('limit'=>100));
        if(is_array($this->dataArray))
            foreach($this->dataArray as $row) {

                if($row['total'] > 0) $total=$row['total'];
                else $total="--";

                // Определяем переменые
                $this->set('valueName',$row['name']);
                $this->set('valueId',$row['id']);


                // Подключаем шаблон
                if($flag=="FORMA")
                    $dis.=$this->parseTemplate($this->getValue('templates.opros_forma'));
                elseif($flag=="RESULT") {
                    $sum=$this->getSumValue($row['category']);
                    $pr=@number_format(($total*100)/$sum,"1",".","");

                    // Определяем переменые
                    $this->set('valueSum',$total);
                    $this->set('valueProc',$pr);
                    $this->set('valueWidth',$pr*3+1);

                    $dis.=$this->parseTemplate($this->getValue('templates.opros_page_forma'));
                }
            }
        return $dis;
    }

    /**
     * Сумма значений
     * @param int $n ИД опроса
     * @return int
     */
    function getSumValue($n) {
        $objBase=$this->getValue('base.opros');
        $PHPShopOrm = &new PHPShopOrm($objBase);
        $result=$PHPShopOrm->query("select SUM(total) as sum from ".$objBase." where category=".$n);
        $row = mysql_fetch_array($result);
        return $row['sum'];
    }
}


/**
 * Элемент баннер
 * @author PHPShop Software
 * @version 1.1
 * @package PHPShopElements
 */
class PHPShopBannerElement extends PHPShopElements {

    function PHPShopBannerElement() {
        $this->debug=false;
        $this->objBase=$GLOBALS['SysValue']['base']['table_name15'];
        parent::PHPShopElements();
    }

    // Вывод баннера
    function index() {

        $this->row = $this->PHPShopOrm->select(array('*'),array("flag"=>"='1'"),array('order'=>'RAND()'),array("limit"=>1));
        if(is_array($this->row)) {

            // Определяем переменые
            $this->set('banerContent',$this->row['content']);
            $this->set('banerTitle',$this->row['name']);

            // Сообщение администратору о конце показов
            if($this->row['count_all']>$this->row['limit_all']) $this->mail();

            // Обновляем данные показа
            $this->update();

            // Подключаем шаблон
            $dis=$this->parseTemplate($this->getValue('templates.baner_list_forma'));
        }
        return $dis;

    }


    function update() {

        if($this->row['datas'] != date("d.m.y")) $count_today=0;
        else $count_today=$this->row['count_today']+1;


        $count_all=$this->row['count_all']+1;
        $this->PHPShopOrm->update(array('count_all'=>$count_all,'count_today'=>$count_today,'datas'=>date("d.m.y")),
                array('id'=>"=".$this->row['id']),$prefix='');
    }


    function mail() {
        $this->PHPShopOrm->update(array('flag'=>'0'),array('id'=>"=".$this->row['id']),$prefix='');

        // Подключаем библиотеку отправки почты
        PHPShopObj::loadClass("mail");
        $zag="Закончились показы у банера ".$this->row['name']."";

        $message="
Закончились показы у банера ".$this->row['name'].".
Для редактирования состояния баннерной сети перейдите в панель
администрирования  http://".$_SERVER['SERVER_NAME']."/phpshop/admpanel/

Характеристики баннера
---------------------------------------------------------

Название: ".$this->row['name']."
Лимит: ".$this->row['limit_all']."
Дата отключения: ".date("d.m.y")."
---------------------------------------------------------


http://".$_SERVER['SERVER_NAME'];
        $PHPShopMail = &new PHPShopMail($this->PHPShopSystem->getParam('adminmail2'),
                "robot@".str_replace("www", '', $_SERVER['SERVER_NAME']),$zag,$message);

    }

}


/**
 * Элемент Облако тегов
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopElements
 */
class PHPShopCloudElement extends PHPShopElements {

    var $page_limit=100; // Лимит страниц для анализа
    var $word_limit=30; // Лимит слов для вывода

    function PHPShopCloudElement() {
        $this->debug=false;
        $this->objBase=$GLOBALS['SysValue']['base']['products'];

        parent::PHPShopElements();
        if($this->PHPShopSystem->getSerilizeParam('admoption.cloud_color')=="")
            $this->color="0x518EAD";
        else $this->color="0x".$this->PHPShopSystem->getSerilizeParam('admoption.cloud_color');
    }

    // Последние новости
    function index() {
        $disp='';


        $data = $this->PHPShopOrm->select(array('keywords','id'),array('enabled'=>"='1'",'keywords'=>" !=''"),array('order'=>'RAND()'),array("limit"=>$this->page_limit));
        if(is_array($data))
            foreach($data as $row) {

                $explode=explode(", ",$row['keywords']);
                foreach($explode as $ev)
                    if(!empty($ev)) {
                        $ArrayWords[]=$ev;
                        $ArrayLinks[$ev]=$row['id'];
                    }

            }
        if(is_array($ArrayWords))
            foreach($ArrayWords as $k=>$v) {
                $count=array_keys($ArrayWords,$v);
                $CloudCount[$v]['size']=count($count);
            }


        // Урезаем для наглядности
        $i=0;
        if(is_array($CloudCount))
            foreach($CloudCount as $k=>$v) {
                if($i<$this->word_limit) $CloudCountLimit[$k]=$v;
                $i++;
            }


        if(is_array($CloudCountLimit))
            foreach($CloudCountLimit as $key=>$val)
                $disp.="<a href='".$this->getValue('dir.dir')."/shop/UID_".$ArrayLinks[$key].".html' style='font-size:12pt;'>$key</a>";

        // Чистим теги
        $disp = str_replace('\n','',$disp);
        $disp = str_replace(chr(13),'',$disp);
        $disp = str_replace(chr(10),'',$disp);

        $disp='
<div id="wpcumuluscontent">Загрузка флеш...</div><script type="text/javascript">
var dd=new Date();
 var so = new SWFObject("/stockgallery/tagcloud.swf?rnd="+dd.getTime(), "tagcloudflash", "180", "180", "9", "'.$this->color.'");
so.addParam("wmode", "transparent");
so.addParam("allowScriptAccess", "always");
so.addVariable("tcolor", "'.$this->color.'");
so.addVariable("tspeed", "150");
so.addVariable("distr", "true");
so.addVariable("mode", "tags");
so.addVariable("tagcloud", "<tags>'.$disp.'</tags>");
so.write("wpcumuluscontent");</script>
';


        // Чистим
        $disp = str_replace('\n','',$disp);
        $disp = str_replace(chr(13),'',$disp);
        $disp = str_replace(chr(10),'',$disp);


        // Определяем переменые
        $this->set('leftMenuName',"Облако тегов");
        $this->set('leftMenuContent',$disp);


        // Подключаем шаблон
        @$dis.=$this->parseTemplate($this->getValue('templates.left_menu'));

        return $dis;
    }
}
?>