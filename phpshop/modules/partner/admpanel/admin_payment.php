<?

$TitlePage="Партнеры -> Заявки на вывод средств";




function actionStart() {
    global $PHPShopInterface,$PHPShopSystem,$PHPShopModules;

    $PHPShopInterface->link="../modules/partner/admpanel/adm_paymentID.php";
    $PHPShopInterface->size="530,530";
    $PHPShopInterface->setCaption(array("","1%"),array("Логин","30%"),array("Дата","10%"),array("E-mail","30%"),array("Сумма (".$PHPShopSystem->getDefaultValutaCode().')',"20%"),array("", "10%"),array("Статус &nbsp;&nbsp;&nbsp;", "10%", array('align' => 'right')));

    // SQL
    $PHPShopOrm = new PHPShopOrm();
    $result=$PHPShopOrm->query('SELECT a.*, b.login, b.mail FROM '.$PHPShopModules->getParam("base.partner.partner_payment").' AS a JOIN '.$PHPShopModules->getParam("base.partner.partner_users").' AS b ON a.partner_id = b.id order by a.id DESC limit 300');

    while($row = mysqli_fetch_array($result)) {
            $sum=number_format($row['sum'],"2",".","");
            $PHPShopInterface->setRow($row['id'],array('name'=>$row['login'],'link'=>'?path=modules.dir.partner.payment&id=' . $row['id']),PHPShopDate::dataV($row['date'],false),array('name'=>$row['mail'],'link'=>'mailto:' . $row['mail']),$sum,array('action' => array('edit', 'delete', 'id' => $row['id']), 'align' => 'center'),array('status' => array('enable' => $row['enabled'], 'align' => 'right', 'caption' => array('Выкл', 'Вкл'))));
        }

    $PHPShopInterface->Compile();
}
?>