<?php

function tab_discount($cumulative_discount) {
    global $PHPShopGUI, $PHPShopSystem;
    $cumulative_discount = unserialize($cumulative_discount);

    if (is_array($cumulative_discount))
        foreach ($cumulative_discount as $key => $value) {

            if(!empty($value))
            $cumulative_html.= "<tr>"
                    . "<td>" . $PHPShopGUI->setInputText(false, 'cumulative_sum_ot[' . $key . ']', $value['cumulative_sum_ot'], "200") . "</td>"
                    . "<td>" . $PHPShopGUI->setInputText(false, 'cumulative_sum_do[' . $key . ']', $value['cumulative_sum_do'], "200") . "</td>"
                    . "<td>" . $PHPShopGUI->setInputText(false, 'cumulative_discount[' . $key . ']', $value['cumulative_discount'], "100") . "</td>"
                    . "<td class='text-center'>" . $PHPShopGUI->setCheckbox('cumulative_enabled[' . $key . ']', 1, false, $value['cumulative_enabled']) . "</td>"
                    . "</tr>"
                    . "<tr>";
        }


    $disp = "<table class='table table-striped'>
              <tr><th>Сумма от " . $PHPShopSystem->getDefaultValutaCode() . "</th><th>Сума до " . $PHPShopSystem->getDefaultValutaCode() . "</th><th>Скидка %</th><th class='text-center'>Вкл / Выкл</th></tr>"
            . $cumulative_html
            . "<tr>"
            . "<td>" . $PHPShopGUI->setInputText(false, 'cumulative_sum_ot[]', null, "200") . "</td>"
            . "<td>" . $PHPShopGUI->setInputText(false, 'cumulative_sum_do[]', null, "200") . "</td>"
            . "<td>" . $PHPShopGUI->setInputText(false, 'cumulative_discount[]', null, "100") . "</td>"
            . "<td class='text-center'>" . $PHPShopGUI->setCheckbox('cumulative_enabled[]', 1, false, null) . "</td>"
            . "</tr>"
            . "<tr>"
            . "<td>" . $PHPShopGUI->setInputText(false, 'cumulative_sum_ot[]', null, "200") . "</td>"
            . "<td>" . $PHPShopGUI->setInputText(false, 'cumulative_sum_do[]', null, "200") . "</td>"
            . "<td>" . $PHPShopGUI->setInputText(false, 'cumulative_discount[]', null, "100") . "</td>"
            . "<td class='text-center'>" . $PHPShopGUI->setCheckbox('cumulative_enabled[]', 1, false, null) . "</td>"
            . "</tr>"
            . "<tr>"
            . "<td >" . $PHPShopGUI->setInputText(false, 'cumulative_sum_ot[]', null, "200") . "</td>"
            . "<td>" . $PHPShopGUI->setInputText(false, 'cumulative_sum_do[]', null, "200") . "</td>"
            . "<td>" . $PHPShopGUI->setInputText(false, 'cumulative_discount[]', null, "100") . "</td>"
            . "<td class='text-center'>" . $PHPShopGUI->setCheckbox('cumulative_enabled[]', 1, false, null) . "</td>"
            . "</tr>"
            . "</table>";

    $disp.=$PHPShopGUI->setHelp('Новые поля появятся после заполнения текущих полей и сохранения результата.');

    return $disp;
}

?>