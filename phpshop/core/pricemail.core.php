<?php
/**
 * Обработчик жалобы на цену
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopCore
 */
class PHPShopPricemail extends PHPShopCore {

    /**
     * Конструктор
     */
    function PHPShopPricemail() {
        // Имя Бд
        $this->objBase=$GLOBALS['SysValue']['base']['table_name17'];

        // Путь для навигации
        $this->objPath="/links/links_";

        // Отладка
        $this->debug=false;

        // список экшенов
        $this->action=array("nav"=>"index","post"=>"send_price_link");
        parent::PHPShopCore();
    }

    function index() {

        if(PHPShopSecurity::true_num($this->PHPShopNav->getId()))
            $this->forma();
        else $this->setError404();
    }



    function send_price_link() {

        // Перехват модуля
        if($this->setHook(__CLASS__,__FUNCTION__,$_POST,'START'))
            return true;

        if(PHPShopSecurity::true_param($_SESSION['text'],$_POST['mail'],$_POST['name_person'],$_POST['link_to_page']) and $_POST['key']==$_SESSION['text']) {

            // Заголовок e-mail пользователю
            $title=$this->PHPShopSystem->getName()." - ".__('Сообщение о меньшей цене');

            $this->set('user_name',$_POST['name_person']);
            $this->set('user_org_name',$_POST['org_name']);
            $this->set('user_tel_code',$_POST['tel_code']);
            $this->set('user_tel',$_POST['tel_name']);
            $this->set('user_info',$_POST['adr_name']);
            $this->set('user_mail',$_POST['mail']);
            $this->set('link_to_page',$_POST['link_to_page']);


            $PHPShopProduct = new PHPShopProduct($this->PHPShopNav->getId());
            $this->set('product_name',$PHPShopProduct->getName());
            $this->set('product_art',$PHPShopProduct->getParam('uid'));
            $this->set('product_id',$this->PHPShopNav->getId());
            $this->set('product_link',$_SERVER['SERVER_NAME']."/shop/UID_".$this->PHPShopNav->getId().".html");

            // Перехват модуля
            $this->setHook(__CLASS__,__FUNCTION__,$_POST);

            // Содержание e-mail пользователю
            $content=ParseTemplateReturn('./phpshop/lib/templates/users/mail_pricemail.tpl',true);

            if(PHPShopSecurity::true_email($_POST['mail'])) {
                PHPShopObj::loadClass('mail');
                $PHPShopMail= new PHPShopMail($this->PHPShopSystem->getEmail(),$_POST['mail'],$title,$content);
            }

            $this->redirect();
        }

    }


    function redirect() {

        // Перехват модуля
        if($this->setHook(__CLASS__,__FUNCTION__))
            return true;

        header("Location: ../shop/UID_".$this->PHPShopNav->getId().".html");
    }

    function forma() {

        // Перехват модуля
        if($this->setHook(__CLASS__,__FUNCTION__,false,'START'))
            return true;

        $PHPShopProduct = new PHPShopProduct($this->PHPShopNav->getId());

        $this->set('productName',$PHPShopProduct->getName());
        $this->set('productImg',$PHPShopProduct->getImage());
        $this->set('productPrice',$PHPShopProduct->getPrice());
        $this->set('productUid',$this->PHPShopNav->getId());
        $this->set('productPriceRub','');

        // Данные пользователя
        if(PHPShopSecurity::true_num($_SESSION['UsersId'])) {
            $PHPShopUsers = new PHPShopUser($_SESSION['UsersId']);
            $this->set('UserMail',$PHPShopUsers->getParam('mail'));
            $this->set('UserName',$PHPShopUsers->getParam('name'));
            $this->set('UserTel',$PHPShopUsers->getParam('tel'));
            $this->set('UserTelCode',$PHPShopUsers->getParam('tel_code'));
            $this->set('UserAdres',$PHPShopUsers->getParam('adres'));
            $this->set('UserComp',$PHPShopUsers->getParam('company'));
            $this->set('formaLock','readonly=1');
        }

        $this->setHook(__CLASS__,__FUNCTION__,$PHPShopProduct,'END');

        $this->ParseTemplate($this->getValue('templates.pricemail_forma'));
    }
}

?>