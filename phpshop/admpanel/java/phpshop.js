
function PHPShopJS(){

    this.button_on = function(a){
        this.classStyle(a.id,'buton');
    }

    this.button_off = function(a){
        this.classStyle(a.id,'butoff');
    }

    this.rowshow_on = function(a){
        this.classStyle(a.id,'row_show_on');
    }

    this.rowshow_out = function(a){
        this.classStyle(a.id,'row_show_off');
    }

    this.classStyle = function(a, name){
        document.getElementById(a).className = name;
    }

    this.style = function(a, style){
        document.getElementById(a).style = style;
    }

    this.value = function(a, value){
        document.getElementById(a).value = value;
    }
}

var PHPShopJS = new PHPShopJS();

// Ajax перезагрузка для модулей
function DoReloadMainWindowModule(page,var1,var3){
    
            var req = new Subsys_JsHttpRequest_Js();
            req.onreadystatechange = function() {
                if (req.readyState == 4) {
                    if (req.responseJS) {
                        if(window.opener.document.getElementById('interfaces')){
                            window.opener.document.getElementById('interfaces').innerHTML = (req.responseJS.xid||'');
                            window.close();
                        }

                    }
                }
            }
            req.caching = false;
            req.open('POST', '../../../admpanel/interface/api.php', true);
            req.send({
                xid: 1,
                page: page,
                var1: var1,
                var3: var3
            });
}



function onDelete(message){
    if(confirm(message)){
        document.getElementById("product_edit").action+='?delID=ok';
        document.getElementById("product_edit").submit();
    }
}


function SetCookie(name, value, days){
    var today = new Date();
    expires = new Date(today.getTime() + days*24*60*60*1000);
    document.cookie = name + "=" + escape(value) +"; expires=" + expires.toGMTString();
}

function GetCookie(cookieName){
    var cookieValue = '';
    var posName = document.cookie.indexOf(escape(cookieName) + '=');
    if (posName != -1) {
        var posValue = posName + (escape(cookieName) + '=').length;
        var endPos = document.cookie.indexOf(';', posValue);
        if (endPos != -1) cookieValue = unescape(document.cookie.substring(posValue, endPos));
        else cookieValue = unescape(document.cookie.substring(posValue));
    }
    return cookieValue;
}


// Вывод скриншота
function GetSkinIcon(skin){
    var path="../../templates/"+skin+"/icon/icon.gif";
    document.getElementById("icon").src=path;
}

function GhekReg(){
    if(document.getElementById('mod_id').value>0) document.forms.product_edit.submit();
}

function GetThemeIcon(skin){
    var path="../skins/"+skin+"/icon.gif";
    document.getElementById("theme").src=path;
}

// Обзор картинок
function ReturnPic(id){
    var pic=document.getElementById(id);
    var path='../photo/assetmanager.php?name='+pic.value+'&tip='+id;
    try{
        pic.value=window.showModalDialog(path,window,"dialogWidth:640px;dialogHeight:500px;edge:Raised;center:Yes;help:No;resizable:No;status:No;");
    }
    catch(e){
        miniWin(path,640,500);
    }
}



// Поиск страниц
function SearchPage(){
    var words=document.getElementById('words').value;
    window.frame2.location.replace('catalog/admin_cat_content.php?words='+words);
}

function EditCatalogPages(){
    if(window.frame2.document.getElementById("catal") && (window.frame2.document.getElementById("catal").value>0)){
        var catal=window.frame2.document.getElementById("catal").value;
        if(catal != 1000) miniWin('catalog/adm_catalogID.php?id='+catal,650,630);
    }else alert("Выберете подкаталог для редактирования");

}

function NewCatalogPages(){
    if(window.frame2.document.getElementById("catal") && (window.frame2.document.getElementById("catal").value>0)){
        var catal=window.frame2.document.getElementById("catal").value;
    }else var catal=0;

    miniWin('catalog/adm_catalog_new.php?id='+catal,650,630);
}

function AllPage(){
    window.frame2.location.replace('catalog/admin_cat_content.php?pid=all');
}

function NewPage(){
    if(window.frame2.document.getElementById("catal") && (window.frame2.document.getElementById("catal").value>0)){
        var catal=window.frame2.document.getElementById("catal").value;
    }else var catal=0;

    miniWin('page/adm_pages_new.php?catalogID='+catal,650,630);

}

function NewPhoto() {
    if(window.frame2.document.getElementById("catal") && (window.frame2.document.getElementById("catal").value>0)){
        var catal=window.frame2.document.getElementById("catal").value;
        miniWin('photo/adm_photo_new.php?id='+catal,400,270)
    }else alert("Выберете подкаталог для добавления фото!");
}

function EditCatalogPhoto(){
    if(window.frame2.document.getElementById("catal") && (window.frame2.document.getElementById("catal").value>0)){
        // && (window.frame2.document.getElementById("catal").value>0)
        var catal=window.frame2.document.getElementById("catal").value;
        miniWin('photo/adm_catalogID.php?id='+catal,650,630);
    }else alert("Выберете подкаталог для редактирования");

}

function NewPhotoCatalog(){
    miniWin('photo/adm_catalog_new.php',650,630);
}





// Проверка дефолтных параметров
function rootNote(){
    if(confirm("Вы используете стандартный пароль и логин для входа в панель управления.\nЭто может привести к взлому сайта. Сменить пароль администратора сейчас?"))
        miniWin('users/adm_userID.php?id=1',500,360)
}


