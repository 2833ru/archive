 /*
 ***** 
 * Модуль "СМАРТ-ФИЛЬТР" для PHPShop Enterprise 3.6
 * Copyright © WEB for ALL, 20010-2014 
 * @author "WEB for ALL" (www.web4.su) 
 * @version 1.0
 *****
 */
 /*
 * Функции для модуля "СМАРТ-ФИЛЬТР"
 */ 
	 
	/**
	 * Выбор цвета в виде визуальных элементов (цветных квадратов)
	*/
	function w4a_smart_get_color(id,field,pref){
	 
		var div = document.getElementById('div_'+id);
		var div_input = document.getElementById('input_'+id);
		
		if(div_input.innerHTML == ''){
		
			div.className = 'smart_color active';
				// ACHTUNG!!! 
				// class="w4asmart_color" - Impotant!!! по нему определяем характеристики "ЦВЕТ" через JavaSript
			div_input.innerHTML = '<input type="hidden" name="'+pref+id+'" id="'+pref+id+'" value="'+field+'" class="w4asmart_color">';
			
		}else{
		
			div.className = 'smart_color';
			div_input.innerHTML = '';
			
		}
		
	}
 
	/**
	 * Получение количества товаров в выборке
	*/ 
	function w4a_smart_get_num(pref){
		var req = new Subsys_JsHttpRequest_Js();
		
		var i = 0;
		var c = document.forms.sort2.elements.length;

		var data0 = '';
		var data1 = '';
				
		for(i=0;i<c;i++){
			id = document.forms['sort2'].elements[i].id;
			val = document.getElementById(id).value;
			if(val=='') val='0';
		
			if(document.forms['sort2'].elements[i].checked || 
			document.forms['sort2'].elements[i].className=='w4asmart_color' ||
			document.forms['sort2'].elements[i].id=='ps' ||
			document.forms['sort2'].elements[i].id=='pf' ){

				if(data0){
					data0 = data0+','+id;
				}else{
					data0 = id;
				}
				
				if(data1){
					data1 = data1+','+val;
				}else{
					data1 = val;
				}
				var flag = 'true';	
			}
		
		}

		req.onreadystatechange = function() {
		
			if (req.readyState == 4) {
			
				if (req.responseJS) {				
					document.getElementById('w4asmart_num').innerHTML = (req.responseJS.item0 || '');	
					
					if(flag=='true'){
						w4a_show_smart('w4asmart','show');
					}else{
						setTimeout("w4a_show_smart('w4asmart','none')", 3000);
					}
				}
				
			}
			
		}
		
            req.caching = false;
            // Подготваливаем объект.
            // Реальное размещение
            var dir=dirPath();
		
		req.open('POST',  dir+'/phpshop/modules/w4asmartfilter/ajax/smart.php', true);
		req.send({			
			data0: data0,
			data1: data1,
			pref: pref
		});	
		
	
	}
	/**
	* универсальная функция показа и сокрытия блока
	*/
	function w4a_show_smart(target,action){
		div = document.getElementById(target);
		if(action=='show'){
			if(target=='w4asmart'){
				var top = document.getElementById('mouseY').value - 55;
				div.style.top = top+'px';	
			}
			
			div.style.display = 'block';
		}else{
			div.style.display = 'none';
		}
	}
 	/**
	* функция Показать все/Скрыть у чекбоксов
	*/
	function w4a_show_smart_element(id){
	
		div1 = document.getElementById('link_'+id);
		div2 = document.getElementById('add_el_'+id);
		if(div2.style.display=='none'){
			div1.innerHTML = 'Скрыть';
			div2.style.display='block';
		}else{
			
			div1.innerHTML = 'Показать все';
			div2.style.display='none';
		}
	
	}
	
  	/**
	* формирование строки запроса с GET-параметрами
	*/

 	function w4a_smartFilter(){
										
		var url = ROOT_PATH + "/smart/?";

		var i = 0;
		var c = arguments.length;

		for (i = 0; i < c; i++){
			if (document.getElementById('sf_field_'+arguments[i])){
												
				if(document.getElementById('sf_field_'+arguments[i]).value!=0){
					url = url + 'sf_'+document.getElementById('sf_field_'+arguments[i]).value+'='+arguments[i]+'&';
				}
												
			}
		}
		location.replace(url);
										
	}
 
  	/**
	* определение координат курсора мыши
	*/
	function fixPageXY(e) {
	  if (e.pageX == null && e.clientX != null ) { 
		var html = document.documentElement;
		var body = document.body;

		e.pageX = e.clientX + (html.scrollLeft || body && body.scrollLeft || 0);
		e.pageX -= html.clientLeft || 0;
		
		e.pageY = e.clientY + (html.scrollTop || body && body.scrollTop || 0);
		e.pageY -= html.clientTop || 0;
	  }
	}

	document.onmousemove = function(e) {
	  e = e || window.event;
	  fixPageXY(e);

	  document.getElementById('mouseX').value = e.pageX;
	  document.getElementById('mouseY').value = e.pageY;
	}
  	/*
	* /определение координат курсора мыши
	**/
 
 
 
 
 
 
 
 
 
 
 
 
 