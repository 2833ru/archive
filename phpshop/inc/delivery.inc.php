<?
// Вывод городов доставки
function GetDelivery($deliveryID){
global $SysValue,$_SESSION;


//В зависимости от того откуда пускаем - engine или delivery
if (empty($SysValue['nav'])) { 
	$engineinc=0;
	$pathTemplate='/'.chr(47).$SysValue['dir']['templates'].chr(47).$_SESSION['skin'].chr(47); // путь до шаблона
} else {
	$engineinc=1;
	$pathTemplate='';
}
//В зависимости от того откуда пускаем - engine или delivery

$table=$SysValue['base']['table_name30'];

if($deliveryID>0){//Если прислали идентификатор, то 
	$sql="select * from ".$table." where (enabled='1' and id='".$deliveryID."') order by city";
	$result=mysql_query($sql);
	$row = mysql_fetch_array($result);
	$isfolder=$row['is_folder']; //Является ли идентификатор папкой
	$PID=$row['PID'];
	$sqlvariants="select * from ".$table." where (enabled='1' and PID='".$row['PID']."') order by city";

	if ($isfolder) { //Если прислали папку, то варианты будут потомки папки
		$sqlvariants="select * from ".$table." where (enabled='1' and PID='".$deliveryID."') order by city";
		$PIDpr=$deliveryID; //Начальный предок, для приглашения 
		$stop=0;
	} else { //Если прислали вариант, то варианты будут соседи
		$sqlvariants="select * from ".$table." where (enabled='1' and PID='".$row['PID']."') order by city";
		$PIDpr=$row['PID']; //Начальный предок, для приглашения 
		$stop=1;
	}
} else { //Если не прислали, значит стартовый набор все корневые доставки
	$stop=0;
	$isfolder=1; //Если не прислали ID, значит прислали 0 идентификатор, который является папкой корневых
	$PID=false;
	$deliveryID=0; //Присваиваем нулевой идентификатор, если ничего не прислали
	$sqlvariants="select * from ".$table." where (enabled='1' and PID='0') order by city";
}
$resultvariants=mysql_query($sqlvariants);// Принимаем варианты
$varamount=mysql_num_rows($resultvariants);

//Если указан идентификатор, то возможно стоит вывести приглашение на уровень вверх. 
/////////////Блок формирования навигации на уровни верх
if ($PID!==false) { //Если есть предки, формируем навигацию
	$pred='';
	$ii=0;
	$num=0;
	while ($PIDpr!=0) {//Делаем пока не дойдем до самого верхнего уровня
		$num++;
		//Получаем первого предка
		$sqlpr="select * from ".$SysValue['base']['table_name30']." where (enabled='1' and id='".$PIDpr."') order by city";
		$resultpr=mysql_query($sqlpr);
		$rowpr = mysql_fetch_array($resultpr);

		$PIDpr=$rowpr['PID']; //Меняем идентификатор предка. На уровень выше
		$city=$rowpr['city'];
		$predok=$rowpr['city'].' > '.$predok; //Довесок, который будем дописывать каждому варианту

		//Получаем количество соседей у вышестоящего.
		$sqlprr="select * from ".$SysValue['base']['table_name30']." where (enabled='1' and PID='".$PIDpr."') order by city";
		$resultprr=mysql_query($sqlprr);
		$ii=0;
		while ($rowsos = mysql_fetch_array($resultprr)) {
			$sqlsosed="select * from ".$SysValue['base']['table_name30']." where (enabled='1' and PID='".$rowsos['id']."') order by city";
			$resultsosed=mysql_query($sqlsosed);
			$sosed=mysql_num_rows($resultsosed);
			$sosfolder=$rowsos['is_folder'];
			if ($sosfolder) {
				if ($sosed) {$ii++;}
			} else {
				$ii++;
			}
		}

		//Если ((есть соседи, т.е. на верхнем уровне можно выбрать что-то другое)
		// И (уровень доставки больше первого)), то показываем приглашение перейти на уровень выше
		if (($ii>1) && ($num>0)) { //Показывать кнопку "снять" если больше 1 вариант выбора у верхнего И (либо есть потомки либо уровень доставки больше первого)
			$pred='Выбрано: '.$city.' <A href="javascript:UpdateDelivery('.$PIDpr.')" title="Выбрать другой способ доставки"><img src="../'.$pathTemplate.'/images/shop/icon-activate.gif" alt=""  border="0" align="absmiddle">Снять выбор</A> <BR> '.$pred;
		}
	}
	if (strlen($pred)) {$br='<BR>';} else {$br='';} //если хоть одно приглашение есть, то Добавление переноса строки, 
} //Если есть предки, формируем навигацию
/////////////Блок формирования навигации на уровни верх

/////////////Блок Добавление вариантов доставок
$varamount=0;
$chkdone=0; //По дефолту умолчательная доставка не указана
while($row = mysql_fetch_array($resultvariants)){

     if(!empty($deliveryID)){//Если присылали идентификатор
       if($row['id'] == $deliveryID) {
		$chk="selected";
	} else {
		$chk="";

		if ($isfolder) { //Если присланный идентификатор папка и работает стартовый файл
		       if($row['flag']==1) { //На случай доставки по умолчанию
				$chk="selected";
				$chkdone=$row['id']; //Если выводится умолчательная доставка, то пометить что выбор завершен
			} else {
				$chk="";
			}

		}


	}
     } elseif($engineinc){//Если НЕ присылали идентификатор, но производится стартовый запуск

       if($row['flag']==1) { //На случай доставки по умолчанию
		$chk="selected";
		$chkdone=$row['id']; //Если выводится умолчательная доставка, то пометить что выбор завершен
	} else {
		$chk="";
	}

     } //

	//Получаем количество соседей у вышестоящего.
	$sqlpot="select * from ".$SysValue['base']['table_name30']." where (enabled='1' and PID='".$row['id']."') order by city";
	$resultpot=mysql_query($sqlpot);
	$pot=mysql_num_rows($resultpot);


	$city=$row['city'];
	if ((!$row['is_folder'])||($pot)) {
		@$disp.='<OPTION value='.$row['id'].' '.$chk.'>'.$predok.$city.'</OPTION>';
		$varamount++;
		$curid=	$row['id'];
	}

}
/////////////Блок Добавление вариантов доставок


if ($varamount===0) {
	$makechoise='<OPTION value=0>[Доставка по умолчанию]</OPTION>'; 
	$alldone='<INPUT TYPE="HIDDEN" id="makeyourchoise" VALUE="DONE">';
	$deliveryID=0;
	$curid=$deliveryID;
//	$waytodo='<IMG onload="UpdateDelivery('.$curid.');" SRC="/'.$pathTemplate.'/images/shop/flag_green.gif">';

}elseif ($varamount>1) {
	$makechoise='<OPTION value="'.$deliveryID.'" id="makeyourchoise">Выберите доставку</OPTION>'; 
	$alldone='';
} else {
	$alldone='<INPUT TYPE="HIDDEN" id="makeyourchoise" VALUE="DONE">';
}


if ($varamount==1) {                                                                          
	if (!(($curid==$deliveryID))) $waytodo='<IMG onload="UpdateDelivery('.$curid.');" SRC="../'.$pathTemplate.'/images/shop/flag_green.gif">';
}

if ($stop) {
	$makechoise='';
	$alldone='<INPUT TYPE="HIDDEN" id="makeyourchoise" VALUE="DONE">';
} else {
	if ($chkdone) $waytodo='<IMG onload="UpdateDelivery('.$chkdone.');" SRC="../'.$pathTemplate.'/images/shop/flag_green.gif">';
}



//Обрамляем полученные варианты слоем, селектом и сдаем на вывод
@$disp='<DIV id="seldelivery">'.$pred.$br.$my.'
<SELECT onchange="UpdateDelivery(this.value)" name="dostavka_metod" id="dostavka_metod">
'.$makechoise.'
'.$disp.'
</SELECT>'.$alldone.$waytodo.'</DIV>
';


return $disp;
}

?>