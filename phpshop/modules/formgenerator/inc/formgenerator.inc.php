<?php

class PHPShopFormgeneratorElement {
    
    function PHPShopFormgeneratorElement() {
        $this->objBase=$GLOBALS['SysValue']['base']['formgenerator']['formgenerator_forms'];
    }
    
    
    function forma($path) {
        $error=null;
        
        $path = PHPShopSecurity::TotalClean($path,4);
        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $data = $PHPShopOrm->select(array('*'),array('path'=>"='".$path."'",'enabled'=>"='1'"),false,array('limit'=>1));

        // Сообщение обязательных полей
        if(!empty($_GET['error'])) $error=$data['error_message'];
        
        if(is_array($data)) {
            $forma_content= '<p>'.$error.'</p><h2>'.$data['name'].'</h2><form method="post" enctype="multipart/form-data" name="formgenerator" id="formgenerator" action="/formgenerator/'.$path.'">
            '.Parser($data['content']).'
                <p id="formgenerator_buttons">
            <input type="hidden" name="forma_id" value="'.$data['id'].'">
            <input class="user" type="reset" value="Очистить">
            <input class="user" type="submit" name="forma_send" value="Отправить">
             </p>
</form>';
            return $forma_content;
        }
        else return 'Форма не найдена в базе';
    }
    
}
?>