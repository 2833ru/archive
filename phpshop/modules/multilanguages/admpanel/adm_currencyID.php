<?php


function addCurrencyMultilanguages($data) {
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
            $Tab_info=$PHPShopGUI->setField("Название:", $PHPShopGUI->setInputText(null, 'multilanguages_name['.$value['id'].']', $multilanguages['multilanguages_name'][$value['id']] ));
            $Tab_info.=$PHPShopGUI->setField("Обозначение:", $PHPShopGUI->setInputText(null, 'multilanguages_code['.$value['id'].']', $multilanguages['multilanguages_code'][$value['id']] ));

           
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
function updateCurrencysMultilanguages($data) {
 
    $multilanguages['multilanguages_name'] = $_POST['multilanguages_name'];
    $multilanguages['multilanguages_code'] = $_POST['multilanguages_code'];

    $_POST['multilanguages_new'] = serialize($multilanguages);
}


$addHandler=array(
        'actionStart'=>'addCurrencyMultilanguages',
        'actionDelete'=>false,
        'actionUpdate'=>'updateCurrencysMultilanguages'
);

?>