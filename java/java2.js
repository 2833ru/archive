/**
 * Поддержка JavaScript функций
 * @package PHPShopJavaScript
 * @author PHPShop Software
 * @version 1.5
 */

var ROOT_PATH="";

// Динамическое меню горизонтальной навигации
function JtopMenuOn(id){
    document.getElementById("menu_"+id).style.display='block';
    var pattern=/menu/;

    for(wi=0;wi<document.all.length;wi++)
        if(pattern.test(document.all[wi].id)==false) a=1;
        else if(document.all[wi].id != "menu_"+id) document.all[wi].style.display='none';

    setTimeout("JtopMenuOff("+id+")",10000);
}
function JtopMenuOff(id){
    document.getElementById("menu_"+id).style.display='none';
}



// Вывод фильтров в поиске
function proSerch(category) {
    var req = new Subsys_JsHttpRequest_Js();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.responseJS) {
                document.getElementById('sort').innerHTML = (req.responseJS.sort||'');
            }
        }
    }
    req.caching = false;
    // Подготваливаем объект.
    // Реальное размещение
    var dir=dirPath();
    req.open('POST', dir+'/phpshop/search.php', true);
    req.send({
        category: category
    });
}


// Прорисовка календаря
function calres(year,month) {
    var req = new Subsys_JsHttpRequest_Js();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.responseJS) {
                document.getElementById('calres').innerHTML = (req.responseJS.calres||'');
            }
        }
    }
    req.caching = false;
    // Подготваливаем объект.
    // Реальное размещение
    var dir=dirPath();
    req.open('POST', dir+'/phpshop/calres.php', true);
    req.send({
        year: year,
        month: month
    });
}

	
// Проверка формы связи
function CheckOpenMessage(){
    var tema = document.getElementById("tema").value;
    var name = document.getElementById("name").value;
    var content = document.getElementById("content").value;
    if(tema=="" || name=="" || content=="") alert("Ошибка заполения формы сообщения!\nДанные, отмеченные флажками обязательны для заполнения.");
    else document.forma_message.submit();
}


// Проверка формы пожаловаться на цену
function CheckPricemail(){
    var mail = document.getElementById("mail").value;
    var name = document.getElementById("name").value;
    var links = document.getElementById("links").value;
    var key = document.getElementById("key").value;
    if(mail=="" || name=="" || links=="" || key=="") alert("Ошибка заполения формы сообщения!\nДанные, отмеченные флажками обязательны для заполнения.");
    else forma_pricemail.submit();
}

function LoadPath(my_path){
    ROOT_PATH = my_path;
}

function dirPath(){
    return ROOT_PATH;
}

// Активная кнопка
function ButOn(Id){
    Id.className='imgOn';
}

function ButOff(Id){
    Id.className='imgOff';
}


// Обновить картинку
function CapReload(){
    var dd=new Date();
    document.getElementById("captcha").src="../phpshop/captcha.php?time="+dd.getTime();
}

// Смайлики
function emoticon(text) {
    var txtarea = document.getElementById("message");
    if (txtarea.createTextRange && txtarea.caretPos) {
        var caretPos = txtarea.caretPos;
        caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? caretPos.text + text + ' ' : caretPos.text + text;
        txtarea.focus();
    } else {
        txtarea.value  += text;
        txtarea.focus();
    }
}


// Подсчет лимита символов
function countSymb(lim) {
    var lim = lim || 500;
    if (document.getElementById("message").value.length > lim) {
        alert("К сожалению, вы превысили максимально допустимую длину комментария");
        document.getElementById("message").value = document.getElementById("message").value.substring(0,lim);
        return false;
    }
    if (document.getElementById("message").value.length > (lim - 50)) {
        document.getElementById("count").style.color = "red";
    }
    if (document.getElementById("message").value.length < (lim - 50)) {
        document.getElementById("count").style.color = "green";
    }
    document.getElementById("count").innerHTML = document.getElementById("message").value.length;
}


