<?php
/**
 * Отладочная панель
 * Включение в config.ini параметр my[debug]=true
 * <code>
 * // example:
 * timer('start','Моя отладка');
 * debug($_POST,'Моя отладка');
 * timer('end','Моя отладка');
 * </code>
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopClass
 */
class PHPShopDebug {
    var $value;
    var $tollbar_height_closed=150;
    var $tollbar_height_opened=500;
    /**
     * @var string фон отладочной панели для вывода информации
     */
    var $backgroundcolor='black';
    /**
     * @var string цвет текста в отладчной панели
     */
    var $textcolor='green';
    /**
     * @var string цвет панели
     */
    var $tabcolor='green';
    /**
     * @var string цвет заголовков панели
     */
    var $texttabcolor='white';

    function add($value,$desc=false) {
        $this->value[$desc]=$value;
    }

    function timeon($desc=false) {
        // Включаем таймер
        $this->start_time[$desc]=microtime(true);
    }

    function timeoff($desc=false) {
        // Выключаем таймер
        $time=microtime(true);
        $seconds=($time-$this->start_time[$desc]);
        $this->seconds[$desc]=substr($seconds,0,6);
    }

    function disp($name,$content) {
        ob_start();
        print_r($name);
        $disp=$content.': '.ob_get_clean();
        echo  '<pre>'.strip_tags($disp).'</pre>';
    }

    function log() {
        $disp='';
        $base=$GLOBALS['SysValue']['base']['errorlog']['errorlog_log'];

        if(!empty($base)) {
            $PHPShopOrm = new PHPShopOrm($base);
            $data = $PHPShopOrm->select(array('*'),$where,array('order'=>'id DESC'),array('limit'=>100));

            if(is_array($data))
                foreach($data as $val) $disp.=PHPShopDate::dataV($val['date']).' '.$val['error'].'</br>';
        }
        else $disp='Модуль Error Log не установлен';

        echo $disp;

    }

    function compile() {
        global $PHPShopNav;
        
        if(!empty($_GET['debug'])) {
            $height=$this->tollbar_height_closed."px";
            $height2=($this->tollbar_height_closed-20)."px";
        }
        else {
            $height="25px";
            $height2="0px";
        }


        $metod='?';
        if(is_array($PHPShopNav->objNav['query'])){
        foreach($PHPShopNav->objNav['query'] as $k=>$v)
            if($k != 'debug') $metod.=$k.'='.$v.'&';

        }

        echo '
           <script>
           function debug_toolbar(toolbar,height){
           height = toolbar.style.height;
           if(height == "'.($this->tollbar_height_closed-20).'px") {
           document.getElementById("debug-kit-toolbar").style.height="500px";
           toolbar.style.height="500px";
           }
             else {
             toolbar.style.height = "'.($this->tollbar_height_closed-20).'px";
             document.getElementById("debug-kit-toolbar").style.height="'.$this->tollbar_height_closed.'px";
             }
           }
           </script>
           <style>

           #debug-kit-toolbar {
           position: fixed;
           top: 0px;
           right:0px;
           width: 100%;
           height: '.$height.';
           overflow: visible;
           z-index:10000;
           font-family: helvetica, arial, sans-serif;
           }
           
           #debug-kit-nav{
           background-color: '.$this->tabcolor.';

           color: '.$this->textcolor.';
           width: 410px;
           padding: 3px;
           padding-right:5px;
           }

           #debug-kit-nav a{
           color: '.$this->texttabcolor.';
           font-size: 12px;
           }

           #debug-kit-display {
           background-color: '.$this->backgroundcolor.';
           overflow: auto;
           color: '.$this->textcolor.';
           border: 0px;
           border-style: inset;
           height: '.$height2.';
           font-size: 11px;
           text-align: left;
           }

           </style>
           <div title="Развернуть" id="debug-kit-toolbar" align="right">
           <div id="debug-kit-nav">
           <IMG border=0 alt="" src="/phpshop/admpanel/icon/debug.png" width=16 height=16 alt="Отладчик Вкл" align="absmiddle" hspace="5">
           <a href="'.$metod.'debug=session">Session</a> |
           <a href="'.$metod.'debug=sysvalue">SysValue</a> |
           <a href="'.$metod.'debug=request">Request</a> |
           <a href="'.$metod.'debug=timer">Timer</a> |
           <a href="'.$metod.'debug=variables">Variables</a> |
           <a href="'.$metod.'debug=values">Values</a> |
           <a href="'.$metod.'debug=log">Error</a> |
           <a href="?">Exit</a></div>
           <div id="debug-kit-display" onclick="debug_toolbar(this)">';

        if(!empty($_GET['debug']))
        switch($_GET['debug']) {
            case "session":
                $this->disp($_SESSION,"_SESSION");
                break;
            case "sysvalue":
                $SysValue=$GLOBALS['SysValue'];
                $SysValue['connect']='******';
                $SysValue['other']='******';
                $this->disp($SysValue,"GLOBALS['SysValue']");
                break;
            case "request":
                $this->disp($GLOBALS['SysValue']['nav'],"GLOBALS['SysValue']['nav']");
                break;
            case "log":
                $this->log();
                break;
            case "variables":
                $this->disp($GLOBALS['SysValue']['other'],"GLOBALS['SysValue']['other']");
                break;
            case "values":
                $this->disp($this->value,"Values");
                break;
            case "timer":
                $this->disp($this->seconds,"Timer");
                break;
        }

        echo '</div></div>';
    }
}

/**
 * Отладка персональных данных
 * @global obj $PHPShopDebug
 * @param mixed $value перемнная для отладки
 * @param string $desc оисание переменной
 */
function debug($value,$desc=false) {
    global $PHPShopDebug;
    $PHPShopDebug->add($value,$desc);
}

/**
 * Замер времени выполнения
 * @global obj $PHPShopDebug
 * @param string $option переключатель вкл.выкл [start|end]
 * @param string $desc описание таймера
 */
function timer($option='start',$desc=false) {
    global $PHPShopDebug;

    if($option == 'start') $PHPShopDebug->timeon($desc);
    else $PHPShopDebug->timeoff($desc);
}

?>