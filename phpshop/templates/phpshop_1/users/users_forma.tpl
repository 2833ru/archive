
<form method="post" name="user_forma">
<table  style="margin-top:8px; margin-left:14px;">
<tr>
    <td class="white" style="padding-left:5px;">логин:</td>
    <td class="white" colspan="2">пароль:</td>

</tr>
<tr>
   <td style="padding-left:5px;"><input type="text" name="login" value="@UserLogin@" style="width:146px; height:23px; border-top:1px solid #cdcdcd;border-left:1px solid #cdcdcd;border-bottom:1px solid #f4f4f4;border-right:1px solid #f4f4f4; "></td>
     <td><input type="password" name="password" value="@UserPassword@" style="width:146px; height:23px; border-top:1px solid #cdcdcd;border-left:1px solid #cdcdcd;border-bottom:1px solid #f4f4f4;border-right:1px solid #f4f4f4; "></td>
    <td> <input type="image" value="" onclick="ChekUserForma()" src="images/enter.jpg" >
    <input type="hidden" value="1" name="user_enter"></td>
</tr>
<tr>
    <td colspan="3" class="white"><table style="margin-top:6px;" border="0" cellspacing="0" cellpadding="0">
  <tr>
    
	 <td><input type="checkbox" value="1" name="safe_users" @UserChecked@></td>
    <td><div style=" background: url(images/remember.gif) bottom left repeat-x ; margin:0px 59px 0px 5px;">запомнить</div></td>
   <td class="white"><a href="/users/register.html" class="white">Регистрация</a><span>|</span><a href="/users/sendpassword.html" class="white">Забыли пароль?</a></td>
  </tr>
</table>


   
	
    </td>
</tr>
</table>
</form>