// Резиновый экран
function ResizeWin(){
    var clientW=document.body.clientWidth;
    if(document.getElementById("interfacesWin") || document.getElementById("interfacesWin1")){


        // Если новое окно
        if(window.opener){
            if(document.getElementById("interfacesWin"))
                document.getElementById("interfacesWin").style.height=(clientW-425);
            else {
                document.getElementById("interfacesWin1").height=(clientW-470);
                document.getElementById("interfacesWin2").height=(clientW-490);
            }
        }
        // В тоже окно
        else{
            if(document.getElementById("interfacesWin"))
                document.getElementById("interfacesWin").style.height=(clientW-500);
            else {
                document.getElementById("interfacesWin1").height=(clientW-535);
                document.getElementById("interfacesWin2").height=(clientW-555);
            }
        }
    }
}


function mp(e){
    if(document.all){
        if((event.button==2)||(event.button==3)){
            alert('Copyright 2004-2006 \© PHPShop\.ru\. All rights reserved\. ');
            return false
        }
    }
    if(document.layers){
        if(e.which==3){
            alert('Copyright 2005 \© ShopBuilder\.ru\. All rights reserved\. ');
            return false
        }
    }
}
if (document.layers) {
    document.captureEvents(event.mousedown)
}
//document.onmousedown=mp;


function do_err(){
    return true
}
onerror=do_err;
if(window.location.href.substring(0,4)=="file")window.location="about:blank";

function atlpdp1(){
    for(wi=0;wi<document.all.length;wi++){
        if(document.all[wi].style.visibility!='hidden'){
            document.all[wi].style.visibility='hidden';
            document.all[wi].id='atlpdpst'
        }
    }
}
function atlpdp2(){
    for (wi=0;wi<document.all.length;wi++){
        if(document.all[wi].id=='atlpdpst')document.all[wi].style.visibility=''
    }
}
window.onbeforeprint=atlpdp1;
window.onafterprint=atlpdp2;



// Активная кнопка
function ButOn(Id){
    var IdStyle = document.getElementById("but"+Id);
    IdStyle.className='buton'
}

function ButOff(Id){
    var IdStyle = document.getElementById("but"+Id);
    IdStyle.className='butoff'
}


function PromptThis(){
    if(confirm("Внимание!\nДанная операция может привести к потере позиции.\nВы действительно хотите выполнить данную команду?")){
        document.getElementById("productDELETE").value='doIT';
        document.product_edit.submit();
    }
}

function SelectQuerySql(sql){
    return document.getElementById("sql_area").value=sql;
}

function SqlSend2(){
    if(document.getElementById("csv_file").value.length!=0)
        if(confirm("Внимание!\nДанная операция может привести к потере базы.\nВы действительно хотите выполнить данную команду?"))
            document.getElementById("sql_forma2").submit();
}

function SqlSend(){
    if(document.getElementById("sql_area").value.length!=0)
        if(confirm("Внимание!\nДанная операция может привести к потере базы.\nВы действительно хотите выполнить данную команду?"))
            document.getElementById("sql_forma").submit();
        else
            return document.getElementById("sql_area").value='';
}


function AdmCat(pid,w,h)
{
    window.open("adm_catalog_main.php?catalogID="+pid+"","_blank","dependent=1,left=300,top=100,width="+w+",height="+h+",location=0,menubar=0,resizable=0,scrollbars=0,status=0,titlebar=0,toolbar=0");
}

function winApplet(url)
{
    var h=270;
    var w=500;
    window.open(url,"_blank","left=300,top=100,width="+w+",height="+h+",location=0,menubar=0,resizable=0,scrollbars=0,status=0");
}

function pressbutt(subm){
    SUBMENU = document.all[subm].style;
    if (SUBMENU.visibility=='hidden')
    {
        SUBMENU.visibility = 'visible';
        SUBMENU.position = 'relative';
    }
    else
    {
        SUBMENU.visibility = 'hidden';
        SUBMENU.position = 'absolute';
    }
}
function CL()
{
    window.close();
}

function CLREL(tip)
{
    if (tip=="left") window.opener.top.frame1.location.reload();
    if (tip=="right") window.opener.top.frame2.location.reload();
    if (tip=="top") window.opener.location.reload();
    else window.opener.location.reload();
    window.close();
}


/*
function miniWin(url,w,h) {
var win=window.showModelessDialog(url, "","dialogHeight: "+h+"px; dialogWidth: "+w+"px; dialogTop: px; dialogLeft:px; edge: Raised; center: Yes; help: No; resizable: 0; status: 0;scroll: 0");
}
*/

function miniWin(url,w,h)
{

    window.open(url,"_blank","dependent=1,left=100,top=20,width="+w+",height="+h+",location=0,menubar=0,resizable=1,scrollbars=0,status=0,titlebar=0,toolbar=0");
}



function miniWinFull(url,w,h)
{
    window.open(url,"_blank","left=100,top=100,width="+w+",height="+h+",location=0,menubar=0,resizable=1,scrollbars=1,status=0,titlebar=0,toolbar=0");
}

function Ras(w,h)
{
    var s=window.document.data_list.data_news.value;
    var uri="news/news_to_mail.php?data="+s;
    window.open(uri,"_blank","left=100,top=100,width="+w+",height="+h+",location=0,menubar=0,resizable=1,scrollbars=0,status=0");
}


function show_on(a)
{
    document.all[a].className='row_show_on';
}
function show_out(a)
{
    document.all[a].className='row_show_off';
}

function onPreview() {
    var f_url = document.getElementById("f_url");
    var url = f_url.value;
    if (!url) {
        alert("You have to enter an URL first");
        f_url.focus();
        return false;
    }
    window.ipreview.location.replace(url);
    return false;
}

function onCancel() {
    window.close(null);
    return false;
}
