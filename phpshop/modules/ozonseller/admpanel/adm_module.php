<?php

include_once dirname(__FILE__) . '/../class/OzonSeller.php';

PHPShopObj::loadClass("order");
PHPShopObj::loadClass("delivery");

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ozonseller.ozonseller_system"));

// ���������� ������ ������
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $PHPShopOrm->update(array('version_new' => $new_version));
}

// ������� ����������
function actionUpdate() {
    global $PHPShopModules;

    if (!empty($_POST['load']))
           actionUpdateCategory();
    
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ozonseller.ozonseller_system"));
    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);

    header('Location: ?path=modules&id=' . $_GET['id']);

    return $action;
}

function setChildrenCategory($tree_array,$parent_to) {
    global $PHPShopModules;

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ozonseller.ozonseller_categories"));

    if (is_array($tree_array)) {
        foreach ($tree_array as $category) {
            $PHPShopOrm->insert(['name_new' => PHPShopString::utf8_win1251($category['title']), 'id_new' => $category['category_id'], 'parent_to_new' => $parent_to]);

            if (is_array($category['children'])) {
                foreach ($category['children'] as $children) {
                    $PHPShopOrm->insert(['name_new' => PHPShopString::utf8_win1251($children['title']), 'id_new' => $children['category_id'], 'parent_to_new' => $category['category_id']]);
                    if (is_array($children['children']))
                        setChildrenCategory($children['children'],$children['category_id']);
                }
            }
        }
    }
}

