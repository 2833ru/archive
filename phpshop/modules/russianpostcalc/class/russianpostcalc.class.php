<?php

class russianpostcalc {

    public function __construct() {
        $this->api = "http://russianpostcalc.ru/api_beta_077.php";
    }

    public function option() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['russianpostcalc']['russianpostcalc_system']);
        return $PHPShopOrm->select();
    }

    protected function _russianpostcalc_api_communicate($request) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->api);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($curl);

        curl_close($curl);
        if ($data === false) {
            return "10000 server error";
        }

        $js = json_decode($data, $assoc = true);
        return $js;
    }

    public function russianpostcalc_api_calc($apikey, $password, $from_index, $to_index, $weight = 3, $ob_cennost_rub = 1000) {
        $request = array("apikey" => $apikey,
            "method" => "calc",
            "from_index" => $from_index,
            "to_index" => $to_index,
            "weight" => $weight,
            "ob_cennost_rub" => $ob_cennost_rub
        );

        if ($password != "") {
            //если пароль указан, аутентификация по методу API ключ + API пароль.
            $all_to_md5 = $request;
            $all_to_md5[] = $password;
            $hash = md5(implode("|", $all_to_md5));
            $request["hash"] = $hash;
        }

        $ret = $this->_russianpostcalc_api_communicate($request);

        return $ret;
    }

}

?>
