/**
 * JS Библиотека панели характеристик товара tab_sorts.gui.php
 */


function addOption (oListbox, text, value, isDefaultSelected, isSelected){
    var oOption = document.createElement("option");
    oOption.appendChild(document.createTextNode(text));
    oOption.setAttribute("value", value);

    if (isDefaultSelected) oOption.defaultSelected = true;
    else if (isSelected) oOption.selected = true;

    oListbox.appendChild(oOption);
}

function enterchar(num){
    var sellist=document.getElementById("list"+num);
    var aoptions=sellist.options;
    var addit=document.getElementById("addval"+num).value;
    selopts=new Array;
    var masi=0;

    for(i=0;i<aoptions.length;i++){
        var cse=aoptions[i].selected;
        if (cse==true) {
            selopts[masi]=aoptions[i].value;
            masi++;
        }
    }
    

    if (addit.length>0) { //Если значение введено, значит работаем
        var req = new Subsys_JsHttpRequest_Js();
        req.onreadystatechange = function() {
            if (req.readyState == 4) {
                if (req.responseJS) {

                    optsres=req.responseJS.interfaces;
                    sellist.options.length = 0;
                    addOption(sellist, "Нет данных", "", false,false);
                    for (i=0;i<optsres.length;i++) {
                        addOption(sellist,optsres[i]['name'] , optsres[i]['id'], false,optsres[i]['selected']);
                    }
                }
            }
        }
        req.caching = false;
        // Подготваливаем объект.
        req.open('POST', 'action_char.php', true);
        req.send( {
            num: num,
            selopts:selopts,
            addit:addit
        } );

    } else {//Нечего вводить!
        alert("Введите значение!");
    }

}

function init() {
      if (arguments.callee.done) return;
      arguments.callee.done = true;
      if (khtmltimer) clearInterval(khtmltimer);
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
      else return;
      t = e.target || window.event.srcElement;
      if (t.getAttribute('class') == 'selected') t.removeAttribute('class');
      else t.setAttribute('class', 'selected');
      for (var i = 0, j = opts.length; i < j; i++) {
        if (opts[i].hasAttribute('class')) opts[i].selected = true;
        else opts[i].selected = false;
      }
    }
         
    if (document.addEventListener) document.addEventListener("DOMContentLoaded", init, false);
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