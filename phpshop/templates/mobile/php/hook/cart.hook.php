<?php

function ordercartforma_hook($val, $option, $rout) {

    if ($rout == 'START') {
        $PHPShopProduct = new PHPShopProduct($val['id']);
        PHPShopParser::set('cart_image', $PHPShopProduct->getParam('pic_small'));
        PHPShopParser::set('cart_id', $val['id']);
        PHPShopParser::set('cart_xid', $option['xid']);
        PHPShopParser::set('cart_name', $val['name']);
        PHPShopParser::set('cart_num', $val['num']);
        PHPShopParser::set('cart_price', $val['price']);

        // Модальное окно редактирования
        $product_edit = '
            <div id="setProduct' . $val['id'] . '" class="modal">
            <header class="bar bar-nav">
                <a class="icon icon-close pull-right" href="#setProduct' . $val['id'] . '" onclick="modal_off(this.hash)"></a>
                <h1 class="title">Корзина</h1>
            </header>

            <div class="content">
                <div class="content-padded">
                <form name="forma_cart" method="post" id="forma_cart" action="./">
                    <p>
                        <input type="number" name="num_new" required value="' . $val['num'] . '">
                        <input type="hidden" value="' . $option['xid'] . '" name="id_edit">
                        <input type="hidden" name="edit_num" value="edit">
                    </p>
                    <button class="btn btn-positive btn-block"><span class="icon icon-edit"></span> Применить</button>
                </form>
                
<form name="forma_cart_del" method="post" id="forma_cart_del" action="./">
<input type=hidden name="id_delete" value="' . $option['xid'] . '">
<button class="btn btn-negative btn-block"><span class="icon icon-trash"></span> Удалить </button>
</form>


                </div>
            </div>
        </div>';

        PHPShopParser::set('product_edit', $product_edit, true);

        return ParseTemplateReturn('./phpshop/templates/' . $_SESSION['skin'] . '/order/product.tpl', true);
    }
}

$addHandler = array
    (
    'ordercartforma' => 'ordercartforma_hook'
);
?>