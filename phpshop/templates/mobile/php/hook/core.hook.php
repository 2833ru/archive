<?php

function Compile_mob_hook($obj) {

    if (!empty($GLOBALS['AddToTemplateMDetect'])) {

        $detect = $GLOBALS['AddToTemplateMDetect']->detect();
        //$detect='sybmian';
        
        if(empty($detect))
            $_SESSION['MobileDetect'] = false;
        elseif($detect == 'wp')
            $_SESSION['MobileDetect'] = false;
        elseif($detect == 'symbian')
            $_SESSION['MobileDetect'] = $detect;
        else
            $_SESSION['MobileDetect'] = true;
    }
    else{
        $_SESSION['MobileDetect'] = false;
    }
    
    
    // Cookie для JS
    if(empty($_SESSION['MobileDetect'])){
        @setcookie("PCDetect", 1);
    }
    
        

    // Выключаем эмуляцию PC
    if ($_SESSION['MobileDetect']) {
        ob_start();
        ParseTemplate($obj->getValue($obj->template));
        $result = ob_get_clean();
        echo str_replace('onclick', 'ontest', $result);
        return true;
    }
}

$addHandler = array
    (
    'Compile' => 'Compile_mob_hook'
);
?>