// Комменты v1.0
function commentList(xid,comand,page,cid) {
    var message="";

    if(comand == "add") {
        message = document.getElementById('message').value;
        alert ("Комменетарий будет доступен после прохождения модерации...");
    }

    if(comand == "edit_add"){
        message = document.getElementById('message').value;
        cid = document.getElementById('commentEditId').value;
        document.getElementById('commentButtonAdd').style.visibility = 'visible';
        document.getElementById('commentButtonEdit').style.visibility = 'hidden';
    }

    if(comand == "dell"){
        if(confirm("Вы действительно хотите удалить комментарий?")){
            cid = document.getElementById('commentEditId').value;
            document.getElementById('commentButtonAdd').style.visibility = 'visible';
            document.getElementById('commentButtonEdit').style.visibility = 'hidden';
        }
        else cid=0;
    }


    var req = new Subsys_JsHttpRequest_Js();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.responseJS) {
				
                if(comand == "edit"){
                    document.getElementById('message').value = (req.responseJS.comment||'');
                    document.getElementById('commentButtonAdd').style.visibility = 'hidden';
                    document.getElementById('commentButtonEdit').style.visibility = 'visible';
                    document.getElementById('commentButtonEdit').style.display = '';
                    document.getElementById('commentEditId').value=cid;

                }
                else
                {
                    document.getElementById('message').value = "";
                    if(req.responseJS.status == "error") alert("Возможность добавления комментария возможна только для авторизованных пользователей.\nАвторизуйтесь или пройдите регистрацию.");
                    document.getElementById('commentList').innerHTML = (req.responseJS.comment||'');
                }
            }
        }
    }
    req.caching = false;
    // Подготваливаем объект.
    // Реальное размещение
    var dir=dirPath();
    req.open('POST', dir+'/phpshop/comment.php', true);
    req.send({
        xid: xid,
        comand: comand,
        page: page,
        message: message,
        cid: cid
    });
}


// Изображения v2.1
function fotoload(xid,fid) {
    document.getElementById('fotoload').innerHTML = document.getElementById('fotoload').innerHTML;
    var req = new Subsys_JsHttpRequest_Js();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.responseJS) {
                document.getElementById('fotoload').innerHTML = (req.responseJS.foto||'');
            }
        }
    }
    req.caching = false;
    // Подготваливаем объект.
    // Реальное размещение
    var dir=dirPath();
    req.open('POST', dir+'/phpshop/fotoload.php', true);
    req.send({
        xid: xid,
        fid: fid
    });
}



// Просчет доставки
function UpdateDelivery(xid) {
    var req = new Subsys_JsHttpRequest_Js();
    var sum = document.getElementById('OrderSumma').value;
    var wsum = document.getElementById('WeightSumma').innerHTML;
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.responseJS) {
                document.getElementById('DosSumma').innerHTML = (req.responseJS.delivery||'');
                document.getElementById('d').value = xid;
                document.getElementById('TotalSumma').innerHTML = (req.responseJS.total||'');
                document.getElementById('seldelivery').innerHTML = (req.responseJS.dellist||'');
            }
        }
    }
    req.caching = false;
    // Подготваливаем объект.
    // Реальное размещение
    var dir=dirPath();
		
    req.open('POST', dir+'/phpshop/delivery.php', true);
    req.send({
        xid: xid,
        sum: sum,
        wsum: wsum
    });
}




// Очистка корзины
function cartClean(){
    if(confirm("Вы действительно хотите очистить корзину?"))  window.location.replace('./?cart=clean');
}



// Удаление заявки
function NoticeDel(id){
    if(confirm("Вы действительно хотите удалить заявку?"))
        window.location.replace('./notice.html?noticeId='+id);
}


function NoFoto(obj,pathTemplate){
    obj.src=pathTemplate +'/images/shop/no_photo.gif';
}


function NoFoto2(obj){
    obj.height=0;
    obj.width=0;
}


function EditFoto(obj,max_width){
/*
var w,h,pr,max_height;
w=Number(obj.width);
if(w > max_width) obj.width = max_width;
*/
}


