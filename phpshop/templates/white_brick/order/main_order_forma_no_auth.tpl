<div class="checkout-content" style="display: block;">
    <div class="left">
        <br>
        <span style="color:red">@user_error@</span>
        <span class="required">*</span> <b>E-Mail:</b><br>
        <input type="text" class="req"  name="mail" value="@php echo $_POST['mail']; php@">
        <br>
        <br>
        <span class="required">*</span> <b>���� ���:</b><br>
        <input type="text" class="req"  name="name_new"  value="@php echo $_POST['name_new']; php@">
        <br>
        <br>
    </div> 
    <div class="auth-hint">
        ���� �� - ����� ������������, �� ������ ������� �� �������� �� ��� � ������ ������ �� �����.<br>
        ���� �� �� ������������, �� ������ ��� �� ������ � �������� ���� ����� � ������ ��������.<br>
         <label><input type="checkbox" value="on" name="rule" class="req" checked="checked"> @rule@</label>
    </div>
</div>