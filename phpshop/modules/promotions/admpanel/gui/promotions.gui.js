// A $( document ).ready() block.
$(document).ready(function() {
    code_check = $('#code_check_new').prop('checked');
    if (code_check == false) {
        $("#delivery_method_check_new").addClass("readonly");
        $("#delivery_method_new").addClass("readonly");
        $("#code_tip_new").addClass("readonly");
        //$("#active_check_new").addClass("readonly");
        //$("#active_date_ot_new").addClass("readonly");
        //$("#active_date_do_new").addClass("readonly");
        $("#sum_order_check_new").addClass("readonly");
        $("#sum_order_new").addClass("readonly");

        //Сообщение при заблокрированном чекбоксе
        //$(':checkbox[readonly=readonly]').click(function(){
        //      alert('Данную галочку невозможно установить пока не введен «Код купона» на вкладке - «Условия»');
        //      return false;
        //  });
    }

    $("#code_check_new").on("click", function() {
        code_check = $('#code_check_new').prop('checked');
        if (code_check == false) {
            $("#delivery_method_check_new").addClass("readonly");
            $("#delivery_method_new").addClass("readonly");
            $("#code_tip_new").addClass("readonly");
            //$("#active_check_new").addClass("readonly");
            //$("#active_date_ot_new").addClass("readonly");
            //$("#active_date_do_new").addClass("readonly");
            $("#sum_order_check_new").addClass("readonly");
            $("#sum_order_new").addClass("readonly");
        }
        else {
            $("#delivery_method_check_new").removeClass("readonly");
            $("#delivery_method_new").removeClass("readonly");
            $("#code_tip_new").removeClass("readonly");
            //$("#active_check_new").removeClass("readonly");
            //$("#active_date_ot_new").removeClass("readonly");
            //$("#active_date_do_new").removeClass("readonly");
            $("#sum_order_check_new").removeClass("readonly");
            $("#sum_order_new").removeClass("readonly");

            //Сообщение при заблокрированном чекбоксе
            //$(':checkbox[readonly=readonly]').click(function(){
            //alert('Данную галочку невозможно установить пока не введен «Код купона» на вкладке - «Условия»');
            //return false;
            //});
        }
    });

    //$("#code_check_new").click(function() {

    //});

    //Сообщение при заблокрированном чекбоксе
    $(':checkbox').click(function() {
        if ($(this).attr("class") == 'readonly') {
            alert('Данную галочку невозможно установить пока не введен «Код купона» на вкладке - «Условия»');
            return false;
        }
    });



    $('#selectalloption').click(function() {
        option_check = $('#selectalloption').prop('checked');
        if (option_check == true) {
            $('#categories option').prop('selected', true);
        }
        else {
            $('#categories option').prop('selected', false);
        }
    });
});

function randAa(n) {  // [ 5 ] random big/small letters
    var s = '';
    while (s.length < n)
        s += String.fromCharCode(Math.random() * 127).replace(/\W|\d|_/g, '');
    $('input[name=code_new]').val(s);
}

function init() {
    if (arguments.callee.done)
        return;
    arguments.callee.done = true;
    if (khtmltimer)
        clearInterval(khtmltimer);
    var s = document.getElementsByTagName('select');
    for (var i = 0; i < s.length; i++) {
        if (s[i].hasAttribute('multiple')) {
            s[i].onclick = updateSelect;
        }
    }
}
function updateSelect(e) {
    var opts = this.getElementsByTagName('option'), t, o;
    if (e) {
        e.preventDefault();
        t = e.target;
    }
    else if (window.event) {
        window.event.returnValue = false;
        t = window.event.srcElement;
    }
    else
        return;
    t = e.target || window.event.srcElement;
    if (t.getAttribute('class') == 'selected')
        t.removeAttribute('class');
    else
        t.setAttribute('class', 'selected');
    for (var i = 0, j = opts.length; i < j; i++) {
        if (opts[i].hasAttribute('class'))
            opts[i].selected = true;
        else
            opts[i].selected = false;
    }
}

if (document.addEventListener)
    document.addEventListener("DOMContentLoaded", init, false);
/*@cc_on @*/
/*@if (@_win32)
 document.write("<script id=__ie_onload defer src=javascript:void(0)><\\/script>");
 var script = document.getElementById('__ie_onload');
 script.onreadystatechange = function() {
 if (this.readyState == 'complete') {
 init();
 }
 };
 /*@end @*/
if (/KHTML/i.test(navigator.userAgent)) {
    var khtmltimer = setInterval(function() {
        if (/loaded|complete/.test(document.readyState)) {
            init();
        }
    }, 10);
}
window.onload = init;


