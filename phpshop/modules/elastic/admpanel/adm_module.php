<?php

include_once dirname(__DIR__) . '/class/include.php';

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.elastic.elastic_system"));

// ���������� ������ ������
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;

    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();

    return $PHPShopOrm->update(['version_new' => $new_version]);
}

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;

    // ��������� �������
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);

    if (empty($_POST["filter_show_counts_new"]))
        $_POST["filter_show_counts_new"] = 0;
    if (empty($_POST["filter_update_new"]))
        $_POST["filter_update_new"] = 0;
    if (empty($_POST["search_show_informer_string_new"]))
        $_POST["search_show_informer_string_new"] = 0;
    if (empty($_POST["ajax_search_categories_new"]))
        $_POST["ajax_search_categories_new"] = 0;
    if (empty($_POST["available_sort_new"]))
        $_POST["available_sort_new"] = 0;
    if (empty($_POST["use_additional_categories_new"]))
        $_POST["use_additional_categories_new"] = 0;
    if (empty($_POST["use_proxy_new"]))
        $_POST["use_proxy_new"] = 0;
    if (empty($_POST["search_uid_first_new"]))
        $_POST["search_uid_first_new"] = 0;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);

    header('Location: ?path=modules&id=elastic');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $TitlePage, $select_name, $PHPShopBase;

    $PHPShopGUI->action_button['�������������� ������'] = [
        'name' => '�������������� ������',
        'action' => 'importProducts',
        'class' => 'btn  btn-default btn-sm navbar-btn',
        'type' => 'submit',
        'icon' => 'glyphicon glyphicon-import'
    ];

    $PHPShopGUI->addJSFiles('../modules/elastic/admpanel/gui/script.gui.js?v=1.0');
    $PHPShopGUI->setActionPanel($TitlePage, $select_name, ['�������������� ������', '��������� � �������']);
    $Elastic = new Elastic();
    $client = null;
    $error = null;
    $info = null;

    // �������
    $data = $PHPShopOrm->select();

    if(!empty($data['api'])) {
        try {
            $client = $Elastic->client->getClientInfo();
        } catch (\Exception $exception) {
            $error = $exception->getMessage();
        }
        if(isset($client['data']['blocked']) && (bool) $client['data']['blocked'] === true) {
            $error = $client['data']['block_reason'];
        }
    } else {
        $info = __('������� API ���� � ������� "���������" ��� ������� � ����������.');
    }

    $registerLink = null;
    if(empty($data['api'])) {
        $registerLink = '<a href="https://elastica.host/register" target="_blank">' . __('�����������') . '</a>';
    }

    if ($PHPShopBase->getParam('template_theme.demo') == 'true') {
        $data['api'] = '';
    }

    $Tab1 = $PHPShopGUI->setField('API �������������', $PHPShopGUI->setInputText(false, 'api_new', $data['api'], 300, $registerLink));
    $Tab1 .= $PHPShopGUI->setField('������������ Proxy', $PHPShopGUI->setCheckbox('use_proxy_new', 1, '������������ �������������� ����� �����������', $data['use_proxy']), 1, '����������� ������ � ��� ������, ���� � ��� ����������� �������� � ������������ � ���������� �������.');

    if(!empty($error)) {
        $Tab1 .= sprintf('<div class="alert alert-danger" role="alert">%s</div>', $error);
    }
    if(!empty($info)) {
        $Tab1 .= sprintf('<div class="alert alert-info" role="alert">%s</div>', $info);
    }

    if(isset($client['data']['tariff']['filter_available']) && (bool) $client['data']['tariff']['filter_available'] === true) {
        $filterSettings = $PHPShopGUI->setField('���������� �������', $PHPShopGUI->setCheckbox('filter_show_counts_new', 1, '��������� ���������� ������� � �������� ��������������', $data['filter_show_counts'])) .
            $PHPShopGUI->setField('������������ ����������', $PHPShopGUI->setSelect('filter_update_new', [
                ['���������', 0, $data['filter_update']],
                ['����������� ��������', 1, $data['filter_update']],
                ['�������� ��������', 2, $data['filter_update']]
            ], 300));
    } else {
        $filterSettings = sprintf('<div class="alert alert-danger" role="alert">%s</div>', __('���������� ������ ������� ���������� � ����� �������� �����.'));
    }

    if(is_null($error) && is_null($info)) {
        $Tab1 .= $PHPShopGUI->setCollapse('��������� ������',
            $PHPShopGUI->setField('������� � ���', $PHPShopGUI->setSelect('search_page_row_new', [
                [1, 1, $data['search_page_row']],
                [2, 2, $data['search_page_row']],
                [3, 3, $data['search_page_row']],
                [4, 4, $data['search_page_row']],
                [5, 5, $data['search_page_row']]
            ], 300)) .
            $PHPShopGUI->setField('���� "������� � ����������"', $PHPShopGUI->setSelect('find_in_categories_new', [
                ['�� ������������', 0, $data['find_in_categories']],
                ['����������� ��������', 1, $data['find_in_categories']],
                ['����������� �������', 2, $data['find_in_categories']]
            ], 300)) .
            $PHPShopGUI->setField('������������ ���-�� ��������� � ����� "������� � ����������"', $PHPShopGUI->setInputText(false, 'max_categories_new', $data['max_categories'], 300)) .
            $PHPShopGUI->setField('������� �� ��������', $PHPShopGUI->setInputText(false, 'search_page_size_new', $data['search_page_size'], 300)) .
            $PHPShopGUI->setField('���������� ��������', $PHPShopGUI->setInputText(false, 'misprints_new', $data['misprints'], 300)) .
            $PHPShopGUI->setField('�������� � ������� ������', $PHPShopGUI->setInputText(false, 'misprints_ajax_new', $data['misprints_ajax'], 300)) .
            $PHPShopGUI->setField('��������� �������� ��� ����� ���������� ������� ��', $PHPShopGUI->setInputText(false, 'misprints_from_cnt_new', $data['misprints_from_cnt'], 300)) .
            $PHPShopGUI->setField('�������������� ������', $PHPShopGUI->setCheckbox('search_show_informer_string_new', 1, '���������� ������ "������� XX ����������� � XX ����������."', $data['search_show_informer_string'])) .
            $PHPShopGUI->setField('�������������� ���������', $PHPShopGUI->setCheckbox('use_additional_categories_new', 1, '���������� �������������� ��������� �������', $data['use_additional_categories'])) .
            $PHPShopGUI->setField('��������� � ������� ������', $PHPShopGUI->setCheckbox('ajax_search_categories_new', 1, '�������� � ���������� �������� ������ ��������� ��������������� ���������� �������', $data['ajax_search_categories'])) .
            $PHPShopGUI->setField('������� � ������� ������', $PHPShopGUI->setInputText(false, 'ajax_search_products_cnt_new', $data['ajax_search_products_cnt'], 300)) .
            $PHPShopGUI->setField('���� ������ ������', $PHPShopGUI->setInputText(false, 'search_filter_new', $data['search_filter'], 300), 1, '���� � ���������� ������� ������. ������ ���������������� ��������� ElasticSearchFilterInterface.php') .
            $PHPShopGUI->setField('���� ������ �������� ������', $PHPShopGUI->setInputText(false, 'ajax_search_filter_new', $data['ajax_search_filter'], 300), 1, '���� � ���������� ������� �������� ������. ������ ���������������� ��������� ElasticAjaxSearchFilterInterface.php') .
            $PHPShopGUI->setField('������� � �������', $PHPShopGUI->setCheckbox('available_sort_new', 1, '�������� ������� ������ � �������', $data['available_sort'])) .
            $PHPShopGUI->setField('������ ������� �� ��������', $PHPShopGUI->setCheckbox('search_uid_first_new', 1, '������� ������ �� ������� ���������� ��������', $data['search_uid_first']))
        );

        $Tab1 .= $PHPShopGUI->setCollapse('��������� ������� �������', $filterSettings);
    }

    $Tab2 = '<div class="form-group form-group-sm"><div class="col-sm-12" style="padding-left: 20px;padding-right: 20px;">
                ' . $PHPShopGUI->setTextarea('synonyms_new', $data['synonyms'], true, '100%', 300,
                        '����� ������� ����� � �������. ������ ����� ���� � ����� ������. ��������:<br> �����, ������� <br> 
                            ryzen, ������') .
              '</div></div>';

    $info = '<h4>��������� ������</h4>
    <ol>
        <li><a href="https://elastica.host/register" target="_blank">���������������� �������</a> 
        � ������� �������� ����. ����� ������ ��������� ����� ����� ������������� ����������� ���������������� ����� ������������� 14 ����.</li>
        <li>� <a href="https://elastica.host/personal" target="_blank">������ ��������</a> ������� "��������� ����������" � ����������� <kbd>API �������������</kbd> � ���� <kbd>API �������������</kbd> �������� ������. ������ ������ "���������".</li>
        <li>��������� ���������� ������� � ���, ���������� ������� �� �������� ������.</li>
        <li>��� ������� ������� � ����������� ������ �������� ���� <kbd>������� � ����������</kbd>. ��� ������ ��������� � ����������� ��������� � ��� �������. �� ������� �� ������� ����� ����� ��������� ��������� ����������. 
        ���� <kbd>������� � ����������</kbd> �������� � ���� ��������� <kbd>����������� ��������</kbd> � ���������� � <kbd>����������� �������</kbd> ��� ����������� ������ � ��������-��������� � ����� ������� �������������.</li>
        <li>��������� ����� <kbd>�������������� ���������</kbd> ��������� �������� ����� �������������� ��������� ������ � ���� <kbd>������� � ����������</kbd>.</li>
        <li>������ <kbd>�������������� ������</kbd> � ��������� ���������� �������� ������ � ��������� ������.</li>
    </ol>
    <h4>������ �������</h4>
    <ol>
      <li>���������� ������ ������� �������� � �������� ������ <kbd>������</kbd> � <kbd>����</kbd>.</li>
        <li>� ������� ������� ���������� �������� �������������, ��� ������� ��� ������ � ��������. 
        ������������ �� ������ � �� ������ ������� ��������������, ��� ������� ��� �������, � ��� �� ����� ����� ��������� 
        ���������� ��������������, �������� "�������������", ��� ������ ���������.</li>
        <li>�������� ����� �������� ���������� ������� ��� ������� �������� ��������������.</li>
        <li>�������� ����� ��������������� ���������� ������� ������� ��� ������ ��������. ���� ������������ ������ �������� 
        �������������� � ������� - �������� ������ �������������, ��� ������� ��� ������� ��������� � ��������� ���������������, ����� ������������� ��� ������. 
        ���� �������� ����� <kbd>���������� �������</kbd> ���������� ������� ����� ������������� �����������.</li>
    </ol>';

    $Tab3 = $PHPShopGUI->setInfo($info);

    // ����� �����������
    $Tab4 = $PHPShopGUI->setPay();

    // ����� ����� ��������
    $PHPShopGUI->setTab(["��������", $Tab1, true], ["��������", $Tab2], ["����������", $Tab3], ["� ������", $Tab4]);

    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionUpdate.modules.edit");
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>