// ������������� ���������
function actionUpdateCategory() {
    global $PHPShopModules;

    $OzonSeller = new OzonSeller();
    $getTree = $OzonSeller->getTree();
    $tree_array = $getTree['result'];

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ozonseller.ozonseller_categories"));
    $PHPShopOrm->debug = false;

    // �������
    $PHPShopOrm->query('TRUNCATE TABLE `' . $PHPShopModules->getParam("base.ozonseller.ozonseller_categories") . '`');
    
    if (is_array($tree_array)) {
        foreach ($tree_array as $category) {
            $PHPShopOrm->insert(['name_new' => PHPShopString::utf8_win1251($category['title']), 'id_new' => $category['category_id'], 'parent_to_new' => 0]);
            
            if (is_array($category['children'])) {
                foreach ($category['children'] as $children) {
                    $PHPShopOrm->insert(['name_new' => PHPShopString::utf8_win1251($children['title']), 'id_new' => $children['category_id'], 'parent_to_new' => $category['category_id']]);
                    if (is_array($children['children']))
                        setChildrenCategory($children['children'],$children['category_id']);
                }
            }
        }
    }

}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm,$PHPShopModules;

    $PHPShopGUI->field_col = 4;

    // �������
    $data = $PHPShopOrm->select();

    // ������
    $status[] = [__('����� �����'), 0, $data['status']];
    $statusArray = (new PHPShopOrm('phpshop_order_status'))->getList(['id', 'name']);
    foreach ($statusArray as $statusParam) {
        $status[] = [$statusParam['name'], $statusParam['id'], $data['status']];
    }

    // �������� ������� �������
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $OrderStatusArray = $PHPShopOrderStatusArray->getArray();
    $order_status_value[] = array(__('����� �����'), 0, $data['status']);
    if (is_array($OrderStatusArray))
        foreach ($OrderStatusArray as $order_status)
            $order_status_value[] = array($order_status['name'], $order_status['id'], $data['status']);


    $Tab1 = $PHPShopGUI->setField('������ ������ �����', $PHPShopGUI->setInputText($_SERVER['SERVER_NAME'] . '/yml/?marketplace=ozon&pas=', 'password_new', $data['password'],'100%'));
    $Tab1 .= $PHPShopGUI->setField('Client id', $PHPShopGUI->setInputText(false, 'client_id_new', $data['client_id'], '100%'));
    $Tab1 .= $PHPShopGUI->setField('API key', $PHPShopGUI->setInputText(false, 'token_new', $data['token'], '100%'));
    $Tab1 .= $PHPShopGUI->setField('������ ������ ������', $PHPShopGUI->setSelect('status_new', $order_status_value, '100%'));
    
    $PHPShopOrmCat = new PHPShopOrm($PHPShopModules->getParam("base.ozonseller.ozonseller_categories"));
    $category = $PHPShopOrmCat->select(['COUNT(`id`) as num']);

    $Tab1 .= $PHPShopGUI->setField('���� ���������', $PHPShopGUI->setText($category['num'].' '.__('������� � ��������� ����'),null, false, false).'<br>'.$PHPShopGUI->setCheckbox('load', 1, '�������� ���� ��������� ������� ��� OZON', 0));

    $Tab1 = $PHPShopGUI->setCollapse('���������', $Tab1);

    $info = '<h4>��������� ������</h4>
    <ol>
        <li>������������������ � <a href="https://seller.ozon.ru" target="_blank">OZON Seller</a></li>
        <li>� ������ �������� OZON Seller ������� <a href="https://seller.ozon.ru/app/settings/api-keys" target="_blank">��������� - API �����</a>, ������� ����� API ���� � ������� ������������� (������� �����, ���� ������ �� ���� ������� API). ���������� ����������� �������� ����� � ���� <kbd>API key</kbd> �  �������� Client Id � ���� <kbd>Client Id</kbd> � ���������� ������.
        </li>
        <li>� ���������� ������ �������� ����� ���������� ���� ��������� ������� ��� OZON � ������ <kbd>���������</kbd>. �������� ���� ��������� ����� ������ ��������� ������ �� ������� �������� ���������� ��������� � OZON.</li>
        <li>� ���������� ������ ������� ������ �������, ����������� � OZON.</li>
    </ol>
    <h4>��������� OZON Seller</h4>
    <ol>
      <li>��������� <a href="https://seller.ozon.ru/app/products/import/products-feed-update" target="_blank">���������� ������</a> � ������� ����� ���. �������� � �������� ������ �� ��� ����� <code>http://'.$_SERVER['SERVER_NAME'].'/yml/?marketplace=ozon</code></li>
      <li>� ������ �������� OZON Seller � ������� <a href="https://seller.ozon.ru/app/warehouse" target="_blank">FBS - ���������</a> �������� ����� � ������ "��������".</li>
    </ol>
    
   <h4>�������� ������� � OZON</h4>
   Ozon ��������� ������ �� ������� � ����� ���������� ������� �� ���������� � ��������������� �� ���� OZON.
   <ol>
    <li>� �������� �������������� ��������� � �������� ����������� ��������� ���� ��������� � ���������� OZON � �������� <kbd>OZON</kbd>, ���� <kbd>���������� � OZON</kbd>. ���� ����� ��������� ������, �� ��������� ��������� ���� ��������� �� �������� ������. ��������� ����� � ����������� ��������, ����� ���� �������� ���� "������������� ������������� � OZON".</li>
    <li>����������� ��� ������� ����������� �������������� � ���������� ����������. ��������� ��� ��������� �������� ��������� �������������� ����� �� ������ "��������� ��������" ��� ��������� �������������� OZON.</li>
    <li>� �������� �������������� ������ � �������� ����� �������� "������ - OZON" �������� ����� <kbd>�������� ������� � OZON</kbd> � ��������� ������. ���� ����������� ����� ��������, �� �������� ������ � OZON ���������� ����� � � ���� "������ ������" ����� ���������� ������ �������� �������� ������ ��� �������� ������ � ���������. ������ ������� ��� �������� � OZON �������� � ������� "������ - OZON Seller - ������ ��� OZON".</li>
    <li>����� �������� �������� ������ �������� � ������� <a href="https://seller.ozon.ru/app/products?filter=all" target="_blank">������ �������</a> � OZON.</li>
  </ol>
  
  <h4>�������� ������� � OZON</h4>
   <ol>
    <li>������ ������� ��� �������� �� OZON �������� � ������� "������ - OZON Seller - ������ �� OZON". �� ����� �� ����� ������ ��������� �������� � ��������� ������ �� ������ � OZON. ��� �������� ������ ������������ ������ <kbd>��������� �����</kbd>. ����������� ����� ����� ����� ������, ��������� � ���������� ������. � ���� "���������� ��������������" ������������ ������ ����� ���������� � �������� � OZON � ��� �����. ��� ��������� �������� ������ ������� ������� ��� �� ���� ������� � ��������.</li>
    <li>� �������� "�������������" ������������� ������ � OZON ��������� ������ ���������� �� ������ � ���� ������� ������.</li>
  </ol>
';
    
    if($data['fee_type'] == 1){
        $status_pre='-';
    }
 
    else {
        $status_pre='+';
    }
    
    $Tab3= $PHPShopGUI->setCollapse('��������� ���',
        $PHPShopGUI->setField('������� ��� OZON', $PHPShopGUI->setSelect('price_new', $PHPShopGUI->setSelectValue($data['price'], 5), 100)) .
        $PHPShopGUI->setField('�������', $PHPShopGUI->setInputText($status_pre, 'fee_new', $data['fee'], 100, '%')).
        $PHPShopGUI->setField('��������', $PHPShopGUI->setRadio("fee_type_new", 1, "���������", $data['fee_type']) . $PHPShopGUI->setRadio("fee_type_new", 2, "���������", $data['fee_type'])) 
            );

    $Tab2 = $PHPShopGUI->setInfo($info);

    // ����� �����������
    $Tab4 = $PHPShopGUI->setPay(false, false, $data['version'], true);

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1.$Tab3, true, false, true), array("����������", $Tab2), array("� ������", $Tab4));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
