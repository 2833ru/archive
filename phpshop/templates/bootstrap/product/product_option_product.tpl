<!-- Окно выбора опций товара  -->
<div class="modal fade bs-example-modal-sm" id="optionModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-tasks"></span> Выбор опций</h4>
            </div>
            <div class="modal-body">

                @optionsDisp@
                <div class="modal-footer">
                    <button class="btn btn-primary addToCartListOption" data-uid="@productUid@" data-num="1" data-dismiss="modal">@productSale@</button>
                </div>
            </div>
        </div>
    </div>
</div>
 <button class="btn btn-success @elementCartHide@" data-num="1" role="button" data-toggle="modal" data-target="#optionModal"><span class="glyphicon glyphicon-tasks"></span> @productSelect@</button>