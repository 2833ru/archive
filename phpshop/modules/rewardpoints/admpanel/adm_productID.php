<?php

function addPoints($data) {
    global $PHPShopGUI;

    $Tab1 = $PHPShopGUI->setField("���������� ������:", $PHPShopGUI->setInput("text", "point_new", $data['point'], "left", 100,false,false,false,'���� ������ � ������'), "none");
    $Tab1 .= $PHPShopGUI->setField("����������� ������� �� �����:", 
    	$PHPShopGUI->setCheckbox('check_pay_new', 1, '��', $data['check_pay'])
    , "none");


    $PHPShopGUI->addTab(array("�����",$Tab1,450));
}
function updatePoints($data) {
	if($_POST['check_pay_new']!=1) {
		$_POST['check_pay_new'] = 0;
	}
}


$addHandler=array(
        'actionStart'=>'addPoints',
        'actionDelete'=>false,
        'actionUpdate'=>'updatePoints'
);

?>