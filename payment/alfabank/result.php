<?php
/**
 * Обработчик оплаты заказа через Robox
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopPayment
 */

$_classPath = "../../phpshop/";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("system");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopSystem = new PHPShopSystem();


if(empty($GLOBALS['SysValue'])) exit(header("Location: /"));

define('USERNAME', urlencode($SysValue['alfabank']['alf_login']));
define('PASSWORD', $SysValue['alfabank']['alf_pass']);
define('GATEWAY_URL', $SysValue['alfabank']['alf_gateway_url']);
define('RETURN_URL', $SysValue['alfabank']['alf_return_url']);


function gateway($method, $data) {
  $curl = curl_init(); // Инициализируем запрос
  curl_setopt_array($curl, array(
        CURLOPT_URL => GATEWAY_URL.$method, // Полный адрес метода
        CURLOPT_RETURNTRANSFER => true, // Возвращать ответ
        CURLOPT_POST => true, // Метод POST
        CURLOPT_POSTFIELDS => http_build_query($data) // Данные в запросе
  ));
  $response = curl_exec($curl); // Выполненяем запрос
  $response = json_decode($response, true); // Декодируем из JSON в массив
  curl_close($curl); // Закрываем соединение
  return $response; // Возвращаем ответ
}


//if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
$data = array(
'userName' => USERNAME,
'password' => PASSWORD,
'orderNumber' => urlencode($_POST['orderNumber']),
'amount' => urlencode($_POST['amount']),
'returnUrl' => RETURN_URL
);


/**
* ЗАПРОС РЕГИСТРАЦИИ ОДНОСТАДИЙНОГО ПЛАТЕЖА В ПЛАТЕЖНОМ ШЛЮЗЕ
* register.do
*
* ПАРАМЕТРЫ
* userName Логин магазина.
* password Пароль магазина.
* orderNumber Уникальный идентификатор заказа в магазине.
* amount Сумма заказа в копейках.
* returnUrl Адрес, на который надо перенаправить пользователя в случае успешной оплаты.
*
* ОТВЕТ
* В случае ошибки:
* errorCode Код ошибки. Список возможных значений приведен в таблице ниже.
* errorMessage Описание ошибки.
*
* В случае успешной регистрации:
* orderId Номер заказа в платежной системе. Уникален в пределах системы.
* formUrl URL платежной формы, на который надо перенаправить браузер клиента.
*
* Код ошибки Описание
* 0 Обработка запроса прошла без системных ошибок.
* 1 Заказ с таким номером уже зарегистрирован в системе.
* 3 Неизвестная (запрещенная) валюта.
* 4 Отсутствует обязательный параметр запроса.
* 5 Ошибка значения параметра запроса.
* 7 Системная ошибка.
*/

//$data =  json_encode($data);

    $response = gateway('register.do', $data); 
/**
* ЗАПРОС РЕГИСТРАЦИИ ДВУХСТАДИЙНОГО ПЛАТЕЖА В ПЛАТЕЖНОМ ШЛЮЗЕ
* registerPreAuth.do
*
* Параметры и ответ точно такие же, как и в предыдущем методе.
* Необходимо вызывать либо register.do, либо registerPreAuth.do.
*/
// $response = gateway('registerPreAuth.do', $data);

    if (isset($response['errorCode'])) { // В случае ошибки вывести ее
      echo 'Ошибка #' . $response['errorCode'] . ': ' . $response['errorMessage'];
    } else { // В случае успеха перенаправить пользователя на плетжную форму
      header('Location: ' . $response['formUrl']);
      die();
    }
	
	
//} 
exit();
?>