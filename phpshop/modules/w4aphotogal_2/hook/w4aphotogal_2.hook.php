<?php

/**
 * Изменение галереии в карточке товара
 */
	function w4a_photogal_2($obj, $data){
		global $SysValue;
			$js='
					<!-- bxSlider Javascript file -->
					<script src="phpshop/modules/w4aphotogal_2/javascript/jquery.bxslider/jquery.bxslider.min.js"></script>
					<!-- bxSlider CSS file -->
					<link href="phpshop/modules/w4aphotogal_2/javascript/jquery.bxslider/jquery.bxslider.css" rel="stylesheet" />
					<script>
					$(document).ready(function(){
					  $(\'.bxslider\').bxSlider({
						slideWidth: 60,
						minSlides: 2,
						maxSlides: 3,
						moveSlides: 1,
						slideMargin: 10,
						pager: false,
						infiniteLoop: false,
						hideControlOnEnd: true,
						nextText: \'\',
						prevText: \'\'
					  });
					});
					</script>
			';
		//print_r($data);
		$sql = 'select id,name from phpshop_foto where parent='.$data['id'].' order by id limit 0,12';
		$result = mysql_query($sql);
		$fotos_small = "";
		$i=1;//счетчик колон в таблице вывода иконок
		while(@$row = mysql_fetch_array($result)){
		
			$pic_url = $row['name'];
			$pic_id = $row['id'];
			
			$pic_url_s = str_replace('.','s.',$pic_url);
			$pic_url_bigger = str_replace('.','_big.',$pic_url);
			
			if($i==1){
				$dis ='
					<div id="pic'.$i.'" style="display:block; height:350px; overflow: hidden;"><a onclick="return hs.expand(this)" title="" class="highslide" href="'.$pic_url_bigger.'" target="_blank" getparams="null"><img alt="" src="'.$pic_url_bigger.'" style="width:220px;"></a></div>
				';
			}else{
				$dis .='
				<div id="pic'.$i.'" style="display:none; height:350px; overflow: hidden;"><a onclick="return hs.expand(this)" title="" class="highslide" href="'.$pic_url_bigger.'" target="_blank" getparams="null"><img alt="" src="'.$pic_url.'" style="width:220px;"></a></div>
				';
			}
			
			$icons .='
						<div class="slide">
							<a class="highslide  " onclick="return hs.expand(this)" href="'.$pic_url_bigger.'" target="_blank" getparams="null">
								<img src="'.$pic_url_s.'" onmouseover="$(\'#pic'.$i.'\').css({\'display\':\'block\'});$(\'#list'.$i.'#\').css({\'display\':\'none\'});" />
							</a>
						</div>


			';
			if($i==1) $list = "#pic$i";
			else $list .= ", #pic$i";
			$i++;
		}
		for($ii=1;$ii<=($i-1);$ii++){
		
			if($ii==1){
				if($i==2)$listing = str_replace('#pic'.$ii,'',$list); // если одна иконка
				else $listing = str_replace('#pic'.$ii.', ','',$list); // если больше одной иконок
			}else $listing = str_replace(', #pic'.$ii,'',$list);
			$icons = str_replace('#list'.$ii.'#',$listing,$icons);
		}
		
		
			$diss='
					<div class="bxbigphoto">
						'.$dis.'
					</div>
					<div class="bxslider">
						'.$icons.'
					</div>
			';	

			$disp = $js.$diss;
		return $disp;
	
	}
 
 
	function image_gallery_hook($obj, $data, $rout) {

			// если jQuery еще не подключалось в шаблоне, то TRUE, иначе FALSE
			$jq_on = true;
	
			$jq_lib = '
				<!-- jQuery library (served from Google) -->
				<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
			';
			
			$dis=w4a_photogal_2($obj, $data);
			if($jq_on==true) $disp=$jq_lib.$dis;
			else $disp=$dis;
			// Переназначем переменную списка категорий
			$obj->set('productFotoList', $disp);

			return true;		


	}

$addHandler = array
    (
    'image_gallery' => 'image_gallery_hook'
);
?>
