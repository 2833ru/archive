var PCDetect = GetCookie('PCDetect');

function setDelivery(price, delivery_id) {
    document.getElementById('delivery_price').innerHTML = price;
    document.getElementById('d').value = delivery_id;
    if (PCDetect == 1)
        modal_off('#modalDelivery');
}

function setPayment(name, payment_id) {
    document.getElementById('payment_name').innerHTML = name;
    document.getElementById('order_metod').value = payment_id;
    if (PCDetect == 1)
        modal_off('#modalPayment');
}

function setOption(style) {
    var ga = document.createElement('link');
    ga.rel = 'stylesheet';
    ga.async = true;
    ga.href = '/phpshop/templates/mobile/ratchet/css/' + style;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(ga, s);

    setCookie('mobile_skin', style, 10);
}

function go(page) {
    return window.location.replace(page);
}


function modal_on(name) {
    document.getElementById(name.replace("#", "")).className = 'modal.active';
}

function modal_off(name) {
    document.getElementById(name.replace("#", "")).className = 'modal';
}

function setCookie(name, value, days) {
    var today = new Date();
    expires = new Date(today.getTime() + days * 24 * 60 * 60 * 1000);
    document.cookie = name + "=" + escape(value) + "; expires=" + expires.toGMTString();
}

function GetCookie(cookieName) {
    var cookieValue = '';
    var posName = document.cookie.indexOf(escape(cookieName) + '=');
    if (posName != -1) {
        var posValue = posName + (escape(cookieName) + '=').length;
        var endPos = document.cookie.indexOf(';', posValue);
        if (endPos != -1)
            cookieValue = unescape(document.cookie.substring(posValue, endPos));
        else
            cookieValue = unescape(document.cookie.substring(posValue));
    }
    return cookieValue;
}


function tab_on(obj) {

    var content  = document.querySelectorAll('#segmented-content span');
    for (i = content.length; i--; ) {
        content[i].className = 'control-content';
    }
    document.getElementById(obj.hash.replace("#", "")).className = 'control-item active';

    var control = document.querySelectorAll('#segmented-control a');
    for (i = control.length; i--; ) {
        control[i].style.background = "#FFFFFF";
    }
    obj.style.background = "#CCCCCC";
}

function addCartOption(xid) {
    var name = "allOptionsSet" + xid;
    if (document.getElementById(name)) {
        addname = document.getElementById(name).value;
    } else {
        addname = "";
    }
    window.location.replace('/order/?from=html&id='+xid+'&addname=t'+addname);
}