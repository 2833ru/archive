<?php

$TitlePage = __("Каталоги");
PHPShopObj::loadClass('valuta');
PHPShopObj::loadClass('category');
PHPShopObj::loadClass('sort');
unset($_SESSION['jsort']);

/**
 * Вывод товаров
 */
function actionStart() {
    global $PHPShopInterface, $TitlePage, $PHPShopBase, $PHPShopGUI;


    $PHPShopInterface->sort_action = false;
    $PHPShopInterface->action_button['Добавить каталог'] = array(
        'name' => '',
        'action' => 'addNewCat',
        'class' => 'btn btn-default btn-sm navbar-btn',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-plus',
        'tooltip' => 'data-toggle="tooltip" data-placement="left" title="' . __('Добавить каталог') . '"'
    );

    $PHPShopInterface->setActionPanel($TitlePage, false, array('Добавить каталог'));


    $PHPShopInterface->addJSFiles('./js/jquery.treegrid.js', './catalog/gui/catalog.gui.js', './js/bootstrap-treeview.min.js');
    $PHPShopInterface->addCSSFiles('./css/bootstrap-treeview.min.css');

    // Прогрессбар
    $treebar = '<div class="progress">
  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
    <span class="sr-only">' . __('Загрузка') . '..</span>
  </div>
</div>';

    // Поиск категорий
    $search = '<div class="none" id="category-search" style="padding-bottom:5px;"><div class="input-group input-sm">
                <input type="input" class="form-control input-sm" type="search" id="input-category-search" placeholder="' . __('Искать в категориях...') . '" value="">
                 <span class="input-group-btn">
                  <a class="btn btn-default btn-sm" id="btn-search" type="submit"><span class="glyphicon glyphicon-search"></span></a>
                 </span>
            </div></div>';

    $sidebarleft[] = array('title' => 'Категории', 'content' => $search . '<div id="tree">' . $treebar . '</div>', 'title-icon' => '<div class="hidden-xs"><span class="glyphicon glyphicon-chevron-down" data-toggle="tooltip" data-placement="top" title="' . __('Развернуть все') . '"></span>&nbsp;<span class="glyphicon glyphicon-chevron-up" data-toggle="tooltip" data-placement="top" title="' . __('Свернуть') . '"></span>&nbsp;<span class="glyphicon glyphicon-search" id="show-category-search" data-toggle="tooltip" data-placement="top" title="' . __('Поиск') . '"></span></div>');

    $PHPShopInterface->setSidebarLeft($sidebarleft, 3);


    $PHPShopInterface->_CODE .= '   
    <div class="row">
       <div class="col-md-3 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-eye-open"></span> ' . __('На витрине') . '</div>
             <div class="panel-body text-right panel-intro">
                 <a>' . $PHPShopBase->getNumRows('categories', "where skin_enabled='0'") . '</a>
             </div>
          </div>
       </div>
       <div class="col-md-3 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-eye-close"></span> ' . __('Скрыто') . '</div>
                <div class="panel-body text-right panel-intro">
                 <a>' . $PHPShopBase->getNumRows('categories', "where skin_enabled='1'") . '</a>
               </div>
          </div>
       </div>
        <div class="col-md-3 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-th-list"></span> ' . __('Главное меню') . '</div>
                <div class="panel-body text-right panel-intro">
                <a>' . $PHPShopBase->getNumRows('categories', "where menu='1'") . '</a>
               </div>
          </div>
       </div>
       <div class="col-md-3 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-th-large"></span> ' . __('Плитка на главной') . '</div>
                <div class="panel-body text-right panel-intro">
                 <a>' . $PHPShopBase->getNumRows('categories', "where tile='1'") . '</a>
               </div>
          </div>
       </div>
   </div>';
    
        $PHPShopInterface->_CODE .= '   
    <div class="row">
       <div class="col-md-3 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-thumbs-up"></span> ' . __('Спецпредложения') . '</div>
             <div class="panel-body text-right panel-intro">
                 <a href="?path=catalog&where[spec]=1">' . $PHPShopBase->getNumRows('products', "where spec='1' and parent_enabled='0'") . '</a>
             </div>
          </div>
       </div>
       <div class="col-md-3 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-open"></span> ' . __('Яндекс и Google') . '</div>
                <div class="panel-body text-right panel-intro">
                 <a href="?path=catalog&where[yml]=1">' . $PHPShopBase->getNumRows('products', "where yml='1'") . '</a>
               </div>
          </div>
       </div>
        <div class="col-md-3 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-flash"></span> ' . __('Без цен') . '</div>
                <div class="panel-body text-right panel-intro">
                <a href="?path=catalog&where[price]=0&core=eq">' . $PHPShopBase->getNumRows('products', "where price='0' and parent_enabled='0'") . '</a>
               </div>
          </div>
       </div>
       <div class="col-md-3 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-picture"></span> ' . __('Без картинок') . '</div>
                <div class="panel-body text-right panel-intro">
                 <a href="?path=catalog&where[pic_small]=null&core=eq">' . $PHPShopBase->getNumRows('products', "where pic_small='' and parent_enabled='0'") . '</a>
               </div>
          </div>
       </div>
   </div>';

    $fixVariants = [
        [__('Перенести в Неопределенные товары &rarr; Удаленные'), 1, 1],
        [__('Удалить'), 2, 1]
    ];

    $PHPShopInterface->_CODE .= '<div class="row">';
    $PHPShopInterface->_CODE .= '<div class="col-md-12 text-center">';
    $PHPShopInterface->_CODE .= '<div class="panel panel-default fix-products-block">';
    $PHPShopInterface->_CODE .= '<div class="panel-heading"><span class="glyphicon glyphicon-transfer"></span> ' . __('Товары с удаленными категориями') . '</div>';
    $PHPShopInterface->_CODE .= '<div class="panel-body text-left panel-intro text-center">';
    $PHPShopInterface->_CODE .= $PHPShopGUI->setSelect('fix_products', $fixVariants, 350);
    $PHPShopInterface->_CODE .= $PHPShopGUI->setButton('Перенести', 'ok', 'fix-products');
    $PHPShopInterface->_CODE .= '</div>';
    $PHPShopInterface->_CODE .= '</div>';
    $PHPShopInterface->_CODE .= '</div>';
    $PHPShopInterface->_CODE .= '</div>';

    $PHPShopInterface->Compile(3);
}