// Вывод полной формы
function GetAllForma(catId){
    if(catId!="") window.location.replace("../shop/CID_"+catId+".html");
}


// Сортировка прайса
function DoPriceSort(){
    var catId=document.getElementById("catId").value;
    location.replace("../price/CAT_SORT_"+catId+".html");
}


// Активация закладок
function NavActive(nav){
    if(document.getElementById(nav)){
        var IdStyle = document.getElementById(nav);
        IdStyle.className='menu_bg';
    }
}


// Проверка формы восстанволения пароля
function ChekUserSendForma(){
    var d=document.userpas_forma;
    var login=d.login.value;
    if(login=="") alert("Ошибка заполнения формы восстанволения пароля");
    else d.submit();
}

// Проверка регистрации нового пользователя
function CheckNewUserForma(){
    var d=document.users_data;
    var login=d.login_new.value;
    var password=d.password_new.value;
    var password2=d.password_new2.value;
    var name=d.name_new.value;
    var mail=d.mail_new.value;
    var tel=d.tel_new.value;
    var adres=d.adres_new.value;

    if(name=="" || mail=="" || login=="" || password=="" || password!=password2)
        alert("Ошибка заполнения формы регистрации пользователя");
    else d.submit();
}

// Выход
function UserLogOut(){
    if(confirm("Вы действительно хотите выйти из личного кабинета?"))
        window.location.replace('?LogOut');
}


// Проверка смены пароля
function DispPasDiv(){
    if(document.getElementById("password_chek").checked) document.getElementById("password").style.display='block';
    else document.getElementById("password").style.display='none';
}

// Проверка изменения паролей пользователей
function UpdateUserPassword(){
    var d=document.users_password;
    var login=d.login_new.value;
    var password=d.password_new.value;
    var password2=d.password_new2.value;

    if(login=="" || password=="" || password!=password2){
        alert("Ошибка заполнения формы для изменения доступа");
        document.getElementById("password").style.display='block';
        document.getElementById("password_chek").checked="true";
    }
    else d.submit();
}

// Проверка изменения данных пользователей
function UpdateUserForma(){
    var d=document.users_data;
    var name=d.name_new.value;
    var mail=d.mail_new.value;

    if(name=="" || mail=="")
        alert("Ошибка заполнения формы для изменения данных");
    else d.submit();
}


// Проверка формы авторизации
function ChekUserForma(){
    var login=document.user_forma.login.value;
    var password=document.user_forma.password.value;
    if(login!="" || password!="")
        document.user_forma.submit();
    else alert("Ошибка заполнения формы авторизации");
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


// Изменение кол-ва в поле
function ChangeNumProduct(pole,znak){

    var num=Number (document.getElementById(pole).value);
    if(znak=="+")document.getElementById(pole).value=(num+1);
    if(znak=="-" && num!=1)document.getElementById(pole).value=(num-1);
}

// Смена валюты
function ChangeValuta(){
    document.ValutaForm.submit();
}

// Смена скина
function ChangeSkin(){
    document.SkinForm.submit();
}



// PHPShop CartAdder v 1.2
function ToCart(xid,num,xxid) {
    var req = new Subsys_JsHttpRequest_Js();
    var same= 0;
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.responseJS) {
                initialize();
                setTimeout("initialize_off()",3000);
                document.getElementById('num').innerHTML = (req.responseJS.num||'');
                document.getElementById('sum').innerHTML = (req.responseJS.sum||'');
                same=(req.responseJS.same||'');
                if (same==1) {
                    alert("Этот товар добавлялся ранее с другой характеристикой. Количество товара в корзине увеличено и характеристика обновлена на последний вариант!");
                }
            }
        }
    }
    req.caching = false;
    var truePath=dirPath();

    var name="allOptionsSet"+xxid;
    if(document.getElementById(name)) {
        addname=document.getElementById(name).value;
    } else {
        addname="";
    }


    req.open('POST', truePath+'/phpshop/cartload.php', true);
    req.send({
        xid: xid,
        num: num,
        addname: addname,
        same: same,
        test:303
    });
}
	
