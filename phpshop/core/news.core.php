<?php
/**
 * Обработчик новостей
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopCore
 */
class PHPShopNews extends PHPShopCore {

    /**
     * Конструктор
     */
    function PHPShopNews() {
        // Имя Бд
        $this->objBase=$GLOBALS['SysValue']['base']['table_name8'];

        // Путь для навигации
        $this->objPath="/news/news_";

        // Отладка
        $this->debug=false;

        // Список экшенов
        $this->action=array("nav"=>"ID","post"=>"news_plus");
        parent::PHPShopCore();

        // Календарь
        //$this->calendar();
    }

    /**
     * Экшен по умолчанию
     */
    function index() {

        // Выборка данных
        $this->dataArray=parent::getListInfoItem(array('*'),false,array('order'=>'id DESC'));

        // 404
        if(!isset($this->dataArray)) return $this->setError404();

        if(is_array($this->dataArray))
            foreach($this->dataArray as $row) {

                // Определяем переменные
                $this->set('newsId',$row['id']);
                $this->set('newsData',$row['datas']);
                $this->set('newsZag',$row['zag']);
                $this->set('newsKratko',$row['kratko']);


                // Подключаем шаблон
                $this->addToTemplate($this->getValue('templates.main_news_forma'));
            }

        // Пагинатор
        $this->setPaginator();

        // Мета
        $this->title="Новости - ".$this->PHPShopSystem->getValue("name");

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.news_page_list'));
    }

    /**
     * Экшен выборки подробной информации при наличии переменной навигации ID
     * @return string
     */
    function ID() {

        // Безопасность
        if(!PHPShopSecurity::true_num($this->PHPShopNav->getId())) return $this->setError404();

        // Выборка данных
        $row=parent::getFullInfoItem(array('*'),array('id'=>'='.$this->PHPShopNav->getId()));

        // 404
        if(!isset($row)) return $this->setError404();

        // Определяем переменые
        $this->set('newsData',$row['datas']);
        $this->set('newsZag',$row['zag']);
        $this->set('newsKratko',$row['kratko']);
        $this->set('newsPodrob',$row['podrob']);

        // Подключаем шаблон
        $this->addToTemplate($this->getValue('templates.main_news_forma_full'));

        // Мета
        $this->title=$row['zag']." - ".$this->PHPShopSystem->getValue("name");
        $this->description=strip_tags($row['kratko']);
        $this->lastmodified=PHPShopDate::GetUnicTime($row['datas']);


        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.news_page_full'));
    }

    /**
     * Экшен записи новости при получении $_POST[news_plus]
     */
    function news_plus() {
        $mail=PHPShopSecurity::TotalClean($_POST['mail'],3);

        switch($_POST['status']) {

            case("1"):
                $this->write($mail);
                break;

            case("0"):
                $this->del($mail);
                break;
        }

        // Мета
        $this->title="Новости - Подписка - ".$this->PHPShopSystem->getValue("name");


        $this->parseTemplate($this->getValue('templates.news_forma_mesage'));
    }


    /**
     * Есть ли адрес в базе
     * @param string $mail почта
     * @return bool
     */
    function chek($mail) {
        $PHPShopOrm = &new PHPShopOrm($this->getValue('base.table_name9'));
        $PHPShopOrm->debug=$this->debug;
        $num=$PHPShopOrm->select(array('id'),array('mail'=>"='$mail'"),false,array('limit'=>1));
        if(empty($num['id'])) return true;
    }

    /**
     * Добавление адреса  в БД
     * @param string $mail
     */
    function write($mail) {

        if(!empty($mail)) {

            if($this->chek($mail)) {
                $PHPShopOrm = &new PHPShopOrm($this->getValue('base.table_name9'));
                $PHPShopOrm->debug=$this->debug;
                $PHPShopOrm->insert(array('datas'=>date("d.m.y"),'mail'=>$mail),$prefix='');


                $mes="<FONT style=\"font-size:14px;color:red\">
                    <B>".$this->getValue('lang.good_news_mesage_1')."</B></FONT><BR>".$this->getValue('lang.good_news_mesage_2');
            }else {
                $mes="<FONT style=\"font-size:14px;color:red\">
                    <B>".$this->getValue('lang.bad_news_mesage_1')."</B></FONT><BR>".$this->getValue('lang.good_news_mesage_2');
            }

        }

        else {
            $mes="<FONT style=\"font-size:14px;color:red\">
                    <B>".$this->getValue('lang.bad_news_mesage_3')."</B></FONT><BR>".$this->getValue('lang.good_news_mesage_2');
        }

        $this->set('mesageText',$mes);
    }

    /**
     * Удаление адреса из БД
     * @param string $mail
     */
    function del($mail) {

        if(!$this->chek($mail)) {
            $PHPShopOrm = &new PHPShopOrm($this->getValue('base.table_name9'));
            $PHPShopOrm->debug=$this->debug;
            $PHPShopOrm->delete(array('mail'=>"='$mail'"));
            $mes="<FONT style=\"font-size:14px;color:red\">
                    <B>".$this->getValue('lang.bad_news_mesage_2')."</B></FONT><BR>".$this->getValue('lang.good_news_mesage_2');

        }else {
            $mes="<FONT style=\"font-size:14px;color:red\">
                    <B>".$this->getValue('lang.bad_news_mesage_3')."</B></FONT><BR>".$this->getValue('lang.good_news_mesage_2');
        }

        $this->set('mesageText',$mes);
    }


    /**
     * Календарь новостей
     */
    function calendar() {
        if($this->PHPShopSystem->getSerilizeParam('admoption.user_calendar') == 1) {
            include_once './phpshop/inc/calendar.inc.php';
            $this->set('calendar',calendar());
        }
    }
}
?>