function actionDeleteProducts()
{
    $mode = (int) $_REQUEST['mode'];

    $orm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
    $categories = array_column($orm->select(['id', 'parent_to'], false, false, ['limit' => 1000000]), 'parent_to', 'id');

    // Ищем у каких категорий удалена родительская, собираем в массив id таких категорий и всех их подкатегорий
    $childrens = [];
    foreach ($categories as $id => $parentId) {
        if((int) $parentId > 0 && !isset($categories[$parentId])) {
            $category = new PHPShopCategory((int) $id);
            $childrens[] = (int) $id;
            foreach (array_column($category->getChildrenCategories(1000, ['id'], false), 'id') as $child) {
                $childrens[] = (int) $child;
            }
        }
    }

    // Удаляем их с БД и массива категорий
    $orm->delete(['id' => sprintf(' IN (%s)', implode(',', $childrens))]);
    foreach ($childrens as $children) {
        unset($categories[$children]);
    }

    $orm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
    $count = $orm->select(["COUNT('id') as count"], ['category' => sprintf(' NOT IN (%s)', implode(',', array_keys($categories)))]);
    if((int) $count['count'] > 0) {
        if($mode === 1) {
            $orm->update(['category_new' => 1000004], ['category' => sprintf(' NOT IN (%s)', implode(',', array_keys($categories)))]);
        } else {
            $orm->delete(['category' => sprintf(' NOT IN (%s)', implode(',', array_keys($categories)))]);
        }
    }

    return ['success' => 1, 'count' => $count['count']];
}

// Обработка событий
$PHPShopGUI->getAction();
?>