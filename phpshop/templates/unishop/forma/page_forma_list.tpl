
@Error@
<style type="text/css">.order-page-sidebar-user-block{display: block;}</style>
<form role="form" method="post" name="forma_message" class="template-sm form-horizontal registration-area">
    <div class="form-group">
            <label class="" for="exampleInputEmail1"></label>
        <div class="col-sm-6">
            <input placeholder="{Заголовок}" type="text" name="tema" value="@php  echo $_POST[tema]; php@" class="form-control" id="exampleInputEmail1"  required="">
        </div>
    </div>
    <div class="form-group">
            <label class="control-label" for="exampleInputEmail1"></label>
        <div class="col-sm-6">
            <input placeholder="{Имя}" type="text" name="name" value="@php  echo $_POST[name]; php@" class="form-control" id="exampleInputEmail1"  required="">
        </div>
    </div>
    <div class="form-group">
            <label class="control-label" for="exampleInputEmail1"></label>
        <div class="col-sm-6">
            <input placeholder="E-mail" type="email" name="mail" value="@php  echo $_POST[mail]; php@" class="form-control" id="exampleInputEmail1">
        </div>
    </div>
    <div class="form-group">
            <label class="control-label" for="exampleInputEmail1"></label>
        <div class="col-sm-6">
            <input placeholder="{Телефон}" type="text" name="tel" value="@php  echo $_POST[tel]; php@" class="form-control" id="exampleInputEmail1">
        </div>
    </div>
    <div class="form-group">
            <label class="control-label" for="exampleInputEmail1"></label>
        <div class="col-sm-6">
            <input placeholder="{Компания}" type="text" name="company" value="@php  echo $_POST[company]; php@" class="form-control" id="exampleInputEmail1">
        </div>
    </div>
    <div class="form-group">
            <label class="control-label" for="exampleInputEmail1"></label>
        <div class="col-sm-6">
            <textarea placeholder="{Сообщение}" name="content" class="form-control" required="">@php  echo $_POST[content]; php@</textarea>
        </div>
    </div>
    <div class="form-group">
        <div class=""></div>
        <div class="col-sm-6">
            <p class="small"><label><input name="rule" value="1" required="" checked="" type="checkbox"> @rule@</label></p>
        </div>
    </div>
    <div class="form-group">
        <div class=""></div>
        <div class="col-sm-6">
            @captcha@
        </div>
    </div>
    <div class="form-group">
        <div class=""></div>
        <div class="col-sm-6">
            <span class="pull-right">
                <input type="hidden" name="send" value="1">
                <button type="submit" class="btn btn-primary">{Отправить сообщение}</button>
            </span>
        </div>
    </div>

</form>    