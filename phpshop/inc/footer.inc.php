<?php

/*
 * Подвал
 */

// Перехват модуля
if ($PHPShopNav->notPath('print')) {
    $PHPShopModules->setHookHandler('footer', 'footer');
}


// Аналитика
$PHPShopAnalitica->counter();

echo '
    </div>
  </body>
</html>';
?>