function AddToCart(xid) {
    var num=1;
    var xxid=xid;
    if(confirm("Добавить выбранный товар ("+num+" шт.) в корзину?")){
        ToCart(xid,num,xxid);
        if(document.getElementById("order")) document.getElementById("order").style.display='block';
    }
}	
		
// Если есть поле с кол-вом товара
function AddToCartNum(xid,pole) {
    var num=Number(document.getElementById(pole).value);
    var xxid=xid;
    if(num<1) num=1;
    if(confirm("Добавить выбранный товар ("+num+" шт.) в корзину?")){
        ToCart(xid,num,xxid);
        if(document.getElementById("order")) document.getElementById("order").style.display='block';
    }
}
	
// Если есть подчиненные товары OPTION
function AddToCartParent(xxid) {
    var num=1;
    var xid=document.getElementById("parentId").value;
    if(confirm("Добавить выбранный товар ("+num+" шт.) в корзину?")){
        ToCart(xid,num,xxid);
        initialize();
        setTimeout("initialize_off()",3000);
        if(document.getElementById("order")) document.getElementById("order").style.display='block';
    }
}	


// Добавить в сравнение
function AddToCompare(xid) {
    var num=1;
    var same=0;
    if(confirm("Добавить выбранный товар в таблицу сравнения?")){

        var req = new Subsys_JsHttpRequest_Js();
        req.onreadystatechange = function() {
            if (req.readyState == 4) {
                if (req.responseJS) {
                    // Записываем в <div> результат работы.
                    same=(req.responseJS.same||'');

                    if (same==0) {
                        initialize2();
                        setTimeout("initialize_off2()",3000);
                    } else {
                        alert("Товар уже есть в таблице сравнения!");
                    }


                    document.getElementById('numcompare').innerHTML = (req.responseJS.num||'');
				
                }
            }
        }
        req.caching = false;
        // Подготваливаем объект.
        var truePath=dirPath();
        req.open('POST', truePath+'/phpshop/compare.php', true);
        req.send({
            xid: xid,
            num: num,
            same:same
        });
        if(document.getElementById("compare")) document.getElementById("compare").style.display='block';
    }
}	
	


// PhpshopButton v1.0
function butt_on(subm){//ON
    var MENU = document.all[subm].style;
    MENU.background = '8BB911';
}
function butt_of(subm){//OF
    var MENU = document.all[subm].style;
    MENU.background = '999999';
}

function ReturnSortUrl(v){ // Генерим урл
    var s,url="";
    if(v>0){
        s=document.getElementById(v).value;
        if(s!="") url="v["+v+"]="+s+"&";
    }
    return url;
}


// Сортировка по фильтрам
function GetSortAll(){
    var url=ROOT_PATH+"/shop/CID_"+arguments[0]+".html?";
    var i=1;
    var c=arguments.length;
    for(i=1; i<c; i++)
        if(document.getElementById(arguments[i])) url=url+ReturnSortUrl(arguments[i]);
    location.replace(url);
}


function GetSort(id,sort){// Сортировка 
    var path=location.pathname;
    if(sort!=0) location.replace(path+'?'+id+'='+sort);
    else location.replace(path);
}


// Системная информация
function systemInfo() {
    var req = new Subsys_JsHttpRequest_Js();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.responseJS) {
                Info= (req.responseJS.info||'');
                confirm(Info);
            }
        }
    }
    req.caching = false;
    req.open('POST', '/phpshop/info.php', true);
    req.send({
        test:303
    });
}

	

// PhpGoToAdmin v3.1	
function getKey(e){

    // Реальное размещение
    var dir=dirPath();


    if (e == null) { // ie
        key = event.keyCode;
        var ctrl=event.ctrlKey;
    } else { // mozilla
        key = e.which;
        var ctrl=e.ctrlKey;
    }
    if((key=='123') && ctrl) window.location.replace(dir+'/phpshop/admpanel/');
    if(key=='120') systemInfo();
}

