
<!-- Окно выбора подтипов товара  -->
<div class="modal fade bs-example-modal-sm" id="parentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-tasks"></span>  Выбор опций</h4>
            </div>
            <div class="modal-body">

                <div class="list-group">
                    <a class="list-group-item active">@productName@</a>
                    @parentList@
                </div>
            </div>
        </div>
    </div>
</div>
 <button class="btn btn-primary" role="button" data-toggle="modal" data-target="#parentModal"><span class="glyphicon glyphicon-tasks"></span> @productSelect@</button>