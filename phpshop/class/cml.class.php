<?php

/**
 * Библиотека работы с CommerceML
 * @version 1.3
 * @package PHPShopClass
 */
class PHPShopCommerceML {

    /**
     * Конструктор
     */
    function __construct() {
        global $PHPShopSystem;

        $this->exchange_key = $PHPShopSystem->getSerilizeParam("1c_option.exchange_key");
    }

    /**
     * Категории
     * @param array $where условие поиска
     * @return array
     */
    function category($where) {
        $Catalog = array();
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);

        // Не выводить скрытые каталоги
        $where['skin_enabled'] = "!='1'";

        $data = $PHPShopOrm->select(array('id,name,parent_to'), $where, false, array('limit' => 10000));
        if (is_array($data))
            foreach ($data as $row) {
                if ($row['id'] != $row['parent_to']) {
                    $Catalog[$row['id']]['id'] = $row['id'];
                    $Catalog[$row['id']]['name'] = $row['name'];
                    $Catalog[$row['id']]['parent_to'] = $row['parent_to'];
                }
            }

        return $Catalog;
    }

    /**
     * Категории
     * @param integer $id ИД категории
     * @return string
     */
    function setCategories($id) {
        $xml = '<Группы>';
        $category = $this->category(array('parent_to' => '=' . $id));
        foreach ($category as $val) {
            $xml .= '<Группа>
                <Ид>' . $val['id'] . '</Ид>
		<Наименование>' . str_replace(['&', '<', '>'], '', $val['name']) . '</Наименование>';
            $parent = $this->setCategories($val['id']);
            if (!empty($parent))
                $xml .= $parent;
            else
                $xml .= '<Группы/>';
            $xml .= '</Группа>';
        }

        $xml .= '</Группы>';

        return $xml;
    }

    /**
     * Изображения товара
     * @param array $product_row
     * @return string
     */
    function getImages($product_row) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['foto']);
        $data = $PHPShopOrm->select(array('*'), array('parent' => '=' . $product_row['id']), false, array('limit' => 10000));
        $xml = null;
        if (is_array($data))
            foreach ($data as $row) {
                $xml .= '<Картинка>http://' . $_SERVER['SERVER_NAME'] . $row['name'] . '</Картинка>';
            }

        if (empty($xml))
            $xml = '<Картинка>http://' . $_SERVER['SERVER_NAME'] . $product_row['pic_big'] . '</Картинка>';

        return $xml;
    }

    /**
     * Генерация CommerceML для товаров
     * @param array $data
     * @return string
     */
    function getProducts($data) {
        global $PHPShopSystem;

        $xml = null;

        // Каталоги
        $category = $this->setCategories(0);

        // Товары
        foreach ($data as $row)
            if (is_array($row)) {

                // Убираем подтипы
                if ($row['parent_enabled'] == 1)
                    continue;

                if ($this->exchange_key == 'code') {
                    $code = $row['uid'];
                    $uid = null;
                    $id = $row['external_code'];
                }

                if ($this->exchange_key == 'uid') {
                    $code = null;
                    $uid = $row['uid'];
                    $id = $row['external_code'];
                }

                if ($this->exchange_key == 'external') {
                    $code = null;
                    $uid = null;
                    $id = $row['uid'];
                }

                $item .= '
                        <Товар>
			<Ид>' . $id . '</Ид>
                        <Код>' . $code . '</Код>
			<Артикул>' . $uid . '</Артикул>
			<Наименование>' . str_replace(['&', '<', '>'], '', $row['name']) . '</Наименование>
                        <БазоваяЕдиница Код="796 " НаименованиеПолное="Штука" МеждународноеСокращение="PCE">' . $row['ed_izm'] . '</БазоваяЕдиница>
                        <ПолноеНаименование><![CDATA[' . $row['description'] . ']]></ПолноеНаименование>
			<Группы>
				<Ид>' . $row['category'] . '</Ид>
			</Группы>
                        <Описание><![CDATA[' . $row['content'] . ']]></Описание>
			<СтавкиНалогов>
				<СтавкаНалога>
					<Наименование>НДС</Наименование>
					<Ставка>' . $PHPShopSystem->getParam('nds') . '</Ставка>
				</СтавкаНалога>
			</СтавкиНалогов>
			<ЗначенияРеквизитов>
				<ЗначениеРеквизита>
					<Наименование>ТипНоменклатуры</Наименование>
					<Значение>Товар</Значение>
				</ЗначениеРеквизита>
				<ЗначениеРеквизита>
					<Наименование>Вес</Наименование>
					<Значение>' . $row['weight'] . '</Значение>
				</ЗначениеРеквизита>
			</ЗначенияРеквизитов>
                        ' . $this->getImages($row) . '
		</Товар>
                ';
            }

        $items = ' <Каталог СодержитТолькоИзменения="false">
	<Ид>1</Ид>
        <ИдКлассификатора>1</ИдКлассификатора>
	<Наименование>Основной каталог товаров</Наименование>
		<Товары>
' . $item . '
		</Товары>
	</Каталог>';

        $xml = '<?xml version="1.0" encoding="windows-1251"?>
<КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . PHPShopDate::get(time(), false, true) . '">
    <Классификатор>
    <Ид>1</Ид>
    <Наименование>Классификатор (Основной каталог товаров)</Наименование>
    <Владелец>
       <Ид>1</Ид>
       <Наименование>' . $PHPShopSystem->getParam('name') . '</Наименование>
       <ОфициальноеНаименование>' . $PHPShopSystem->getParam('company') . '</ОфициальноеНаименование>
       <ИНН>' . $PHPShopSystem->getParam('nds') . '</ИНН>
       <КПП>' . $PHPShopSystem->getParam('kpp') . '</КПП>
    </Владелец>
	' . $category . '
    </Классификатор>
    ' . $items . '
</КоммерческаяИнформация>';
        return $xml;
    }

    /**
     * Генерация CommerceML для заказа
     * @param array $data
     * @return string
     */
    function getOrders($data) {
        global $PHPShopSystem;

        $xml = null;
        if (is_array($data))
            foreach ($data as $row)
                if (is_array($row)) {

                    $PHPShopOrder = new PHPShopOrderFunction($row['id']);
                    $this->update_status[] = $row['id'];

                    $num = 0;
                    $id = $row['id'];
                    $uid = $row['uid'];
                    $order = unserialize($row['orders']);
                    $status = unserialize($row['status']);
                    $sum = $PHPShopOrder->returnSumma($order['Cart']['sum'], $order['Person']['discount']);

                    $item = null;
                    if (is_array($order['Cart']['cart']))
                        foreach ($order['Cart']['cart'] as $val) {

                            $num = $val['num'];
                            $sum = $PHPShopOrder->returnSumma($val['price'] * $num, $order['Person']['discount']);

                            if ($this->exchange_key == 'code') {
                                $code = $val['uid'];
                                $uid = null;
                                $id = (new PHPShopOrm($GLOBALS['SysValue']['base']['products']))->getOne(['external_code'],['id'=>'='.$val['id']])['external_code'];
                            }

                            if ($this->exchange_key == 'uid') {
                                $code = null;
                                $uid = $val['uid'];
                                $id = (new PHPShopOrm($GLOBALS['SysValue']['base']['products']))->getOne(['external_code'],['id'=>'='.$val['id']])['external_code'];
                            }

                            if ($this->exchange_key == 'external') {
                                $code = null;
                                $uid = null;
                                $id = (new PHPShopOrm($GLOBALS['SysValue']['base']['products']))->getOne(['external_code'],['id'=>'='.$val['id']])['external_code'];
                            }
                            
                            // Подтип
                            if(!empty($val['parent'])){
                                $id = (new PHPShopOrm($GLOBALS['SysValue']['base']['products']))->getOne(['external_code'],['id'=>'='.$val['parent']])['external_code'].'#'.$id;
                            }
                                
                            $item .= '<Товар>
				<Ид>' . $id . '</Ид>
                                <Код>' . $code . '</Код>
				<Штрихкод></Штрихкод>
				<Артикул>' . $uid . '</Артикул>
				<Наименование>' . $val['name'] . '</Наименование>
				<ЦенаЗаЕдиницу>' . $val['price'] . '</ЦенаЗаЕдиницу>
				<Количество>' . $val['num'] . '</Количество>
				<Сумма>' . $sum . '</Сумма>
				<Единица>шт</Единица>
			</Товар>';
                        }

                    if (empty($row['fio']))
                        $row['fio'] = $row['org_name'];

                    $xml .= '
	<Документ>
                <Ид>' . $row['id'] . '</Ид>
		<Номер>' . $row['uid'] . '</Номер>
		<Дата>' . PHPShopDate::get($row['datas'], false, true) . '</Дата>
		<ХозОперация>Заказ товара</ХозОперация>
		<Роль>Продавец</Роль>
		<Валюта>' . $PHPShopSystem->getDefaultValutaIso() . '</Валюта>
		<Сумма>' . $row['sum'] . '</Сумма>
                <Комментарий>' . $status['maneger'] . '</Комментарий>
                <Контрагенты>
		   <Контрагент>
              <Ид>' . $row['user'] . '</Ид>
		      <Наименование>' . $row['fio'] . '</Наименование>
		      <ПолноеНаименование>' . $row['org_name'] . '</ПолноеНаименование>
		      <ИНН>' . $row['org_inn'] . '</ИНН>
		      <КПП>' . $row['org_kpp'] . '</КПП>
		      <Роль>Покупатель</Роль>
		   </Контрагент>
                </Контрагенты>
		<Товары>
' . $item . '
		</Товары>
	</Документ>';
                }

        $xml = '<?xml version="1.0" encoding="windows-1251"?>
<КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . PHPShopDate::get(time(), false, true) . '">
	' . $xml . '
</КоммерческаяИнформация>';

        return $xml;
    }

}

?>