document.onkeydown = getKey; 


// Загрузка установок v1.0
function default_load(copyrigh,protect){
    if(copyrigh=="true") window.status="Powered & Developed by PHPShop.ru";
    if(protect=="true"){
        if (document.layers) {
            document.captureEvents(event.mousedown)
            }
        document.onmousedown=mp;
    }
}

// Загрузка позиции каталога статей
function pressbutt_load_catalog(subm,dir){
    if(!dir) dir='';
    var IMG2=dir+'/images/shop/arr3.gif';
    if(subm!='' && document.getElementById("p"+subm)){
        var SUBMENU = document.getElementById("p"+subm).style;
        SUBMENU.visibility = 'visible';
        SUBMENU.position = 'relative';
        if(document.all['i'+subm]) document.all['i'+subm].src=IMG2;
    }
}


// PHPSHOP JavaListCatalog v 2.0
// Start Load Modul
function pressbutt_load(subm,dir,copyrigh,protect,psubm){
    var path=location.pathname;

    // Работа с классом
    if(document.getElementById("cat"+subm)){
        var IdStyle = document.getElementById("cat"+subm);
        if(IdStyle.className == 'catalog_forma') IdStyle.className='catalog_forma_open';
        else IdStyle.className='catalog_forma';
    }

    // Загрузка установок
    var load=default_load(copyrigh,protect);

    // Убираем форму авторизации
    if(path=="/users/" && document.getElementById("autorization")) document.getElementById("autorization").style.display='none';

    // Убираем форму поиска
    var path=location.pathname;
    if(path=="/search/" && document.getElementById("search")) document.getElementById("search").style.display='none';

    // Убираем форму корзины
    var path=location.pathname;
    if((path=="/order/" || path=="/done/") && document.getElementById("cart")) document.getElementById("cart").style.display='none';

    // Убираем форму заказа
    var path=location.pathname;
    if((path=="/done/" || path=="/done/") && document.getElementById("cart")) document.getElementById("cart").style.display='block';

    // Проверяем каталог статей
    var pattern=/page/;
    if(pattern.test(path)==true){
        var catalog=pressbutt_load_catalog(subm,dir);
    }
    else{
        // Каталог товаров
        if(!dir) dir='';
        var IMG2=dir+'/images/shop/arr3.gif';
        if(subm!=''){
            var SUBMENU = document.getElementById("m"+subm).style;
            SUBMENU.visibility = 'visible';
            SUBMENU.position = 'relative';
            if(document.all['i'+subm]) document.all['i'+subm].src=IMG2;
        }
        if(psubm!=''){
            var PSUBMENU = document.getElementById("m"+psubm).style;
            PSUBMENU.visibility = 'visible';
            PSUBMENU.position = 'relative';
            if(document.all['i'+psubm]) document.all['i'+psubm].src=IMG2;
        }


    }
}

// PHPSHOP JavaListCatalog v1.3
// Main Modul
function pressbutt(subm,num,dir,i,m){


    // Работа с классом
    if(document.getElementById("cat"+subm)){
        var IdStyle = document.getElementById("cat"+subm);
        if(IdStyle.className == 'catalog_forma') IdStyle.className='catalog_forma_open';
        else IdStyle.className='catalog_forma';
    }


    if(!dir) dir='';
    if(!m) m="m";
    if(!i) i="i";
    var SUBMENU = document.all[m+subm].style;
    var IMG=dir+'/images/shop/arr2.gif';
    var IMG2=dir+'/images/shop/arr3.gif';


    if (SUBMENU.visibility=='hidden'){
        SUBMENU.visibility = 'visible';
        SUBMENU.position = 'relative';
        if(document.all[i+subm]) document.all[i+subm].src=IMG2;
    }

    else{
        SUBMENU.visibility = 'hidden';
        SUBMENU.position = 'absolute';
        if(document.all[i+subm]) document.all[i+subm].src=IMG;
    }

    for(j=0;i<num;j++)
        if(j != subm)
            if(document.all[m+j]){
                document.all[m+j].style.visibility = 'hidden';
                document.all[m+j].style.position = 'absolute';
                if(document.all[j+subm]) document.all[i+j].src=IMG;
            }
        }

