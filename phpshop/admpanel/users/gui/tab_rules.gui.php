<?php

function rules_checked($a, $b) {
    //if(empty($a)) $a='1-1-1';
    $array = explode("-", $a);
    return $array[$b];
}

function tab_rules($row, $autofill = false) {
    global $PHPShopGUI;
    $status = unserialize($row);

    $dis = '<table id="rules" class="table table-striped table-bordered text-center ' . $autofill . ' ">
                           <tr>
                            <th class="text-center">Раздел</th>
                            <th class="text-center">Обзор <br><input id="select_rules_view" type="checkbox"></th>
                            <th class="text-center">Редактирование <br><input id="select_rules_edit" type="checkbox"></th>
                            <th class="text-center">Создание <br><input id="select_rules_creat" type="checkbox"></th>
                            <th class="text-center">Удаление</th>
                            <th class="text-center">Дополнительно <br><input id="select_rules_option" type="checkbox"></th>
                           </tr>
                            <tr>
                            <td>Настройка системы</td>
                                <td>' . $PHPShopGUI->setCheckbox('system_rul_1', 1, false, rules_checked($status[system], 0)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('system_rul_2', 1, false, rules_checked($status[system], 1)) . '</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                           <tr>
                                <td>Товары</td>
                                <td>' . $PHPShopGUI->setCheckbox('catalog_rul_1', 1, false, rules_checked($status[catalog], 0)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('catalog_rul_2', 1, false, rules_checked($status[catalog], 1)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('catalog_rul_3', 1, false, rules_checked($status[catalog], 2)) . '</td>
                                <td>-</td>
                                 <td>' . $PHPShopGUI->setCheckbox('catalog_rul_5', 1, 'Управление правами', rules_checked($status[catalog], 4)) . ' ' . $PHPShopGUI->setCheckbox('catalog_rul_4', 1, 'Все товары', rules_checked($status[catalog], 3)) . '</td>
                            </tr>
                            <tr>
                                <td>Заказы</td>
                                <td>' . $PHPShopGUI->setCheckbox('order_rul_1', 1, false, rules_checked($status[order], 0)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('order_rul_2', 1, false, rules_checked($status[order], 1)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('order_rul_3', 1, false, rules_checked($status[order], 2)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('order_rul_4', 1, false, rules_checked($status[order], 3)) . '</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Администраторы</td>
                                <td>' . $PHPShopGUI->setCheckbox('users_rul_1', 1, false, rules_checked($status[users], 0)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('users_rul_2', 1, false, rules_checked($status[users], 1)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('users_rul_3', 1, false, rules_checked($status[users], 2)) . '</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                           <tr>
                                <td>Пользователи</td>
                                <td>' . $PHPShopGUI->setCheckbox('shopusers_rul_1', 1, false, rules_checked($status[shopusers], 0)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('shopusers_rul_2', 1, false, rules_checked($status[shopusers], 1)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('shopusers_rul_3', 1, false, rules_checked($status[shopusers], 2)) . '</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Новости</td>
                                <td>' . $PHPShopGUI->setCheckbox('news_rul_1', 1, false, rules_checked($status[news], 0)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('news_rul_2', 1, false, rules_checked($status[news], 1)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('news_rul_3', 1, false, rules_checked($status[news], 2)) . '</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                           <tr>
                                <td>Отчеты</td>
                                <td>' . $PHPShopGUI->setCheckbox('report_rul_1', 1, false, rules_checked($status[report], 0)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('report_rul_2', 1, false, rules_checked($status[report], 1)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('report_rul_3', 1, false, rules_checked($status[report], 2)) . '</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                           <td>Страницы</td>
                                <td>' . $PHPShopGUI->setCheckbox('page_rul_1', 1, false, rules_checked($status[page], 0)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('page_rul_2', 1, false, rules_checked($status[page], 1)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('page_rul_3', 1, false, rules_checked($status[page], 2)) . '</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                            <td>Текстовые блоки</td>
                                <td>' . $PHPShopGUI->setCheckbox('menu_rul_1', 1, false, rules_checked($status[menu], 0)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('menu_rul_2', 1, false, rules_checked($status[menu], 1)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('menu_rul_3', 1, false, rules_checked($status[menu], 2)) . '</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                             <tr>
                                <td>Отзывы</td>
                                <td>' . $PHPShopGUI->setCheckbox('gbook_rul_1', 1, false, rules_checked($status[gbook], 0)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('gbook_rul_2', 1, false, rules_checked($status[gbook], 1)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('gbook_rul_3', 1, false, rules_checked($status[gbook], 2)) . '</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                            <td>Баннеры</td>
                                <td>' . $PHPShopGUI->setCheckbox('banner_rul_1', 1, false, rules_checked($status[banner], 0)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('banner_rul_2', 1, false, rules_checked($status[banner], 1)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('banner_rul_3', 1, false, rules_checked($status[banner], 2)) . '</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                            <td>Слайдер</td>
                                <td>' . $PHPShopGUI->setCheckbox('slider_rul_1', 1, false, rules_checked($status[slider], 0)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('slider_rul_2', 1, false, rules_checked($status[slider], 1)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('slider_rul_3', 1, false, rules_checked($status[slider], 2)) . '</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                            <td>Ссылки</td>
                                <td>' . $PHPShopGUI->setCheckbox('links_rul_1', 1, false, rules_checked($status[links], 0)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('links_rul_2', 1, false, rules_checked($status[links], 1)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('links_rul_3', 1, false, rules_checked($status[links], 2)) . '</td>
                                <td>-</td>
                                <td>-</td>
                            </tr> 
                            <tr>
                            <td>Прайс-лист</td>
                                <td>' . $PHPShopGUI->setCheckbox('csv_rul_1', 1, false, rules_checked($status[csv], 0)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('csv_rul_2', 1, false, rules_checked($status[csv], 1)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('csv_rul_3', 1, false, rules_checked($status[csv], 2)) . '</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                            <td>Опрос</td>
                                <td>' . $PHPShopGUI->setCheckbox('opros_rul_1', 1, false, rules_checked($status[opros], 0)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('opros_rul_2', 1, false, rules_checked($status[opros], 1)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('opros_rul_3', 1, false, rules_checked($status[opros], 2)) . '</td>
                                <td>-</td>
                                <td>-</td>
                            </tr> 
                            <tr>
                            <td>Рейтинг</td>
                                <td>' . $PHPShopGUI->setCheckbox('rating_rul_1', 1, false, rules_checked($status[rating], 0)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('rating_rul_2', 1, false, rules_checked($status[rating], 1)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('rating_rul_3', 1, false, rules_checked($status[rating], 2)) . '</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                            <td>Экспорт / Импорт данных</td>
                                <td>' . $PHPShopGUI->setCheckbox('exchange_rul_1', 1, false, rules_checked($status[exchange], 0)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('exchange_rul_2', 1, false, rules_checked($status[exchange], 1)) . '</td>
                                <td></td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                            <td>Скидки и статусы</td>
                                <td>' . $PHPShopGUI->setCheckbox('discount_rul_1', 1, false, rules_checked($status[discount], 0)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('discount_rul_2', 1, false, rules_checked($status[discount], 1)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('discount_rul_3', 1, false, rules_checked($status[discount], 2)) . '</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                            <td>Валюты</td>
                                <td>' . $PHPShopGUI->setCheckbox('currency_rul_1', 1, false, rules_checked($status[currency], 0)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('currency_rul_2', 1, false, rules_checked($status[currency], 1)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('currency_rul_3', 1, false, rules_checked($status[currency], 2)) . '</td>
                                <td>-</td>
                                <td>-</td>
                            </tr> 
                            <tr>
                            <td>Доставка</td>
                                <td>' . $PHPShopGUI->setCheckbox('delivery_rul_1', 1, false, rules_checked($status[delivery], 0)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('delivery_rul_2', 1, false, rules_checked($status[delivery], 1)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('delivery_rul_3', 1, false, rules_checked($status[delivery], 2)) . '</td>
                                <td>-</td>
                                <td>-</td>
                            </tr> 
                            <tr>
                            <td>Мультибаза</td>
                                <td>' . $PHPShopGUI->setCheckbox('servers_rul_1', 1, false, rules_checked($status[servers], 0)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('servers_rul_2', 1, false, rules_checked($status[servers], 1)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('servers_rul_3', 1, false, rules_checked($status[servers], 2)) . '</td>
                                <td>-</td>
                                <td>-</td>
                            </tr> 
                            <tr>
                                <td>Модули</td>
                                <td>' . $PHPShopGUI->setCheckbox('modules_rul_1', 1, false, rules_checked($status[modules], 0)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('modules_rul_2', 1, false, rules_checked($status[modules], 1)) . '</td>
                                <td>' . $PHPShopGUI->setCheckbox('modules_rul_3', 1, false, rules_checked($status[modules], 2)) . '</td>
                                <td>-</td>
                                <td>' . $PHPShopGUI->setCheckbox('modules_rul_4', 1, 'Загрузка модулей', rules_checked($status[modules], 3)) . '</td>
                            </tr>
                            <tr>
                            <td>Обновление ПО</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>' . $PHPShopGUI->setCheckbox('update_rul_1', 1, 'Установка обновлений', rules_checked($status[update], 0)) . '</td>
                            </tr> 
       </table>';

    return $dis;
}

?>