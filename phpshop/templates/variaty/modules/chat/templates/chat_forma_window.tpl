

<div class="modal hide fade" id="chatModalPre" style="width:auto !important;">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title" id="myModalLabel">Начать чат</h3>
    </div>
    <div class="modal-body">
        <form id="chatform">
            <div class="form-group">
                <input type="text" name="chat_mod_user_name" id="chat_mod_user_name" class="form-control input-sm" placeholder="Имя..." value="@php echo $_SESSION[mod_chat_user_name]; php@">
            </div>
            <div >
                <button type="button" class="btn btn-default btn-sm" id="chatend">Закрыть</button> &nbsp;
                <button type="submit" class="btn btn-primary btn-sm pull-right" id="chatstart">@php if(empty($_SESSION['mod_chat_user_session'])) echo "Начать"; else echo "Далее"; php@</button>
            </div>
        </form>
    </div>
</div>
<a href="#" data-toggle="modal" data-target="#chatModalPre" class="btn btn-default"><i class="icon-user"></i> Начать чат</a>
<input type="hidden" name="chat_mod_user_name_true" id="chat_mod_user_name_true" value="@php echo $_SESSION[mod_chat_user_name]; php@">
<script>

    $().ready(function() {

        $('#chatbutton').hover(
                function() {
                    $(this).animate({"left": "0px"}, "slow");
                },
                function() {
                    $(this).animate({"left": "-36px"}, "slow");
                });

        $("body").on("click", "#chatend", function() {
            $('#chatbutton').popover('hide');
            $('#chatModalPre').modal('hide');
        });


        $('.breadcrumb, .template-slider').waypoint(function() {
            $('#chatbutton').popover('hide');
        });

        $("body").on("input", "#chat_mod_user_name", function() {
            $('#chat_mod_user_name_true').val(($(this).val()));
        });

        $('#chatbutton').popover();
        $('#chatbutton').on('show.bs.popover', function() {

            $('#chatbutton').attr('data-content', $("#chatModalPre .modal-body").html());
        });


        $('#chatopenwindow').on('click', function() {
            var w = 500;
            var h = 550;
            var url = '//@serverName@phpshop/modules/chat/chat.php?name=' + $('#chat_mod_user_name').val();
            chat = window.open(url, "chat", "dependent=1,left=100,top=20,width=" + w + ",height=" + h + ",location=0,menubar=0,resizable=1,scrollbars=0,status=0,titlebar=0,toolbar=0");
            chat.focus();
            $('#chatModal').modal('hide');
        });


        $(document).on('submit', '#chatform', function() {

            if ($('#chat_mod_user_name_true').val().length > 0) {
                var url = '//@serverName@/phpshop/modules/chat/chat.php?name=' + $('#chat_mod_user_name_true').val();
                $('.chat-modal-content').attr('src', url);
                $('#chatModal').modal('show');
                $('#chatstart').html('Продолжить');
                $('#chatbutton').popover('hide');
                $('#chatend').addClass('hide');
                $('#chatModalPre').modal('hide');
            }
            return false;
        });


    });
</script>

<!-- Модальное окно чата -->
<div class="modal hide fade" id="chatModal">
    <div class="modal-header">
        <a class="btn btn-default btn-sm pull-left icon-th-large" id="chatopenwindow"  title="Открыть в окне"></a>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Чат онлайн</h3>
    </div>
    <form role="form" method="post" name="user_forma" action="@ShopDir@/oneclick/">
        <div class="modal-body" style="max-height: 500px !important; height:500px;">

            <iframe class="chat-modal-content"></iframe>

        </div>
    </form>
</div>
<!--/ Модальное окно чата -->