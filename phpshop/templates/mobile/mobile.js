function setDelivery(price, delivery_id) {
    document.getElementById('delivery_price').innerHTML = price;
    document.getElementById('d').value = delivery_id;
}

function setPayment(name, payment_id) {
    document.getElementById('payment_name').innerHTML = name;
    document.getElementById('order_metod').value = payment_id;
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