function CheckMessage(message){
    var message = document.getElementById("message").value;
    if(message=="") alert("Ошибка заполения формы сообщения!");
    else document.forma_message.submit();
}

function NewsChek()
{
    var s1=window.document.forms.forma_news.mail.value;
    if (s1=="" || s1=="E-mail..."){
        alert("Ошибка заполнения формы подписки!");
        return false;
    }
    else
        document.forma_news.submit();
    return true;
}

function SearchChek()
{
    var s1=window.document.forms.forma_search.words.value;
    if (s1==""  || s1=="Я ищу..."){
        alert("Ошибка заполнения формы поиска!");
        return false;
    }
    else document.forma_search.submit();
    return true;
}

function OrderChek()
{
    var s1=window.document.forms.forma_order.mail.value;
    var s2=window.document.forms.forma_order.name_person.value;
    var s3=window.document.forms.forma_order.tel_name.value;
    var s4=window.document.forms.forma_order.adr_name.value;
    if (document.getElementById("makeyourchoise").value=="DONE") {
        bad=0;
    } else {
        bad=1;
    }


    if (s1=="" || s2=="" || s3=="" || s4=="") {
        alert("Ошибка заполнения формы заказа.\nДанные отмеченные флажками заполнять обязательно! ");
    } else if (bad==1) {
        alert("Ошибка заполнения формы заказа.\nВыберите доставку!");
    } else{
        document.forma_order.submit();
    }
}

function Fchek()
{
    var s1=window.document.forms.forma_gbook.name_new.value;
    var s2=window.document.forms.forma_gbook.tema_new.value;
    var s3=window.document.forms.forma_gbook.otsiv_new.value;
    if (s1=="" || s2=="" || s3=="")
        alert("Ошибка заполнения формы отзыва!");
    else
        document.forma_gbook.submit();
}

function Img_on(pic,img){
    document.all[pic].src=img;
}

function Img_of(pic,img){
    document.all[pic].src=img;
}

function cart_load(subm){
    SUBMENU=document.all[subm].style;
    if (SUBMENU.visibility=='hidden')
    {
        SUBMENU.visibility = 'visible';
        SUBMENU.position = 'absolute';
    }
    else
    {
        SUBMENU.visibility = 'hidden';
        SUBMENU.position = 'absolute';
        document.all[pic].src="images/m1.gif";
    }
}

function CL()
{
    window.close();
}
function CLREL()
{
    window.opener.location.reload();
    window.close();
}
function REL(url)
{
    location.href=url;
}
function miniWin(url,w,h)
{
    w=window.open(url,"edit","left=100,top=100,width="+w+",height="+h+",location=0,menubar=0,resizable=1,scrollbars=0,status=0");
    w.focus();
}

function DebugWin(url,name,w,h)
{
    w=window.open(url,name,"left=100,top=100,width="+w+",height="+h+",location=0,menubar=0,resizable=1,scrollbars=0,status=0");
    w.focus();
}

function miniWinFull(url,w,h)
{
    w=window.open(url,"edit","left=100,top=100,width="+w+",height="+h+",location=0,menubar=1,resizable=1,scrollbars=1,status=0");
    w.focus();
}

function miniWinChek(url,w,h)
{
    w=window.open(url,"edit","left=0,top=0,width="+w+",height="+h+",location=0,menubar=0,resizable=1,scrollbars=1,status=0");
    w.focus();
}

function FormaBank(url,w,h)
{
    window.open(url,"_blank","left=400,top=100,width="+w+",height="+h+",location=0,menubar=0,resizable=0,scrollbars=1,status=0");
}
function Order(page)
{
    window.opener.document.location.href=page;
    window.close();
}

function Order2(page)
{
    window.opener.document.location.href=page;
//window.close();
}