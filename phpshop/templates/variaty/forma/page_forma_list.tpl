<form method="post" name="forma_message">
  <table width="100%" cellpadding="5">
    <tr>
      <td colspan="2"><strong  style="font-size:14px; color:#FF0000"> @Error@</strong>
        <p></p></td>
    </tr>
    <tr>
      <td>Тема:&nbsp;&nbsp;&nbsp; </td>
      <td><input type="text" name="tema" id="tema" style="width:300px" value="">
        <img src="images/shop/flag_green.gif" alt="" width="16" height="16"  hspace="5" align="absmiddle"></td>
    </tr>
    <tr>
      <td>Контактное лицо:&nbsp;&nbsp;&nbsp; </td>
      <td><input type="text" name="name" id="name" style="width:300px" value="">
        <img src="images/shop/flag_green.gif" alt="" width="16" height="16"  hspace="5" align="absmiddle"></td>
    </tr>
    <tr>
      <td>E-mail: </td>
      <td><input type="text" name="mail" id="mail" style="width:300px" value=""></td>
    </tr>
    <tr>
      <td>Телефон: </td>
      <td><input type="text" name="tel" id="tel" style="width:300px;" value=""></td>
    </tr>
    <tr>
      <td>Компания: </td>
      <td><input type="text" name="company" id="company" style="width:300px;" value=""></td>
    </tr>
    <tr>
      <td>Сообщение:</td>
      <td><textarea style="width:300px; height:200px;" name="content" id="content"></textarea>
        <img src="images/shop/flag_green.gif" alt="" width="16" height="16" hspace="5" align="absmiddle"> 
       <p class="small"><label><input name="rule" value="1" required="" checked="" type="checkbox"> @rule@</label></p>
      </td>
    </tr>
    <tr>
      <td align="center"></td>
      <td>
           @captcha@
          
      </td>
    </tr>
  </table>
 
  <table>
    
    <tr>
      <td colspan="2"><br>
        <div id=allspec><img src="images/shop/comment.gif" alt="" width="16" height="16"  hspace="5" align="absmiddle">Данные, отмеченные <b>флажками</b> обязательны для заполнения.<br>
        </div></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><div class="btn-group"><input type="reset" value="Очистить" class="btn">
        <input type="hidden" name="send" value="1" >
        <input type="button" value="Отправить сообщение" onclick="CheckOpenMessage()" class="btn btn-primary"></div></td>
    </tr>
  </table>
</form>
