<?php

/**
 * Панель дополнительных шаблонов
 * @param array $row массив данных
 * @return string 
 */
function tab_base($data) {
    global $PHPShopGUI, $skin_base_path, $PHPShopBase;

    $disp=null;

    // Установленные шаблоны
    if (is_array($data))
        foreach ($data as $val) {
            $path_parts = pathinfo($val);
            $ready_theme[] = $path_parts['basename'];
        }


    /*
      $i = 1;
      $count = 0;
      $data_pic = xml2array($skin_base_path . '/commerce.php', "template", true);

      $title = '<p class="text-muted hidden-xs data-row">'.__('Вы можете приобрести любой платный шаблон из представленных здесь. Все платные шаблоны сверстаны в современном стандарте HTML5 и полностью адаптивны для мобильных устройств. Вы можете установить ознакомительную версию платного шаблона и использовать ее 30 дней. По истечению ознакомительного режима, шаблон перейдет в режим ограниченного функционала. После покупки шаблона выдается персональный ключ для использования дизайна на вашем сайте. Для каждого нового домена требуется приобретать отдельный ключ шаблона').'.</p>';

      // Платные шаблоны
      $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['templates_key']);
      $data_template = $PHPShopOrm->select(array('*'), false, false, array('limit' => 100));
      if (is_array($data_template))
      foreach ($data_template as $row)
      $template_com[$row['path']] = $row;

      $img_list = $buy = null;
      $PHPShopTemplates = new PHPShopTemplates();
      if (is_array($data_pic))
      foreach ($data_pic as $row) {

      if ($i == 1)
      $img_list.='<div class="row">';

      if (@in_array($row['name'], $ready_theme)) {
      $main = "hide";
      $serial = "btn-default";
      $panel = 'panel-warning';

      if ($PHPShopTemplates->checkKey($row['name'], $template_com[$row['name']]['key'])) {
      $panel = 'panel-success';
      $serial = $buy = $trial = 'hide';
      $active = null;
      } else {
      $panel = 'panel-warning';
      $buy = $trial = null;
      $active = 'hide';
      }

      if (is_array($template_com[$row['name']]))
      $mes = ' - Установлен';
      else {
      $date_end = time() + 2592000;
      $key = null;
      $sql = "INSERT INTO " . $GLOBALS['SysValue']['base']['templates_key'] . "  VALUES ('" . $row['name'] . "'," . $date_end . ",'" . $key . "','" . md5($row['name'] . $date_end . $_SERVER['SERVER_NAME'] . $key) . "')";
      mysqli_query($PHPShopBase->link_db, $sql);
      $mes = ' - Загружен';
      }
      } else {
      $main = "btn-default";
      $panel = 'panel-default';
      $serial = $active = $trial = "hide";
      $mes = $buy = null;
      }

      $day = intval(round(($template_com[$row['name']]['date'] - time()) / (86400)));
      if ($day > 30 or $day < 0)
      $day = 30;



      $img_list.='<div class="col-md-4"><div class="panel ' . $panel . '"><div class="panel-heading">' . $row['name'] . $mes . '
      <span class="glyphicon glyphicon-plus pull-right btn ' . $main . ' btn-xs skin-load" data-path="' . $row['name'] . '" data-type="commerce" data-toggle="tooltip" data-placement="top" title="' . __('Загрузить') . '"></span>
      <span class="glyphicon glyphicon-credit-card pull-right btn ' . $serial . ' btn-xs skin-serial" data-path="' . $row['name'] . '" data-type="commerce" data-toggle="tooltip" data-placement="top" title="' . __('Ввести ключ') . '" data-key="' . $template_com[$row['name']]['key'] . '"></span>
      </div><div class="panel-body text-center"><img class="image-shadow" style="max-height:150px" src="' . $skin_base_path . $row['icon'] . '">

      </div>
      <div class="text-center panel-footer">
      <a class="btn btn-sm btn-default active ' . $active . '" data-toggle="tooltip" data-placement="top" title="' . __('Ключ: ') . $template_com[$row['name']]['key'] . '"><span class="glyphicon glyphicon-ok"></span> ' . __('Активирован').'</a>
      <div class="btn-group" role="group" aria-label="...">
      <a class="btn btn-sm btn-primary ' . $buy . '" data-toggle="tooltip" data-placement="top" title="' . __('Купить лицензию') . '" href="http://www.phpshop.ru/market/?F=' . getPayLink($row['price'], $row['name']) . '" target="_blank">' . __('Купить').' ' . $row['price'] . ' <span class="rubznak hidden-xs">p</span></a>
      <a class="btn btn-sm btn-default ' . $trial . '" data-toggle="tooltip" data-placement="top" title="' . __('Осталось до конца дней') . '"><span class="glyphicon glyphicon-time"></span> ' . $day . ' дн.</a>

      <a class="btn btn-sm btn-default ' . $buy . '" data-toggle="tooltip" data-placement="top" title="' . __('Посмотреть демо') . '" href="http://market.phpshop.ru/?skin=' . $row['name'] . '" target="_blank"><span class="glyphicon glyphicon-eye-open"></span> ' . __('Демо').'</a>
      </div>
      </div>

      </div>
      </div>';

      if ($i == 3) {
      $img_list.='</div>';
      $i = 1;
      }
      else
      $i++;

      $count++;
      }


      if (count($data_pic) % 3 != 0)
      $img_list.='</div>';


      // Архивные шаблоны
      $i = 1;
      $count = 0;
      $data_pic = xml2array($skin_base_path . '/template-archive.php', "template", true);
      arsort($data_pic);

      $title_free = '<p class="text-muted hidden-xs data-row">' . __('Ниже представлены классические бесплатные шаблоны от предыдущих версий PHPShop. Функциональность классических шаблонов может отличаться от современных шаблонов. Для 100% работоспособности продукта рекомендуется использовать предустановленные шаблоны').'.</p>';
      $img_list_free = null;
      if (is_array($data_pic))
      foreach ($data_pic as $row) {

      if ($i == 1)
      $img_list_free.='<div class="row">';

      if (@in_array($row['name'], $ready_theme)) {
      $main = "hide";
      $panel = 'panel-success';
      $mes = ' - Установлен';
      } else {
      $main = "btn-default";
      $panel = 'panel-default';
      $mes = null;
      }



      $img_list_free.='<div class="col-md-4"><div class="panel ' . $panel . '"><div class="panel-heading">' . $row['name'] . $mes . ' <span class="glyphicon glyphicon-plus pull-right btn ' . $main . ' btn-xs skin-load" data-path="' . $row['name'] . '" data-toggle="tooltip" data-type="archive" data-placement="top" title="' . __('Загрузить') . '"></span></div><div class="panel-body text-center"><img class="image-shadow image-skin"  src="' . $skin_base_path . $row['icon'] . '"></div></div></div>';

      if ($i == 3) {
      $img_list_free.='</div>';
      $i = 1;
      }
      else
      $i++;

      $count++;
      }


      if (count($data_pic) % 3 != 0)
      $img_list_free.='</div>';

     */
    // Дефолтные шаблоны 
    $i = 1;
    $count = 0;
    $data_pic = xml2array($skin_base_path . '/template5.php', "template", true);
    arsort($data_pic);

    $title_default = '<p class="text-muted hidden-xs data-row">' . __('Ниже представлены штатные бесплатные шаблоны, адаптированные для мобильных устройств. Для редактирования шаблона, кликните на кнопку "Настроить". Выбранный новый шаблон нужно сохранить в <a href="?path=system#1"><span class="glyphicon glyphicon-share-alt"></span>Основых настройках</a> для отображения посетителям магазина') . '.</p>';
    $img_list_default = null;
    if (is_array($data_pic))
        foreach ($data_pic as $row) {

            if ($i == 1)
                $img_list_default .= '<div class="row">';

            if (@in_array($row['name'], $ready_theme)) {
                $main = "hide";
                $panel = 'panel-default';
                $mes = '  <span class="pull-right text-muted">' . __('загружен') . '</span>';
                $demo = null;
                $reload = 'skin-reload';
                $load = __('Перегрузить');
                $icon = 'glyphicon-save';
            } else {
                $main = "btn-default";
                $panel = 'panel-default';
                $mes = null;
                $reload = null;
                $demo = "hide";
                $load = __('Загрузить');
                $icon = 'glyphicon-plus';
            }

            if ($row['type'] == 'new')
                $new = ' <span class="label label-primary">new</span>';
            else
                $new = null;
            
            $img_list_default .= '<div class="col-md-4"><div class="panel ' . $panel . '"><div class="panel-heading">' . $row['name'] . $new . $mes . '</div><div class="panel-body text-center"><img class="image-shadow image-skin"  src="https://mini.s-shot.ru/1024x1024/400/png/?https://myphpshop.ru/?skin=' . $row['name'].'&demo&r=1&base=fashion"></div>
                
           <div class="text-center panel-footer">
                    
                        <div class="btn-group" role="group" aria-label="...">
                        <a class="btn btn-sm btn-primary ' . $demo . '" data-toggle="tooltip" data-placement="top" title="' . __('Настроить') . '" href="?path=' . $_GET['path'] . '&name=' . $row['name'] . '"><span class="glyphicon glyphicon-cog"></span> ' . __('Настроить') . '</a>
                            
                        <a class="btn btn-sm btn-default skin-load ' . $reload . ' " data-path="' . $row['name'] . '" data-type="default" data-toggle="tooltip" data-placement="top" title="' . $load . '"><span class="glyphicon ' . $icon . '"></span> </a>
                              
                        <a class="btn btn-sm btn-default ' . $demo . '" data-toggle="tooltip" data-placement="top" title="' . __('Посмотреть демо') . '" href="../../?skin=' . $row['name'] . '" target="_blank"><span class="glyphicon glyphicon-eye-open"></span> ' . __('Демо') . '</a>
                            
                        

                        </div>
                     </div>

</div></div>';

            if ($i == 3) {
                $img_list_default .= '</div>';
                $i = 1;
            } else
                $i++;

            $count++;
        }


    if (count($data_pic) % 3 != 0)
        $img_list_default .= '</div>';

    // Персональный дизайн
    $promo = 'Дизайн-бюро <a href="https://www.phpshop.ru/page/portfolio.html" target="_blank">PHPShop.Design</a> делает дизайны только для  PHPShop, а значит, неожиданностей при создании дизайна не произойдет, и  вы получите уникальный профессиональный дизайн в срок, отвечающий всем  требованиям сегодняшнего дня. 
   <p>     
<ol>
        <li>Мы на 100% знаем свою платформу, а это значит, что  Вам не придется переплачивать за часы работы дизайнера, не знакомого с  PHPShop. </li>
        <li>Мы стараемся учитывать всю функциональность PHPShop  еще на первом этапе его создания, и вы получите работающий  интернет-магазин таким, каким Вы его видите на утвержденном Вами макете. </li>
        <li>Большинство доработок, ранее требовавших  вмешательства в код платформы, на новой версии PHPShop,  производятся с помощью "дизайн-хуков", - это значит, что в будущем вы сможете обновляться без потери доработок. </li>
        <li>Мы соблюдаем сроки, и предоставляем гарантии - если  после завершения проекта Вы заметите недочет с нашей стороны  мы  устраним его. </li>
    </ol>
    </p>
    <p>Для заказа персонального дизайна нужно заполнить бриф, в котором вы  формулируете будущий проект, все возникающие вопросы уточнить у наших  консультантов. Cрок создания макета дизайна - 15 рабочих дней.</p>' . '
    <p>
    <a href="https://www.phpshop.ru/calculation/brifdesign/?from=' . $_SERVER['SERVER_NAME'] . '" target="_blank" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-share-alt"></span> Бриф на Персональный дизайн интернет-магазина</a></p>';


    if (!empty($img_list_default)) {

        if (stristr($_SESSION['lang'], "utf"))
            $promo = __($promo);

        //$PHPShopGUI->addTab(array('Персональный дизайн', $promo, true));
        //$PHPShopGUI->setTab(array('Доступные шаблоны', $title_default . $img_list_default, true));
        $PHPShopGUI->_CODE = $title_default . $img_list_default;
    } else
        $disp = $PHPShopGUI->setAlert('Ошибка связи с сервером ' . $skin_base_path, $type = 'warning');


    return $disp;
}

function getPayLink($amount, $template) {
    global $PHPShopSystem;

    $str = array(
        "url" => getenv('SERVER_NAME'),
        "template" => $template,
        "amount" => number_format($amount, 2, '.', ''),
        "time" => time("U") + (3 * 86400),
        'name' => $PHPShopSystem->getParam('company')
    );

    $str = serialize($str);
    $code = base64_encode($str);
    $code2 = str_replace("O", "!", $code);
    $code2 = str_replace("M", "$", $code2);

    return $code2;
}

?>
