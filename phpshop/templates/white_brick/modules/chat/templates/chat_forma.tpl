
<div id="chatModalPre">
    <form id="chatform" class="form-inline">
        <input class="input-small" style="padding:4px;width:100px" name="chat_mod_user_name" id="chat_mod_user_name" required="" value="@php echo $_SESSION[mod_chat_user_name]; php@" id="chat_mod_user_name"  type="text" placeholder="Имя">
        <button type="submit" class="btn btn-default btn-sm pull-right" id="chatstart">@php if(empty($_SESSION['mod_chat_user_session'])) echo "Начать"; else echo "Далее"; php@</button>

    </form>   
</div>
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

        $('.breadcrumb, .template-slider').waypoint(function() {
            $('#chatbutton').popover('hide');
        });

        $("body").on("click", "#chatend", function() {
            $('#chatbutton').popover('hide');
            $('#chatModalPre').modal('hide');
            $('#chatend').addClass('hide');
        });


        $("body").on("input", "#chat_mod_user_name", function() {
            $('#chat_mod_user_name_true').val(($(this).val()));
        });

        $('#chatbutton').popover();
        $('#chatbutton').on('show.bs.popover', function() {
            $('#chatend').removeClass('hide');
            $('#chatbutton').attr('data-content', $("#chatModalPre").html());
        });

        $(document).on('submit', '#chatform', function() {

            if ($('#chat_mod_user_name_true').val().length > 0) {
                var url = ('https:' == document.location.protocol ? 'https://' : 'http://') + '@serverName@/phpshop/modules/chat/chat.php?name=' + $('#chat_mod_user_name_true').val();
                $('.chat-modal-content').attr('src', url);
                $('#chatModal').modal('show');
                $('#chatstart').html('Далее');
                $('#chatbutton').popover('hide');
                $('#chatend').addClass('hide');
            }
            return false;
        });

        $('#chatopenwindow').on('click', function() {
            var w = 500;
            var h = 550;
            var url = '//@serverName@phpshop/modules/chat/chat.php?name=' + $('#chat_mod_user_name').val();
            chat = window.open(url, "chat", "dependent=1,left=100,top=20,width=" + w + ",height=" + h + ",location=0,menubar=0,resizable=1,scrollbars=0,status=0,titlebar=0,toolbar=0");
            chat.focus();
            $('#chatModal').modal('hide');
            $('.chat-modal-content').attr('src', null);
        });

    });
</script>

<!-- Модальное окно чата -->
<div class="modal hide fade" id="chatModal">
    <div class="modal-header">
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
