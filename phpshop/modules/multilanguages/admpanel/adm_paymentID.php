<?php


function addPaymentMultilanguages($data) {
    global $PHPShopGUI, $PHPShopModules, $PHPShopSystem, $PHPShopBase, $link_db;


    
    $multilanguages = unserialize($data['multilanguages']);

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.multilanguages.multilanguages"));
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'num'), array('limit' => 100));
    if(is_array($data)) {
        foreach ($data as $key => $value) {
            $i++;
            if($i==1) {$active = 'active';} else {$active = '';}

            // Наименование
            $Tab_info=$PHPShopGUI->setField("Наименование:", $PHPShopGUI->setInputText(null, 'multilanguages_name['.$value['id'].']', $multilanguages['multilanguages_name'][$value['id']] ));
            $Tab_info.=$PHPShopGUI->setField("Заголовок:", $PHPShopGUI->setInputText(null, 'multilanguages_message_header['.$value['id'].']', $multilanguages['multilanguages_message_header'][$value['id']] ));

            //Полное описание
            $oFCKeditor = new Editor('content_multilanguages_'.$value['id']);
            $oFCKeditor->Height = '350';
            $oFCKeditor->Config['EditorAreaCSS'] = chr(47) . "phpshop" . chr(47) . "templates" . chr(47) . $PHPShopSystem->getValue('skin') . chr(47) . $PHPShopBase->getParam('css.default');
            $oFCKeditor->ToolbarSet = 'Normal';
            $oFCKeditor->Value = $multilanguages['multilanguages_content'][$value['id']];
            $info = $oFCKeditor->AddGUI();
            $Tab_info.=$PHPShopGUI->setField("Сообщение:", $info);

            
            $tablist .= '<li role="presentation" class="'.$active.' in">
                <a href="#tabs-lang-'.$i.'" aria-controls="tabs-lang-'.$i.'" role="tab" data-toggle="tab" data-id="'.$value['name'].'">'.$value['name'].'</a>
            </li>';
            $tab_content .= '<div role="tabpanel" class="tab-pane '.$active.' in fade" id="tabs-lang-'.$i.'"><p></p>'.$Tab_info.'</div>';


        }
    }

    $Tab3 = '
    <div role="tabpanel">
        <ul class="nav nav-pills" role="tablist">
            '.$tablist.'
        </ul>
        <div class="tab-content">
            '.$tab_content.'
        </div>
    </div>';
                

    $PHPShopGUI->addTab(array("Языки",$Tab3,450));
}
function updatePaymentMultilanguages($data) {

    if(is_array($_POST['multilanguages_name'])) {
        foreach ($_POST['multilanguages_name'] as $key => $value) {
            $multilanguages_description[$key] = $_POST['description_multilanguages_'.$key];
            $multilanguages_content[$key] = $_POST['content_multilanguages_'.$key];
        }
    }
 
    $multilanguages['multilanguages_name'] = $_POST['multilanguages_name'];
    $multilanguages['multilanguages_message_header'] = $_POST['multilanguages_message_header'];
    $multilanguages['multilanguages_content'] = $multilanguages_content;


    $_POST['multilanguages_new'] = serialize($multilanguages);
}


$addHandler=array(
        'actionStart'=>'addPaymentMultilanguages',
        'actionDelete'=>false,
        'actionUpdate'=>'updatePaymentMultilanguages'
);

?>