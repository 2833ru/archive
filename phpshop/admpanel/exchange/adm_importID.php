<?php

$TitlePage = __("��������� �������");

// ��������� ������� ��������
function actionStart() {
    global $PHPShopGUI;

    // SQL
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['exchanges_log']);

    // �������
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));
    $PHPShopGUI->setActionPanel(__('��������� ������� �� ') . PHPShopDate::get($data['date']), false, array('�������'));
    $PHPShopGUI->field_col = 4;

    $option = unserialize($data['option']);

    // ���������
    $PHPShopOrmExchanges = new PHPShopOrm($GLOBALS['SysValue']['base']['exchanges']);
    $data_exchanges = $PHPShopOrmExchanges->select(array('*'), array('id' => '=' . intval($option['exchanges']) . ' or id=' . intval($option['exchanges_new'])), false, array("limit" => 1));
    if(!is_array($data_exchanges)){
       $data_exchanges['name'] = '-';
    }

    if (empty($option['export_imgpath']))
        $option['export_imgpath'] = __('����');
    else
        $option['export_imgpath'] = __('���');

    if (empty($option['export_imgproc']))
        $option['export_imgproc'] = __('����');
    else
        $option['export_imgproc'] = __('���');

    if (empty($option['export_imgload']))
        $option['export_imgload'] = __('����');
    else
        $option['export_imgload'] = __('���');

    if (empty($option['export_uniq']))
        $option['export_uniq'] = __('����');
    else
        $option['export_uniq'] = __('���');


    $delim_value = array(';' => __('����� � �������'), ',' => __('�������'));
    $action_value = array('update' => __('����������'), 'insert' => __('��������'));
    $delim_sortvalue = array('#' => '#', '@' => '@', '$' => '$', '-' => __('�������'));
    $delim_sort = array('/' => '/', '\\' => '\\', '-' => '-', '&' => '&', ';' => ';', ',' => ',');
    $delim_imgvalue = array(',' => __('�������'), 0 => __('����'), ';' => __('����� � �������'), '#' => '#', ' ' => __('������'));
    $code_value = array('ansi' => 'ANSI', 'utf' => 'UTF-8');

    if (!empty($option['export_key']))
        $key_value = $option['export_key'];
    else
        $key_value = 'Id ��� �������';

    if (empty($data['status'])) {
        $status = "<span class='text-warning'>" . __('������') . "</span>";
        $text = null;
        $class = 'hide';
    } else {
        $status = __("�������");
        $info = unserialize($data['info']);
        $text = __('���������� ') . $info[0] . (' �����') . '.<br><a href="' . $info[3] . '" target="_blank">' . $info[1] . ' ' . $info[2] . __(' �������') . '</a>';
    }

    // �������� 1
    $Tab1 = $PHPShopGUI->setField("����", $PHPShopGUI->setText($PHPShopGUI->setLink($data['file'], pathinfo($data['file'])['basename']))) .
            $PHPShopGUI->setField("���������", $PHPShopGUI->setText('<a href="?path=exchange.import&exchanges=' . $data_exchanges['id'] . '">' . $data_exchanges['name'] . '</a>'), false, false, $class) .
            $PHPShopGUI->setField("���������� �����", $PHPShopGUI->setText($info[0]), false, false, $class) .
            $PHPShopGUI->setField($info[1] . ' �������', $PHPShopGUI->setText($info[2]), false, false, $class) .
            $PHPShopGUI->setField('��������� �����������', $PHPShopGUI->setText((int) $info[4]), false, false, $class) .
            $PHPShopGUI->setField('�����', $PHPShopGUI->setText('<a href="' . $info[3] . '" target="_blank">CSV</a>'), false, false, $class) .
            $PHPShopGUI->setField('��������', $PHPShopGUI->setText($action_value[$option['export_action']])) .
            $PHPShopGUI->setField('CSV-�����������', $PHPShopGUI->setText($delim_value[$option['export_delim']])) .
            $PHPShopGUI->setField('����������� ��� �������������', $PHPShopGUI->setText($delim_sortvalue[$option['export_sortdelim']])) .
            $PHPShopGUI->setField('����������� �������� �������������', $PHPShopGUI->setText($delim_sort[$option['export_sortsdelim']])) .
            $PHPShopGUI->setField('������ ���� ��� �����������', $PHPShopGUI->setText($option['export_imgpath']), 1, '��������� � ������������ ����� /UserFiles/Image/') .
            $PHPShopGUI->setField('��������� �����������', $PHPShopGUI->setText($option['export_imgproc']), 1, '�������� ��������� � ����������') .
            $PHPShopGUI->setField('�������� �����������', $PHPShopGUI->setText($option['export_imgload']), 1, '�������� ����������� �� ������ �� ������') .
            $PHPShopGUI->setField('����������� ��� �����������', $PHPShopGUI->setText($delim_imgvalue[$option['export_imgdelim']])) .
            $PHPShopGUI->setField('��������� ������', $PHPShopGUI->setText($code_value[$option['export_code']])) .
            $PHPShopGUI->setField('���� ����������', $PHPShopGUI->setText($key_value)) .
            $PHPShopGUI->setField('�������� ������������', $PHPShopGUI->setText($option['export_uniq']), 1, '��������� ������������ ������ ��� ��������');

    $Tab1 = $PHPShopGUI->setCollapse('���������', $Tab1);

    $name_col = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T'];
    foreach ($option['select_action'] as $k => $p) {
        if (empty($p))
            $p = '-';
        $Tab2 .= $PHPShopGUI->setField('������� ' . $name_col[$k], $PHPShopGUI->setText(__($p)));
    }

    $Tab2 = $PHPShopGUI->setCollapse('������������� �����', $Tab2);

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("����������", $Tab1 . $Tab2, true, false, true));

    return true;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>