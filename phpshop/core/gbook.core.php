<?php
/**
 * Обработчик гостевой книги
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopCore
 */
class PHPShopGbook extends PHPShopCore {
    
    /**
     * Конструктор
     */
    function PHPShopGbook() {
        
        // Имя Бд
        $this->objBase=$GLOBALS['SysValue']['base']['table_name7'];
        
        // Путь для навигации
        $this->objPath="/gbook/gbook_";
        
        // Отладка
        $this->debug=false;
        
        // Список экшенов
        $this->action=array("post"=>"send_gb","nav"=>"index","nav"=>"ID","get"=>"add_forma");
        parent::PHPShopCore();
    }
    
    /**
     * Экшен по умолчанию, вывод отзывов
     */
    function index() {

        // Сообщение о записи отзыва
        if(!empty($_GET['write']))
        $this->set('Error',"Сообщение успешно добавлено.");
        
        // Выборка данных
        $this->dataArray=parent::getListInfoItem(array('*'),array('flag'=>"='1'"),array('order'=>'id DESC'));
        
        // 404
        if(!isset($this->dataArray)) return $this->setError404();
        
        if(is_array($this->dataArray))
            foreach($this->dataArray as $row) {
                
                // Ссылка на автора
                if(!empty($row['mail']))  $d_mail="<a href=\"mailto:$row[mail]\"><b>$row[name]</b></a>";
                else  $d_mail="<b>$row[name]</b>";
                
                // Определяем переменые
                $this->set('gbookData',PHPShopDate::dataV($row['datas']));
                $this->set('gbookName',$row['name']);
                $this->set('gbookTema',$row['tema']);
                $this->set('gbookMail',$d_mail);
                $this->set('gbookOtsiv',$row['otsiv']);
                $this->set('gbookOtvet',$row['otvet']);
                $this->set('gbookId',$row['id']);
                
                // Подключаем шаблон
                $this->addToTemplate($this->getValue('templates.main_gbook_forma'));
            }
        
        // Пагинатор
        $this->setPaginator();
        
        
        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.gbook_page_list'));
        
        // Ссылка на новый отзыв
        $this->add($this->attachLink());
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
        
        // Ссылка на автора
        if(!empty($row['mail']))  $d_mail="<a href=\"mailto:$row[mail]\"><b>$row[name]</b></a>";
        else  $d_mail="<b>$row[name]</b>";
        
        
        // Определяем переменые
        $this->set('gbookData',PHPShopDate::dataV($row['datas']));
        $this->set('gbookName',$row['name']);
        $this->set('gbookTema',$row['tema']);
        $this->set('gbookMail',$d_mail);
        $this->set('gbookOtsiv',$row['otsiv']);
        $this->set('gbookOtvet',$row['otvet']);
        $this->set('gbookId',$row['id']);
        
        // Подключаем шаблон
        $this->addToTemplate($this->getValue('templates.main_gbook_forma'));
        
        // Мета
        $this->title=$row['tema']." - ".$this->PHPShopSystem->getValue("name");
        $this->description=strip_tags($row['otsiv']);
        $this->lastmodified=PHPShopDate::GetUnicTime($row['datas']);
        
        
        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.gbook_page_list'));
    }
    
    /**
     * Ссылка на новый отзыв
     * @return string 
     */
    function attachLink() {
        return PHPShopText::div(PHPShopText::a('/gbook/?add_forma=tru','Оставить отзыв'),'center','padding:20');
    }
    
    /**
     * Новый отзыв
     */
    function add_forma() {
        $this->parseTemplate($this->getValue('templates.gbook_forma_otsiv'));
    }
    
    /**
     * Экшен записи отзыва при получении $_POST[send_gb]
     */
    function send_gb() {
        if(!empty($_SESSION['text']) and $_POST['key']==$_SESSION['text']) {
            $this->write();
            header("Location: ../gbook/?write=ok");
        }else {
            $this->set('Error',"Ошибка ключа, повторите попытку ввода ключа");
            $this->parseTemplate($this->getValue('templates.gbook_forma_otsiv'));
        }
    }
    
    /**
     * Запись отзыва в базу
     */
    function write() {
        
        // Подключаем библиотеку отправки почты
        PHPShopObj::loadClass("mail");
        
        if(isset($_POST['send_gb'])) {
            if(!preg_match("/@/",$_POST['mail_new']))//проверка почты
            {
                $_POST['mail_new']="";
            }
            if(!empty($_POST['name_new']) and !empty($_POST['otsiv_new']) and !empty($_POST['tema_new'])) {
                $name_new=PHPShopSecurity::TotalClean($_POST['name_new'],2);
                $otsiv_new=PHPShopSecurity::TotalClean($_POST['otsiv_new'],2);
                $tema_new=PHPShopSecurity::TotalClean($_POST['tema_new'],2);
                $mail_new=addslashes($_POST['mail_new']);
                $date = date("U");
                $ip=$_SERVER['REMOTE_ADDR'];
                
                // Запись в базу
                $this->PHPShopOrm->insert(array('datas'=>$date,'name'=>$name_new,'mail'=>$mail_new,'tema'=>$tema_new,'otsiv'=>$otsiv_new),
                        $prefix='');
                
                $zag=$this->PHPShopSystem->getValue('name')." - Уведомление о добалении отзыва / ".$date;
                $message="
Доброго времени!
---------------

С сайта ".$this->PHPShopSystem->getValue('name')." пришло уведомление о добалении отзыва
в гостевую книгу.

Данные о пользователе:
----------------------

Имя:                ".$name_new."
E-mail:             ".$mail_new."
Тема сообщения:     ".$tema_new."
Сообщение:          ".$otsiv_new."
Дата:               ".date("d-m-y")."
IP:                 ".$ip."

---------------

С уважением,
http://".$_SERVER['SERVER_NAME'];
                
                $PHPShopMail = new PHPShopMail($this->PHPShopSystem->getValue('adminmail2'),$mail_new,$zag,$message);
                
                
            }
        }
    }
    
}
?>