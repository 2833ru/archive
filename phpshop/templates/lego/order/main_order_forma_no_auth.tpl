<div class="row auth-form">
    <div class="col-md-5">
        <span style="color:red">@user_error@</span>
        <div class="form-group">
            <input type="email" name="mail" class="form-control req" placeholder="E-mail" required="" value="@php echo $_POST['mail']; php@">
        </div>
        <div class="form-group">
            <input type="text" name="name_new" class="form-control req"  placeholder="{Èìÿ}" required="" value="@php echo $_POST['name_new']; php@">
        </div>
    </div>
    <div class="col-md-7">
    </div>
</div>