
<div class="breadcrumb">
    <div> <a href="/">Главная</a> <span>»</span> <a href="/gbook/">Отзывы</a> </div>
</div>
<h1>Форма отзыва</h1>
<div align="center" style="padding-bottom:10px;"> <strong  style="font-size:14px; color:#FF0000"> @Error@</strong></div>
<form method="post" name="forma_gbook">
    <table width="100%" cellpadding="5" class="forma_message">
        <tr>
            <td width="30%">Имя </td>
            <td>
                <input type="text" name="name_new" maxlength="45" style=" height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F ">
                <img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"> </td>
        </tr>
        <tr >
            <td> E-mail </td>
            <td><input type="text" name="mail_new" maxlength="45" style=" height:18px; font-family:tahoma; font-size:11px ; color:#4F4F4F ">
            </td>
        </tr>
        <tr>
            <td>Тема сообщения </td>
            <td><textarea style=" height:50px; font-family:tahoma; font-size:11px ; color:#4F4F4F " name="tema_new" maxlength="60" id="content"></textarea>
                <img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"> </td>
        </tr>
        <tr bgcolor="ffffff">
            <td>Отзыв </td>
            <td valign="top"><textarea style=" height:150px; font-family:tahoma; font-size:11px ; color:#4F4F4F " name="otsiv_new" maxlength="300" id="content"></textarea>
                <img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"> </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td  width="30%" align="left"><img src="phpshop/captcha.php" id="captcha" alt="" border="0"><br>
                <a class=b  title="Обновить картинку" href="javascript:CapReload();">Обновить картинку</a></td>
            <td>Введите код, указанный на картинке<br>
                <input type="text" name="key" style="">
                <img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"></td>
        </tr>
        <tr>
            <td colspan="2"><br>
                <div id=allspec><img src="images/shop/comment.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle">Данные, отмеченные <b>флажками</b> обязательны для заполнения.<br>
                </div></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="reset" value="Очистить">
                <input type="hidden" name="send_gb" value="ok" >
                <input type="button" value="Добавить отзыв" onclick="Fchek()">
        </tr>
    </table>

</form>
