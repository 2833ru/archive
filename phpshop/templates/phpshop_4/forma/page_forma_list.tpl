<form method="post" name="forma_message">
    <table width="100%" cellpadding="5" class="forma_message">
        <tr>
            <td colspan="2"><strong  style="font-size:14px; color:#FF0000"> @Error@</strong>
                <p></p></td>
        </tr>
        <tr>
            <td width="30%">Тема:&nbsp;&nbsp;&nbsp; </td>
            <td><input type="text" name="tema" id="tema" style="width:290px"  value="@php echo $_REQUEST['tema']; php@">
                <img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"></td>
        </tr>
        <tr>
            <td>Контактное лицо:&nbsp;&nbsp;&nbsp; </td>
            <td><input type="text" name="name" id="name" style="width:290px"  value="@php echo $_REQUEST['name']; php@">
                <img src="images/shop/flag_green.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"></td>
        </tr>
        <tr>
            <td>E-mail: </td>
            <td><input type="text" name="mail" id="mail" style="width:290px"  value="@php echo $_REQUEST['mail']; php@"></td>
        </tr>
        <tr>
            <td>Телефон: </td>
            <td><input type="text" name="tel" id="tel" style="width:290px"  value="@php echo $_REQUEST['tel']; php@"></td>
        </tr>
        <tr>
            <td>Компания: </td>
            <td><input type="text" name="company" id="company" style="width:290px"  value="@php echo $_REQUEST['company']; php@"></td>
        </tr>
        <tr>
            <td>Сообщение:</td>
            <td><textarea style="height:150px; width:290px;" name="content"  id="content1">@php echo $_REQUEST['content']; php@</textarea>
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
            <td colspan="2" align="center"><input type="reset" value="Очистить">
                <input type="hidden" name="send" value="1">
                <input type="button" value="Отправить сообщение" onclick="CheckOpenMessage()"></td>
        </tr>
    </table>
</form>
