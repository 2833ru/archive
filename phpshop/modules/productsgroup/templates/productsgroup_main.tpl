
        <script src="phpshop/modules/productsgroup/js/productsgroup.js"></script>       
        <div class="productsgroup_list">

                    <table class="table table-striped table-hover table-bordered" cellpadding="0" cellspacing="0">
                        <tr>
                            <th class="text-center" width="5%">Фото</th>
                            <th width="65%">Название товара</th>
                            <th class="text-center" width="20%">Цена</th>
                            <th class="text-center" width="10%">Кол-во</th>
                        </tr>
                        @productsgroup_table_tr@
                    </table>
                    <div class="btn btn-primary basket_put addToCartListGroup" role="button" data-num="1" data-uid-group="@data_uid_group@"><span>@productSale@</span></div>
                    <input type="hidden" class="all_price" name="all_price" value="@all_price@">
                </div>