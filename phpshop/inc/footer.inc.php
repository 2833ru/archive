<?php

/*
 * Подвал
 */

// Перехват модуля
if ($PHPShopNav->notPath('print')) {
    $PHPShopModules->setHookHandler('footer', 'footer');
}
else
    echo '</div>';

// Аналитика
$PHPShopAnalitica->counter();

echo '
  </body>
</html>';
?>