<?php

include_once dirname(__DIR__) . '/class/ThumbnailImages.php';

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.thumbnailimages.thumbnailimages_system"));
$PHPShopOrm->debug=false;

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;

    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&id=' . $_GET['id']);
    return $action;
}

function actionGenerateOriginal() {
    global $PHPShopOrm;

    $data = $PHPShopOrm->select();
    $thumbnailImages = new ThumbnailImages();
    $result = $thumbnailImages->generateOriginal();

    if('original' !== $data['last_operation']) {
        $data['processed'] = 0;
    }

    echo '<div class="alert alert-success" id="rules-message"  role="alert">' .
        __(sprintf('���������. ���������� �����������: � %s �� %s', (int) $data['processed'], (int) $data['processed'] + (int) $result['count']))
        . '</div>';

    if(count($result['skipped']) > 0) {
        $skipped = '';
        foreach ($result['skipped'] as $file) {
            $skipped .= '�� ������ ����: ' . $file . '<br>';
        }
        echo '<div class="alert alert-warning" id="rules-message"  role="alert">' .
            $skipped
            . '</div>';
    }
}
function actionGenerateThumbnail() {
    global $PHPShopOrm;

    $data = $PHPShopOrm->select();
    $thumbnailImages = new ThumbnailImages();
    $result = $thumbnailImages->generateThumbnail();

    if('thumb' !== $data['last_operation']) {
        $data['processed'] = 0;
    }

    echo '<div class="alert alert-success" id="rules-message"  role="alert">' .
        __(sprintf('���������. ���������� �����������: � %s �� %s', (int) $data['processed'], (int) $data['processed'] + (int) $result['count']))
        . '</div>';

    if(count($result['skipped']) > 0) {
        $skipped = '';
        foreach ($result['skipped'] as $file) {
            $skipped .= '�� ������ ����: ' . $file . '<br>';
        }
        echo '<div class="alert alert-warning" id="rules-message"  role="alert">' .
            $skipped
            . '</div>';
    }
}

// ���������� ������ ������
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;

    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $PHPShopOrm->update(['version_new' => $new_version]);
}

// ��������� ������� ��������
function actionStart() {
    global $PHPShopGUI,$PHPShopOrm, $TitlePage, $select_name;
    
    // �������
    $data = $PHPShopOrm->select();

    $PHPShopGUI->action_button['������������� ������'] = [
        'name' => '������������� ������',
        'action' => 'saveIDthumb',
        'class' => 'btn  btn-default btn-sm navbar-btn',
        'type' => 'submit',
        'icon' => 'glyphicon glyphicon-import'
    ];

    $PHPShopGUI->action_button['������������� �������'] = [
        'name' => '������������� �������',
        'action' => 'saveIDorig',
        'class' => 'btn  btn-default btn-sm navbar-btn',
        'type' => 'submit',
        'icon' => 'glyphicon glyphicon-import'
    ];

    $PHPShopGUI->setActionPanel($TitlePage, $select_name, ['������������� ������','������������� �������', '��������� � �������']);

    $Tab1 = '<div class="alert alert-info" role="alert">' .
               __('����������, ������������ � ����������� �� ������� <kbd>��������</kbd> ����� �������������� ������.')
            . '</div>';

    $Tab1 .= $PHPShopGUI->setField('������������ ����������� �� ���', $PHPShopGUI->setInputText(false, 'limit_new', $data['limit'], 150));

    $Info = '<p>
        ������ ��������� ������������� ����� �������� �� ��������� � <kbd>���������</kbd> &rarr; <kbd>�����������</kbd> ����������.<br>
        ������ �������� ��� ������� � �������� ������������ �� ������ ��������:
        <ul>
            <li>����������� ��������� <kbd>��������� �������� ����������� ��� ����������</kbd></li>
            <li>���� ��������� �������� - ����������� ������� ����� �������� � ��������� <code>_big</code>, ��� ����������� �������� � ������������ �������, ��� �������� ������ ������������ ���.</li>
            <li>���� ��������� ��������� ��� ����������� � ��������� <code>_big</code> ��� - ��� ��������� ������ ����������� ������������ ������� �������� ������, ���������� �������� ���������� 
                <kbd>����. ������ ���������</kbd> � <kbd>����. ������ ���������</kbd>.
            </li>
            <li>��� ����������� ������� � ��������� <code>_s</code> ����� �������� ������ ���������������� �������������.</li>
        </ul>
        
       ��������� ������� ����������� �������� ������, ���� �������� ��������� <kbd>��������� �������� ����������� ��� ����������</kbd> ��� ��������� ������� 
       <kbd>����. ������ ���������</kbd> � <kbd>����. ������ ���������</kbd> � ���������� ������������� ������� �����������.
        </p>';

    $Tab2 = $PHPShopGUI->setInfo($Info);


    // ���������� �������� 2
    $Tab3 = $PHPShopGUI->setPay(false, true, $data['version'], true);

    // ����� ����� ��������
    $PHPShopGUI->setTab(["��������", $Tab1, true], ["��������", $Tab2], ["� ������", $Tab3]);

    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionUpdate.modules.edit") .
            $PHPShopGUI->setInput("submit", "saveIDthumb", "���������", "right", 80, "", "but", "actionGenerateThumbnail.modules.edit").
            $PHPShopGUI->setInput("submit", "saveIDorig", "���������", "right", 80, "", "but", "actionGenerateOriginal.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>