<?
/**
 * Обработчик товаров
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopCore
 */
class PHPShopCatalog extends PHPShopCore {

    /**
     * Конструктор
     */
    function PHPShopCatalog() {

        // Имя Бд
        $this->objBase=$GLOBALS['SysValue']['base']['products'];

        // Отладка
        $this->debug=false;

        // Размещение
        $this->path='/'.$GLOBALS['SysValue']['nav']['path'];

        // Валюта
        $this->PHPShopValuta=$GLOBALS['PHPShopValuta'];

        PHPShopObj::loadClass('product');

        // Список экшенов
        $this->action=array("nav"=>"CID");
        parent::PHPShopCore();

        $this->page=$this->PHPShopNav->getPage();
        if(strlen($this->page)==0) $this->page=1;
    }

    /**
     * Экшен по умолчанию, вывод данных по странице
     * @return string
     */
    function index() {
        global $PHPShopModules;

        // Безопасность
        $link=PHPShopSecurity::TotalClean($this->PHPShopNav->getName(),2);

        // Выборка данных
        $row=parent::getFullInfoItem(array('*'),array('link'=>"='$link'",'enabled'=>"!='0'"));

        // Прикрываем страницу от дубля
        if($row['category'] == 2000)  return $this->setError404();
        elseif(empty($row['id'])) return $this->setError404();

        // Определяем переменые
        $this->set('pageContent',Parser($row['content']));
        $this->set('pageTitle',$row['name']);

        // Мета
        if(empty($row['title'])) $title=$row['name'];
        else $title=$row['title'];
        $this->title=$title." - ".$this->PHPShopSystem->getValue("name");
        $this->description=$row['description'];
        $this->keywords=$row['keywords'];
        $this->lastmodified=$row['date'];

        // Навигация хлебные крошки
        $this->navigation($row['category'],$row['name']);

        // Перехват модуля
        //$PHPShopModules->setHookHandler(__CLASS__,__FUNCTION__, $this, $row);

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * Экшен выборки подробной информации при наличии переменной навигации CID
     */
    function CID() {

        // ID категории
        $this->category=PHPShopSecurity::TotalClean($this->PHPShopNav->getId(),1);
        $this->PHPShopCategory = &new PHPShopCategory($this->category);
        $this->category_name=$this->PHPShopCategory->getName();

        $PHPShopOrm = &new PHPShopOrm($this->getValue('base.categories'));
        $PHPShopOrm->debug=$this->debug;
        $row=$PHPShopOrm->select(array('id,name'),array('parent_to'=>"=".$this->category),false,array('limit'=>1));

        // Если страницы
        if(empty($row['id'])) {

            $this->ListProduct();
        }
        // Если каталоги
        else {

            $this->ListCategory();
        }
    }

    /**
     * Проверка режима Multibase
     */
    function checkMultibase($pic_small) {
        $base_host = $this->PHPShopSystem->getSerilizeParam('admoption.base_host');
        if($this->PHPShopSystem->getSerilizeParam('admoption.base_enabled') == 1 and !empty($base_host))
            $this->set('productImg',eregi_replace("/UserFiles/","http://". $base_host."/UserFiles/",$pic_small));
    }



    /**
     * Проверка дополнительных данных товара по складу
     * @param array $row масив данных по товару
     */
    function checkStore($row) {
        
        // Подтипы есть
        if(!empty($row['parent'])) {
            $this->set('ComStart','<!--');
            $this->set('ComEnd','-->');

        }else {
            $this->set('ComStart','');
            $this->set('ComEnd','');
        }


        // Показывать состояние склада
        if($this->PHPShopSystem->getSerilizeParam('admoption.sklad_enabled') == 1 and $row['items'] > 0)
            $this->set('productSklad',$this->SysValue['lang']['product_on_sklad']." ".$row['items']." ".$this->SysValue['lang']['product_on_sklad_i']);
        else $this->set('productSklad','');

        // Если товар на складе
        if(empty($row['sklad'])) {

            $this->set('Notice','');
            $this->set('ComStartCart','');
            $this->set('ComEndCart','');
            $this->set('ComStartNotice','<!--');
            $this->set('ComEndNotice','-->');

            // Если нет новой цены
            if(empty($row['price_n'])) {
                $this->set('productPrice',PHPShopProductFunction::GetPriceValuta($row['id'],$row['price'],false,$row['baseinputvaluta']));
                $this->set('productPriceRub','');
            }

            // Если есть новая цена
            else {
                $productPrice=PHPShopProductFunction::GetPriceValuta($row['id'],$row['price'],false,$row['baseinputvaluta']);
                $this->set('productPrice',$productPrice);
                $this->set('productPriceRub',PHPShopText::strike($productPrice.' '.$this->PHPShopValuta[$row['baseinputvaluta']['code']]));
            }
        }

        // Товар под заказ
        else {
            $this->set('productPrice',PHPShopProductFunction::GetPriceValuta($row['id'],$row['price'],false,$row['baseinputvaluta']));
            $this->set('productPriceRub',$this->SysValue['lang']['sklad_mesage']);
            $this->set('ComStartNotice','');
            $this->set('ComEndNotice','');
            $this->set('ComStartCart',PHPShopText::comment('<'));
            $this->set('ComEndCart',PHPShopText::comment('>'));
            $this->set('productNotice',$this->SysValue['lang']['product_notice']);
        }

        // Если цены показывать только после аторизации
        if($this->PHPShopSystem->getSerilizeParam('admoption.user_price_activate') == 1 and empty($_SESSION['UsersId'])) {
            $this->set('ComStartCart',PHPShopText::comment('<'));
            $this->set('ComEndCart',PHPShopText::comment('>'));
            $this->set('productPrice',PHPShopText::comment('<'));
            $this->set('productValutaName',PHPShopText::comment('>'));
        }
    }

    /**
     * Создание ячеек с товарами
     * @return string
     */
    function setCell() {
        $Arg=func_get_args();
        $item=1;
        $panel=array('panel_l','panel_r','panel_l','panel_r');
        $tr='<tr>';
        foreach($Arg as $key=>$val) {
            if(!empty($val)) {
                $tr.='<td class="'.$panel[$key].'" valign="top">'.$val.'</td>';
                $chek=($key)/2;
                if(empty($chek))
                    $tr.='<td class="setka" height="1"><img height="1" src="images/spacer.gif" width="1"></td>';
            }
        }
        $tr.='</tr>';
        $tr.='<tr><td class="setka" colspan="3" height="1"><img height="1" src="images/spacer.gif" width="1"></td></tr>';
        return $tr;
    }


    /**
     * Проверка опций товара
     */
    function checkProductOption(){

        $DispCatOptionsTest=DispCatOptionsTest($this->category);
        if($DispCatOptionsTest == 1) {
            $this->set('ComStartCart',PHPShopText::comment('<'));
            $this->set('ComEndCart',PHPShopText::comment('>'));
        }
    }

    /**
     * Вывод списка товаров
     * @return string
     */
    function ListProduct() {
        $j=1;
        $item=1;

        // Количество ячеек для вывода товара
        $cell=$this->LoadItems['Podcatalog'][$this->category]['num_row'];

        debug($this->LoadItems['Podcatalog']);

        // Выборка данных
        $this->dataArray=parent::getListInfoItem(array('*'),array('category'=>'='.$this->category),array('order'=>'id DESC'));

        // 404
        if(!isset($this->dataArray)) return $this->setError404();

        // Пагинатор
        $this->setPaginator();

        if(is_array($this->dataArray))
            foreach($this->dataArray as $row) {

                // Определяем переменные
                $this->set('productName',$row['name']);
                $this->set('productArt',$row['uid']);
                $this->set('productDes',$row['description']);

                // Пустая картинка
                if(empty($row['pic_small']))
                    $this->set('productImg','images/shop/no_photo.gif');
                else $this->set('productImg',$row['pic_small']);

                // Проверка режима Multibase
                $this->checkMultibase($row['pic_small']);

                $this->set('productImgBigFoto',$row['pic_big']);
                $this->set('productPriceMoney',$this->PHPShopSystem->getValue('dengi'));
                $this->set('productSale',$this->SysValue['lang']['product_sale']);
                $this->set('productSale',$this->SysValue['lang']['product_info']);
                $this->set('productValutaName',GetValuta());
                $this->set('productId',$this->category);
                $this->set('productUid',$row['id']);
                $this->set('catalog',$this->SysValue['lang']['catalog']);


                // Опции склада
                $this->checkStore($row);

                // Подключаем шаблон ячейки товара
                $dis=ParseTemplateReturn($this->getValue('templates.main_product_forma_'.$cell));

                // Ячейки с товарами (1-4)
                if($j<$cell) {
                    $cell_name='d'.$j;
                    $$cell_name=$dis;
                    $j++;
                    if($item == $this->num_page)
                        $table.=$this->setCell($d1);
                }
                else {
                    $cell_name='d'.$j;
                    $$cell_name=$dis;
                    $table.=$this->setCell($d1,$d2,$d3,$d4);
                    $j=1;

                }
                $item++;
            }

        // Добавляем в дизайн ячейки с товарами
        $this->add($table,true);

        // Мета
        $this->title="Каталог - ".$this->PHPShopSystem->getValue("name");

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.product_page_list'));
    }

    /**
     * Вывод списка категорий
     */
    function ListCategory() {
        global $PHPShopModules;

        // ID категории
        $this->category=PHPShopSecurity::TotalClean($this->PHPShopNav->getId(),1);
        $this->PHPShopCategory = &new PHPShopCategory($this->category);
        $this->category_name=$this->PHPShopCategory->getName();

        // Выборка данных
        $PHPShopOrm = &new PHPShopOrm($this->getValue('base.categories'));
        $PHPShopOrm->debug=$this->debug;
        $dataArray=$PHPShopOrm->select(array('name','id'),array('parent_to'=>'='.$this->category),array('order'=>'num'),array('limit'=>100));
        if(is_array($dataArray))
            foreach($dataArray as $row) {
                $dis.=PHPShopText::li($row['name'],$this->path.'/CID_'.$row['id'].'.html');
            }


        // Если есть описание каталога
        //if(!empty($this->LoadItems['Catalog'][$this->category]['content_enabled']))
        $this->set('catalogContent',$this->PHPShopCategory->getContent());

        $disp.="<ul>$dis</ul>";

        $this->set('catalogName',$this->category_name);
        $this->set('catalogList',$disp);

        // Мета
        $this->title=$this->category_name." - ".$this->PHPShopSystem->getValue("name");

        // Навигация хлебные крошки
        if(!empty($this->LoadItems['Catalog'][$this->category]['parent_to']))
            $this->navigation($this->LoadItems['Catalog'][$this->category]['parent_to'],$this->category_name);
        else $this->navigation($this->category,$this->category_name);


        // Перехват модуля
        //$PHPShopModules->setHookHandler(__CLASS__,__FUNCTION__, $this, $row);

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.catalog_info_forma'));
    }

    function meta() {
        global $PHPShopModules;
        parent::meta();

        // Перехват модуля
        //$PHPShopModules->setHookHandler(__CLASS__,__FUNCTION__, $this);
    }
}
?>