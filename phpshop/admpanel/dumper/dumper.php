<?php
/***************************************************************************\
| Sypex Dumper Lite         version 1.0.7                                   |
| (c)2003-2006 zapimir      zapimir@zapimir.net       http://sypex.net/     |
|---------------------------------------------------------------------------|
|     created: 2003.09.02 19:07              modified: 2006.03.01 16:59     |
|---------------------------------------------------------------------------|
| This program is free software; you can redistribute it and/or             |
| modify it under the terms of the GNU General Public License               |
| as published by the Free Software Foundation; either version 2            |
| of the License, or (at your option) any later version.                    |
|                                                                           |
| This program is distributed in the hope that it will be useful,           |
| but WITHOUT ANY WARRANTY; without even the implied warranty of            |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
| GNU General Public License for more details.                              |
|                                                                           |
| You should have received a copy of the GNU General Public License         |
| along with this program; if not, write to the Free Software               |
| Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,USA. |
\***************************************************************************/




// Путь и URL к файлам бекапа
define('PATHDAMP', 'backup/');
define('URL',  'backup/');
// Максимальное время выполнения скрипта в секундах
// 0 - без ограничений
//define('TIME_LIMIT', 600);
// Ограничение размера данных доставаемых за одно обращения к БД (в мегабайтах)
// Нужно для ограничения количества памяти пожираемой сервером при дампе очень объемных таблиц
define('LIMIT', 1);
// mysql сервер
define('DBHOST', 'localhost:3306');
// Базы данных, если сервер не разрешает просматривать список баз данных,
// и ничего не показывается после авторизации. Перечислите названия через запятую
//define('DBNAMES', $dbase);
// Кодировка данных базы данных
define('CHARSET', 'cp1251');
// Включить сохранение настроек и последних действий
// Для отключения установить значение 0
define('SC', 0);
// Глобальная статистика
// Для отключения установить значение 0
define('GS', 0);

// Временная зона
if(function_exists('date_default_timezone_set'))
date_default_timezone_set('Europe/Moscow');

// Дальше ничего редактировать не нужно



header("Expires: Tue, 1 Jul 2003 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");

$timer = array_sum(explode(' ', microtime()));
ob_implicit_flush();
error_reporting(E_ALL);

$auth = 1;
$error = '';

// Подключаемся
require("../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase")or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");



// Языки
$GetSystems=GetSystems();
$option=unserialize($GetSystems['admoption']);
$Lang=$option['lang'];
require("../language/".$Lang."/language.php");


if($SysValue['my']['time_limit_enabled']=="true"){
$is_safe_mode = ini_get('safe_mode') == '1' ? 1 : 0;
if (!$is_safe_mode) set_time_limit(TIME_LIMIT);
}

define('Lang', $Lang);

if (!file_exists(PATHDAMP) && !$is_safe_mode) {
    mkdir(PATHDAMP, 0777) || die("Не удалось создать каталог для бекапа");
}

$SK = new dumper($dbase);
define('C_DEFAULT', 1);
define('C_RESULT', 2);
define('C_ERROR', 3);
mysql_query("/*!40101 SET NAMES '" . CHARSET . "' */") or die("Invalid query: " . mysql_error());
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

switch($action){
	case 'backup':
	if(CheckedRules($UserStatus["sql"],2) == 1){
		$SK->backup();
		}else{ $UserChek->BadUserFormaWindow();
		$SK->main();}
		break;
	case 'restore':
	if(CheckedRules($UserStatus["sql"],2) == 1){
		$SK->restore();
		}else{ $UserChek->BadUserFormaWindow();
		$SK->main();}
		break;
	default:
		$SK->main();
}

mysql_close();

echo "<SCRIPT>document.getElementById('timer').innerHTML = '" . round(array_sum(explode(' ', microtime())) - $timer, 4) . " сек.'</SCRIPT>";

class dumper {
    var $dbase;
	function dumper($dbase) {
	
	    $this->dbase=$dbase;
		if (file_exists(PATHDAMP . "dumper.cfg.php")) {
		    include(PATHDAMP . "dumper.cfg.php");
		}
		else{
			$this->SET['last_action'] = 0;
			$this->SET['last_db_backup'] = '';
			$this->SET['tables'] = '';
			$this->SET['comp_method'] = 2;
			$this->SET['comp_level']  = 7;
			$this->SET['last_db_restore'] = '';
		}
		$this->tabs = 0;
		$this->records = 0;
		$this->size = 0;
		$this->comp = 0;
	}

	function backup() {
		global $_REQUEST;
		if (!isset($_POST)) {$this->main();}
		set_error_handler("SXD_errorHandler");
		$buttons = "<A ID=save HREF='' STYLE='display: none;' title=\"_blank\">Скачать файл</A> &nbsp; <INPUT ID=back TYPE=button  VALUE='Вернуться' DISABLED onClick=\"history.back();\" class=but>
<INPUT TYPE=button VALUE='Закрыть' onClick=\"window.close();\" class=but >
";
		echo tpl_page(tpl_process("Создается резервная копия БД"), $buttons);

		$this->SET['last_action']     = 0;
		$this->SET['last_db_backup']  = isset($_POST['db_backup']) ? $_POST['db_backup'] : '';
		$this->SET['tables_exclude']  = !empty($_POST['tables']) && $_POST['tables']{0} == '^' ? 1 : 0;
		$this->SET['tables']          = isset($_POST['tables']) ? $_POST['tables'] : '';
		$this->SET['comp_method']     = isset($_POST['comp_method']) ? intval($_POST['comp_method']) : 0;
		$this->SET['comp_level']      = isset($_POST['comp_level']) ? intval($_POST['comp_level']) : 0;
		$this->fn_save();

		$this->SET['tables']          = explode(",", $this->SET['tables']);
		if (!empty($_POST['tables'])) {
		    foreach($this->SET['tables'] AS $table){
    			$table = preg_replace("/[^\w*?^]/", "", $table);
				$pattern = array( "/\?/", "/\*/");
				$replace = array( ".", ".*?");
				$tbls[] = preg_replace($pattern, $replace, $table);
    		}
		}
		else{
			$this->SET['tables_exclude'] = 1;
		}

		if ($this->SET['comp_level'] == 0) {
		    $this->SET['comp_method'] = 0;
		}
		$db = $this->SET['last_db_backup'];

		if (!$db) {
			echo tpl_l("ОШИБКА! Не указана база данных!", C_ERROR);
			echo tpl_enableBack();
		    exit;
		}
		echo tpl_l("Подключение к БД `{$db}`.");
		mysql_select_db($db) or trigger_error ("Не удается выбрать базу данных.<BR>" . mysql_error(), E_USER_ERROR);
		$tables = array();
        $result = mysql_query("SHOW TABLES");
		$all = 0;
        while($row = mysql_fetch_array($result)) {
			$status = 0;
			if (!empty($tbls)) {
			    foreach($tbls AS $table){
    				$exclude = preg_match("/^\^/", $table) ? true : false;
    				if (!$exclude) {
    					if (preg_match("/^{$table}$/i", $row[0])) {
    					    $status = 1;
    					}
    					$all = 1;
    				}
    				if ($exclude && preg_match("/{$table}$/i", $row[0])) {
    				    $status = -1;
    				}
    			}
			}
			else {
				$status = 1;
			}
			if ($status >= $all) {
    			$tables[] = $row[0];
    		}
        }

		$tabs = count($tables);
		// Определение размеров таблиц
		$result = mysql_query("SHOW TABLE STATUS");
		$tabinfo = array();
		$tabinfo[0] = 0;
		$info = '';
		while($item = mysql_fetch_assoc($result)){
			if(in_array($item['Name'], $tables)) {
				$item['Rows'] = empty($item['Rows']) ? 0 : $item['Rows'];
				$tabinfo[0] += $item['Rows'];
				$tabinfo[$item['Name']] = $item['Rows'];
				$this->size += $item['Data_length'];
				$tabsize[$item['Name']] = 1 + round(LIMIT * 1048576 / ($item['Avg_row_length'] + 1));
				if($item['Rows']) $info .= "|" . $item['Rows'];
			}
		}
		$show = 10 + $tabinfo[0] / 50;
		$info = $tabinfo[0] . $info;
		
		if($_REQUEST['upload_action'] == "upload_dump")
			$name = "upload_dump";
		else 
			$name = $db . '_' . date("Y-m-d_H-i_").substr(uniqid(md5(date("U"))),0,8);
        $fp = $this->fn_open($name, "w");
		echo tpl_l("Создание файла с резервной копией БД:<BR>\\n  -  {$this->filename}");
		$this->fn_write($fp, "#SKD101|{$db}|{$tabs}|" . date("Y.m.d H:i:s") ."|{$info}\n\n");
		$t=0;
		echo tpl_l(str_repeat("-", 60));
		$result = mysql_query("SET SQL_QUOTE_SHOW_CREATE = 1");
        foreach ($tables AS $table){
        	echo tpl_l("Обработка таблицы `{$table}` [" . fn_int($tabinfo[$table]) . "].");

        	// Создание таблицы
			$result = mysql_query("SHOW CREATE TABLE {$table}");
        	$tab = mysql_fetch_array($result);
			$tab = preg_replace('/(default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP|DEFAULT CHARSET=\w+|COLLATE=\w+|character set \w+|collate \w+)/i', '/*!40101 \\1 */', $tab);
        	$this->fn_write($fp, "DROP TABLE IF EXISTS {$table};\n{$tab[1]};\n\n");
        	// Опредеделяем типы столбцов
            $NumericColumn = array();
            $result = mysql_query("SHOW COLUMNS FROM {$table}");
            $field = 0;
            while($col = mysql_fetch_row($result)) {
            	$NumericColumn[$field++] = preg_match("/^(\w*int|year)/", $col[1]) ? 1 : 0;
            }
			$fields = $field;
            $from = 0;
			$limit = $tabsize[$table];
			$limit2 = round($limit / 3);
			if ($tabinfo[$table] > 0) {
			if ($tabinfo[$table] > $limit2) {
			    echo tpl_s(0, $t / $tabinfo[0]);
			}
			$i = 0;
			$this->fn_write($fp, "INSERT INTO `{$table}` VALUES");
            while(($result = mysql_query("SELECT * FROM {$table} LIMIT {$from}, {$limit}")) && ($total = mysql_num_rows($result))){
            		while($row = mysql_fetch_row($result)) {
                    	$i++;
    					$t++;

						for($k = 0; $k < $fields; $k++){
                    		if ($NumericColumn[$k])
                    		    $row[$k] = isset($row[$k]) ? $row[$k] : "NULL";
                    		else
                    			$row[$k] = isset($row[$k]) ? "'" . mysql_escape_string($row[$k]) . "'" : "NULL";
                    	}

    					$this->fn_write($fp, ($i == 1 ? "" : ",") . "\n(" . implode(", ", $row) . ")");
    					if ($i % $limit2 == 0)
    						echo tpl_s($i / $tabinfo[$table], $t / $tabinfo[0]);
               		}
					mysql_free_result($result);
					if ($total < $limit) {
					    break;
					}
    				$from += $limit;
            }

			$this->fn_write($fp, ";\n\n");
    		echo tpl_s(1, $t / $tabinfo[0]);}
		}
		$this->tabs = $tabs;
		$this->records = $tabinfo[0];
		$this->comp = $this->SET['comp_method'] * 10 + $this->SET['comp_level'];
        echo tpl_s(1, 1);
        echo tpl_l(str_repeat("-", 60));
        $this->fn_close($fp);
		echo tpl_l("Резервная копия БД `{$db}` создана.", C_RESULT);
		echo tpl_l("Размер БД:       " . round($this->size / 1048576, 2) . " МБ", C_RESULT);
		$filesize = round(filesize(PATHDAMP . $this->filename) / 1048576, 2) . " МБ";
		echo tpl_l("Размер файла: {$filesize}", C_RESULT);
		echo tpl_l("Таблиц обработано: {$tabs}", C_RESULT);
		echo tpl_l("Строк обработано:   " . fn_int($tabinfo[0]), C_RESULT);
		echo "<SCRIPT>with (document.getElementById('save')) {style.display = ''; innerHTML = 'Скачать файл ({$filesize})'; href = 'backup/download.php?backup=". $this->filename . "'; target='_blank'; }document.getElementById('back').disabled = 0;</SCRIPT>";
		

	}

	function restore(){
		if (!isset($_POST)) {$this->main();}
		set_error_handler("SXD_errorHandler");
		$buttons = "<INPUT ID=back TYPE=button VALUE='Вернуться' DISABLED onClick=\"history.back();\" class=but>
<INPUT TYPE=button VALUE='Закрыть' onClick=\"window.close();\" class=but>";
		echo tpl_page(tpl_process("Восстановление БД из резервной копии"), $buttons);

		$this->SET['last_action']     = 1;
		$this->SET['last_db_restore'] = isset($_POST['db_restore']) ? $_POST['db_restore'] : '';
		$file						  = isset($_POST['file']) ? $_POST['file'] : '';
		$this->fn_save();
		$db = $this->SET['last_db_restore'];

		if (!$db) {
			echo tpl_l("ОШИБКА! Не указана база данных!", C_ERROR);
			echo tpl_enableBack();
		    exit;
		}
		echo tpl_l("Подключение к БД `{$db}`.");
		mysql_select_db($db) or trigger_error ("Не удается выбрать базу данных.<BR>" . mysql_error(), E_USER_ERROR);

		preg_match("/^(\d+)\.(\d+)\.(\d+)/", mysql_get_server_info(), $m);
		$this->mysql_version = sprintf("%d%02d%02d", $m[1], $m[2], $m[3]);
		// Определение формата файла
		if(preg_match("/^(.+?)\.sql(\.(bz2|gz))?$/", $file, $matches)) {
			if (isset($matches[3]) && $matches[3] == 'bz2') {
			    $this->SET['comp_method'] = 2;
			}
			elseif (isset($matches[2]) &&$matches[3] == 'gz'){
				$this->SET['comp_method'] = 1;
			}
			else{
				$this->SET['comp_method'] = 0;
			}
			$this->SET['comp_level'] = '';
			if (!file_exists(PATHDAMP . "/{$file}")) {
    		    echo tpl_l("ОШИБКА! Файл не найден!", C_ERROR);
				echo tpl_enableBack();
    		    exit;
    		}
			echo tpl_l("Чтение файла `{$file}`.");
			$file = $matches[1];
		}
		else{
			echo tpl_l("ОШИБКА! Не выбран файл!", C_ERROR);
			echo tpl_enableBack();
		    exit;
		}
		echo tpl_l(str_repeat("-", 60));
		$fp = $this->fn_open($file, "r");
		$this->file_cache = $sql = $table = $insert = '';
        $is_skd = $query_len = $execute = $q =$t = $i = $aff_rows = 0;
		$limit = 300;
        $index = 4;
		$tabs = 0;
		$cache = '';
		$info = array();
		while(($str = $this->fn_read_str($fp)) !== false){
			if (empty($str) || preg_match("/^(#|--)/", $str)) {
				if (!$is_skd && preg_match("/^#SKD101\|/", $str)) {
				    $info = explode("|", $str);
					echo tpl_s(0, $t / $info[4]);
					$is_skd = 1;
				}
        	    continue;
        	}
			$query_len += strlen($str);

			if (!$insert && preg_match("/^(INSERT INTO `?([^` ]+)`? .*?VALUES)(.*)$/i", $str, $m)) {
				if ($table != $m[2]) {
				    $table = $m[2];
					$tabs++;
					echo tpl_l("Таблица `{$table}`.");
					$i = 0;
					if ($is_skd)
					    echo tpl_s(100 , $t / $info[4]);
				}
        	    $insert = $m[1] . ' ';
				$sql .= $m[3];
				$index++;
				$info[$index] = isset($info[$index]) ? $info[$index] : 0;
				$limit = round($info[$index] / 20);
				$limit = $limit < 300 ? 300 : $limit;
				if ($info[$index] > $limit){
					echo $cache;
					$cache = '';
					echo tpl_s(0 / $info[$index], $t / $info[4]);
				}
        	}
			else{
        		$sql .= $str;
				if ($insert) {
				    $i++;
    				$t++;
    				if ($is_skd && $info[$index] > $limit && $t % $limit == 0){
    					echo tpl_s($i / $info[$index], $t / $info[4]);
    				}
				}
        	}

			if (!$insert && preg_match("/^CREATE TABLE (IF NOT EXISTS )?`?([^` ]+)`?/i", $str, $m) && $table != $m[2]){
				$table = $m[2];
				$insert = '';
				$tabs++;
				$cache .= tpl_l("Таблица `{$table}`.");
				$i = 0;
			}
			if ($sql) {
			    if (preg_match("/;$/", $str)) {
            		$sql = rtrim($insert . $sql, ";");
					if (empty($insert)) {
						if ($this->mysql_version < 40101) {
				    		$sql = preg_replace("/ENGINE\s?=/", "TYPE=", $sql);
						}
					}
            		$insert = '';
            	    $execute = 1;
            	}
            	if ($query_len >= 65536 && preg_match("/,$/", $str)) {
            		$sql = rtrim($insert . $sql, ",");
            	    $execute = 1;
            	}
    			if ($execute) {
            		$q++;
            		mysql_query($sql) or trigger_error ("Неправильный запрос.<BR>" . mysql_error(), E_USER_ERROR);
					if (preg_match("/^insert/i", $sql)) {
            		    $aff_rows += mysql_affected_rows();
            		}
            		$sql = '';
            		$query_len = 0;
            		$execute = 0;
            	}
			}
		}
		echo $cache;
		echo tpl_s(1 , 1);
		echo tpl_l(str_repeat("-", 60));
		echo tpl_l("БД восстановлена из резервной копии.", C_RESULT);
		if (isset($info[3])) echo tpl_l("Дата создания копии: {$info[3]}", C_RESULT);
		echo tpl_l("Запросов к БД: {$q}", C_RESULT);
		echo tpl_l("Таблиц создано: {$tabs}", C_RESULT);
		echo tpl_l("Строк добавлено: {$aff_rows}", C_RESULT);

		$this->tabs = $tabs;
		$this->records = $aff_rows;
		$this->size = filesize(PATHDAMP . $this->filename);
		$this->comp = $this->SET['comp_method'] * 10 + $this->SET['comp_level'];
		echo "<SCRIPT>document.getElementById('back').disabled = 0;</SCRIPT>";
		// Передача данных для глобальной статистики
		if (GS) echo "<SCRIPT>document.getElementById('GS').src = 'http://sypex.net/gs.php?r={$this->tabs},{$this->records},{$this->size},{$this->comp},107';</SCRIPT>";

		$this->fn_close($fp);
		$_SESSION['base_update'] = "susses";
	}

	function main(){
		$this->comp_levels = array('9' => '9 (максимальная)', '8' => '8', '7' => '7', '6' => '6', '5' => '5 (средняя)', '4' => '4', '3' => '3', '2' => '2', '1' => '1 (минимальная)','0' => 'Без сжатия');

		if (function_exists("gzopen")) {
		    $this->comp_methods[1] = 'GZip';
		}
		$this->comp_methods[0] = 'Без сжатия';
		if (count($this->comp_methods) == 1) {
		    $this->comp_levels = array('0' =>'Без сжатия');
		}

		$dbs = $this->db_select();
		$this->vars['db_backup']    = $this->fn_select($dbs, $this->SET['last_db_backup']);
		$this->vars['db_restore']   = $this->fn_select($dbs, $this->SET['last_db_restore']);
		$this->vars['comp_levels']  = $this->fn_select($this->comp_levels, $this->SET['comp_level']);
		$this->vars['comp_methods'] = $this->fn_select($this->comp_methods, $this->SET['comp_method']);
		$this->vars['tables']       = $this->SET['tables'];
		$this->vars['files']        = $this->fn_select($this->file_select(), '');
		$buttons = "
		
		<INPUT TYPE=submit VALUE=ОК class=but>
		<INPUT TYPE=button VALUE=Отмена onClick=\"window.close()\" class=but name=btnLang>";
		echo tpl_page(tpl_main(), $buttons);
	}

	function db_select(){
	/*
		if (DBNAMES != '') {
			$items = explode(',', trim(DBNAMES));
			foreach($items AS $item){
    			if (mysql_select_db($item)) {
    				$tables = mysql_query("SHOW TABLES");
    				if ($tables) {
    	  			    $tabs = mysql_num_rows($tables);
    	  				$dbs[$item] = "{$item} ({$tabs})";
    	  			}
    			}
			}
		}
		else {
    		$result = mysql_query("SHOW DATABASES");
    		$dbs = array();
    		while($item = mysql_fetch_array($result)){
    			if (mysql_select_db($item[0])) {
    				$tables = mysql_query("SHOW TABLES");
    				if ($tables) {
    	  			    $tabs = mysql_num_rows($tables);
    	  				$dbs[$item[0]] = "{$item[0]} ({$tabs})";
    	  			}
    			}
    		}
		}
		*/
		$dbs[$this->dbase]=$this->dbase;
	    return $dbs;
	}

	function file_select(){
		$files = array('');
		if (is_dir(PATHDAMP) && $handle = opendir(PATHDAMP)) {
            while (false !== ($file = readdir($handle))) {
                if (preg_match("/^.+?\.sql(\.(gz|bz2))?$/", $file)) {
                    $files[$file] = $file;
                }
            }
            closedir($handle);
        }
		return $files;
	}

	function fn_open($name, $mode){
		if ($this->SET['comp_method'] == 2) {
			$this->filename = "{$name}.sql.bz2";
		    return bzopen(PATHDAMP . $this->filename, "{$mode}b{$this->SET['comp_level']}");
		}
		elseif ($this->SET['comp_method'] == 1) {
			$this->filename = "{$name}.sql.gz";
		    return gzopen(PATHDAMP . $this->filename, "{$mode}b{$this->SET['comp_level']}");
		}
		else{
			$this->filename = "{$name}.sql";
			return fopen(PATHDAMP . $this->filename, "{$mode}b");
		}
	}

	function fn_write($fp, $str){
		if ($this->SET['comp_method'] == 2) {
		    bzwrite($fp, $str);
		}
		elseif ($this->SET['comp_method'] == 1) {
		    gzwrite($fp, $str);
		}
		else{
			fwrite($fp, $str);
		}
	}

	function fn_read($fp){
		if ($this->SET['comp_method'] == 2) {
		    return bzread($fp, 4096);
		}
		elseif ($this->SET['comp_method'] == 1) {
		    return gzread($fp, 4096);
		}
		else{
			return fread($fp, 4096);
		}
	}

	function fn_read_str($fp){
		$string = '';
		$this->file_cache = ltrim($this->file_cache);
		$pos = strpos($this->file_cache, "\n", 0);
		if ($pos < 1) {
			while (!$string && ($str = $this->fn_read($fp))){
    			$pos = strpos($str, "\n", 0);
    			if ($pos === false) {
    			    $this->file_cache .= $str;
    			}
    			else{
    				$string = $this->file_cache . substr($str, 0, $pos);
    				$this->file_cache = substr($str, $pos + 1);
    			}
    		}
			if (!$str) {
			    if ($this->file_cache) {
					$string = $this->file_cache;
					$this->file_cache = '';
				    return trim($string);
				}
			    return false;
			}
		}
		else {
  			$string = substr($this->file_cache, 0, $pos);
  			$this->file_cache = substr($this->file_cache, $pos + 1);
		}
		return trim($string);
	}

	function fn_close($fp){
		if ($this->SET['comp_method'] == 2) {
		    bzclose($fp);
		}
		elseif ($this->SET['comp_method'] == 1) {
		    gzclose($fp);
		}
		else{
			fclose($fp);
		}
		@chmod(PATHDAMP . $this->filename, 0666);
		$this->fn_index();
	}

	function fn_select($items, $selected){
		$select = '';
		foreach($items AS $key => $value){
			$select .= $key == $selected ? "<OPTION VALUE='{$key}' SELECTED>{$value}" : "<OPTION VALUE='{$key}'>{$value}";
		}
		return $select;
	}

	function fn_save(){
		if (SC) {
			$ne = !file_exists(PATHDAMP . "dumper.cfg.php");
		    $fp = fopen(PATHDAMP . "dumper.cfg.php", "wb");
        	fwrite($fp, "<?php\n\$this->SET = " . fn_arr2str($this->SET) . "\n?>");
        	fclose($fp);
			if ($ne) @chmod(PATHDAMP . "dumper.cfg.php", 0666);
			$this->fn_index();
		}
	}

	function fn_index(){
		if (!file_exists(PATHDAMP . 'index.html')) {
		    $fh = fopen(PATHDAMP . 'index.html', 'wb');
			fwrite($fh, tpl_backup_index());
			fclose($fh);
			@chmod(PATHDAMP . 'index.html', 0666);
		}
	}
}

function fn_int($num){
	return number_format($num, 0, ',', ' ');
}

function fn_arr2str($array) {
	$str = "array(\n";
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			$str .= "'$key' => " . fn_arr2str($value) . ",\n\n";
		}
		else {
			$str .= "'$key' => '" . str_replace("'", "\'", $value) . "',\n";
		}
	}
	return $str . ")";
}

// Шаблоны

function tpl_page($content = '', $buttons = ''){
global $Lang;
return <<<HTML
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<TITLE>Работа с базой</TITLE>
<META HTTP-EQUIV=Content-Type CONTENT="text/html; charset=windows-1251">
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
<STYLE TYPE="TEXT/CSS">
<!--
body{
	overflow: auto;
}
select, div {
	font: 11px tahoma, verdana, arial;
}
input.text, select {
	width: 100%;
}
fieldset {
	margin-bottom: 10px;
}
-->
</STYLE>
<script type="text/javascript" language="JavaScript1.2" src="../language/{$Lang}/language_windows.js"></script>
<script>
function getInfo(){
var str = new String();
str = "Фильтры\\n\\nВ фильтре таблиц указываются специальные шаблоны по которым отбираются таблицы. В шаблонах можно использовать следующие специальные символы:символ — означает любое количество символов.\\n\\nсимвол * — означает любое количество символов \\nсимвол ? — означает один любой символ \\nсимвол ^ — означает исключение из списка таблицы или таблиц\\n\\nПримеры: \\n\\nib_* - все таблицы начинающиеся с 'ib_' (все таблицы форума invision board) \\nib_*, ^ib_sessions - все таблицы начинающиеся с 'ib_', кроме 'ib_sessions' \\nib_s*s, ^ib_sessions - все таблицы начинающиеся с 'ib_s' и заканчивающиеся буквой 's', кроме 'ib_sessions' \\n^*s - все таблицы, кроме таблиц заканчивающихся буквой 's' \\n^ib_???? - все таблицы, кроме таблиц, которые начинаются с 'ib_' и содержат 4 символа после знака подчеркивания";
confirm(str);
}
</script>

</HEAD>

<BODY bottommargin="0"  topmargin="0" leftmargin="0" rightmargin="0"  onload="DoCheckLang(location.pathname,1)">
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
	<td style="padding:10">
	<b><span name=txtLang id=txtLang>Работа с базой</span></b><br>
	&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите команды БД</span>.
	</td>
	<td align="right">
	<img src="../img/i_databases_med[1].gif" border="0" hspace="10">
	</td>
</tr>
</table>
<br>
<TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=0>
<TR>
<TD style=padding:10>
<TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=0>
<TR>
<TD VALIGN=TOP>
<TABLE WIDTH=100% HEIGHT=100% BORDER=0 CELLSPACING=1 CELLPADDING=0>
<TR>
<FORM NAME=skb METHOD=POST ACTION=dumper.php>
<TD VALIGN=TOP>
{$content}
<TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=2>
<TR>
<TD STYLE='color: #CECECE' ID=timer></TD>
<TD ALIGN=RIGHT>{$buttons}</TD>
</TR>
</TABLE></TD>
</FORM>
</TR>
</TABLE></TD>
</TR>
</TABLE></TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>
</BODY>
</HTML>
HTML;
}

function tpl_main(){
global $SK,$SysValue;

if(@$_REQUEST['upload_action'] == "upload_dump")
return <<<HTML
<FIELDSET onClick="document.skb.action[0].checked = 1;">
<LEGEND>
<INPUT TYPE=radio NAME=action VALUE=backup checked=checked>
<INPUT TYPE=hidden NAME=upload_action VALUE=upload_dump>
<span name=txtLang id=txtLang>Создание резервной копии БД</span>&nbsp;</LEGEND>
<TABLE WIDTH=100% BORDER=0 CELLSPACING=5 CELLPADDING=2>
<TR>
<TD WIDTH=35%><span name=txtLang id=txtLang>БД</span>:</TD>
<TD WIDTH=65%><SELECT NAME=db_backup>
{$SK->vars['db_backup']}
</SELECT></TD>
<input type=hidden NAME=comp_method value=1>	
<input type=hidden NAME=comp_level value=7>	
</TR>
</TABLE>
</FIELDSET>
</SPAN>
<div style="padding-top:30"><hr></div>
<SCRIPT>
document.skb.action[{$SK->SET['last_action']}].checked = 1;
</SCRIPT>

HTML;


elseif(@$_REQUEST['upload_action'] == "upload_backup")

return <<<HTML
<FIELDSET onClick="document.skb.action[1].checked = 1;">
<LEGEND>
<INPUT TYPE=radio NAME=action VALUE=restore checked=checked>
<INPUT TYPE=hidden NAME=upload_action VALUE=upload_backup>
<span name=txtLang id=txtLang>Обновление базы данных </span>&nbsp;</LEGEND>
<TABLE WIDTH=100% BORDER=0 CELLSPACING=5 CELLPADDING=2>
<TR>
<TD><span name=txtLang id=txtLang>БД</span>:</TD>
<TD><SELECT NAME=db_restore>
{$SK->vars['db_restore']}
</SELECT></TD>
</TR>
<TR>
<TD WIDTH=35%><span name=txtLang id=txtLang>Файл</span>:</TD>
<TD WIDTH=65%>
<input type=hidden NAME=file value="base_update.sql.gz">
Выполнить обновление базы данный из файла <strong>base_update.sql.gz</strong>

</TD>
</TR>
</TABLE>
</FIELDSET>
</SPAN>
<div style="padding-top:30"><hr></div>
<SCRIPT>
document.skb.action[{$SK->SET['last_action']}].checked = 1;
</SCRIPT>

HTML;


elseif(@$_REQUEST['upload_action'] == "upload_backup_back")

return <<<HTML
<FIELDSET onClick="document.skb.action[1].checked = 1;">
<LEGEND>
<INPUT TYPE=radio NAME=action VALUE=restore checked=checked>
<INPUT TYPE=hidden NAME=upload_action VALUE=upload_backup>
<span name=txtLang id=txtLang>Обновление базы данных </span>&nbsp;</LEGEND>
<TABLE WIDTH=100% BORDER=0 CELLSPACING=5 CELLPADDING=2>
<TR>
<TD><span name=txtLang id=txtLang>БД</span>:</TD>
<TD><SELECT NAME=db_restore>
{$SK->vars['db_restore']}
</SELECT></TD>
</TR>
<TR>
<TD WIDTH=35%><span name=txtLang id=txtLang>Файл</span>:</TD>
<TD WIDTH=65%>
<input type=hidden NAME=file value="upload_backup.sql.gz">
Выполнить обновление базы данный из файла <strong>upload_backup.sql.gz</strong>

</TD>
</TR>
</TABLE>
</FIELDSET>
</SPAN>
<div style="padding-top:30"><hr></div>
<SCRIPT>
document.skb.action[{$SK->SET['last_action']}].checked = 1;
</SCRIPT>

HTML;

else


return <<<HTML
<FIELDSET onClick="document.skb.action[0].checked = 1;">
<LEGEND>
<INPUT TYPE=radio NAME=action VALUE=backup>
<INPUT TYPE=hidden NAME=upload_action VALUE="">
<span name=txtLang id=txtLang>Создание резервной копии БД</span>&nbsp;</LEGEND>
<TABLE WIDTH=100% BORDER=0 CELLSPACING=5 CELLPADDING=2>
<TR>
<TD WIDTH=35%><span name=txtLang id=txtLang>БД</span>:</TD>
<TD WIDTH=65%><SELECT NAME=db_backup>
{$SK->vars['db_backup']}
</SELECT></TD>
</TR>
<TR>
<TD><span name=txtLang id=txtLang>Фильтр таблиц</span>:<img src="../icon/icon_info.gif" alt="" border="0" onclick="getInfo()" align="absmiddle" hspace="5" style="cursor: pointer;"></TD>
<TD><INPUT NAME=tables TYPE=text CLASS=text VALUE='{$SK->vars['tables']}'></TD>
</TR>
<TR>
<TD><span name=txtLang id=txtLang>Метод сжатия</span>:</TD>
<TD><SELECT NAME=comp_method>
{$SK->vars['comp_methods']}
</SELECT></TD>
</TR>
<TR>
<TD><span name=txtLang id=txtLang>Степень сжатия</span>:</TD>
<TD><SELECT NAME=comp_level>
{$SK->vars['comp_levels']}
</SELECT></TD>
</TR>
</TABLE>
</FIELDSET>
<FIELDSET onClick="document.skb.action[1].checked = 1;">
<LEGEND>
<INPUT TYPE=radio NAME=action VALUE=restore>
<span name=txtLang id=txtLang>Восстановление БД из резервной копии</span>&nbsp;</LEGEND>
<TABLE WIDTH=100% BORDER=0 CELLSPACING=5 CELLPADDING=2>
<TR>
<TD><span name=txtLang id=txtLang>БД</span>:</TD>
<TD><SELECT NAME=db_restore>
{$SK->vars['db_restore']}
</SELECT></TD>
</TR>
<TR>
<TD WIDTH=35%><span name=txtLang id=txtLang>Файл</span>:</TD>
<TD WIDTH=65%><SELECT NAME=file>
{$SK->vars['files']}
</SELECT></TD>
</TR>
</TABLE>
</FIELDSET>
</SPAN>
<div style="padding-top:30"><hr></div>
<SCRIPT>
document.skb.action[{$SK->SET['last_action']}].checked = 1;
</SCRIPT>

HTML;


}

function tpl_process($title){
return <<<HTML
<FIELDSET>
<LEGEND>{$title}&nbsp;</LEGEND>
<TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=2>
<TR><TD COLSPAN=2><DIV ID=logarea STYLE="width: 100%; height: 220px; border: 0px solid #7F9DB9; padding: 3px; overflow: auto;"></DIV></TD></TR>
<TR><TD WIDTH=31%>Статус таблицы:</TD><TD WIDTH=69%><TABLE WIDTH=100% BORDER=1 CELLPADDING=0 CELLSPACING=0>
<TR><TD BGCOLOR=#FFFFFF><TABLE WIDTH=1 BORDER=0 CELLPADDING=0 CELLSPACING=0 BGCOLOR=#5555CC ID=st_tab
STYLE="FILTER: progid:DXImageTransform.Microsoft.Gradient(gradientType=0,startColorStr=#CCCCFF,endColorStr=#5555CC);
border-right: 1px solid #AAAAAA"><TR><TD HEIGHT=12></TD></TR></TABLE></TD></TR></TABLE></TD></TR>
<TR><TD>Общий статус:</TD><TD><TABLE WIDTH=100% BORDER=1 CELLSPACING=0 CELLPADDING=0>
<TR><TD BGCOLOR=#FFFFFF><TABLE WIDTH=1 BORDER=0 CELLPADDING=0 CELLSPACING=0 BGCOLOR=#00AA00 ID=so_tab
STYLE="FILTER: progid:DXImageTransform.Microsoft.Gradient(gradientType=0,startColorStr=#CCFFCC,endColorStr=#00AA00);
border-right: 1px solid #AAAAAA"><TR><TD HEIGHT=12></TD></TR></TABLE></TD>
</TR></TABLE></TD></TR></TABLE>
</FIELDSET>
<div style="padding-top:0px"><hr></div>
<SCRIPT>
var WidthLocked = false;
function s(st, so){
	document.getElementById('st_tab').width = st ? st + '%' : '1';
	document.getElementById('so_tab').width = so ? so + '%' : '1';
}
function l(str, color){
	switch(color){
		case 2: color = 'navy'; break;
		case 3: color = 'red'; break;
		default: color = 'black';
	}
	with(document.getElementById('logarea')){
		if (!WidthLocked){
			style.width = clientWidth;
			WidthLocked = true;
		}
		str = '<FONT COLOR=' + color + '>' + str + '</FONT>';
		innerHTML += innerHTML ? "<BR>\\n" + str : str;
		scrollTop += 14;
	}
}
</SCRIPT>
HTML;
}


function tpl_l($str, $color = C_DEFAULT){
$str = preg_replace("/\s{2}/", " &nbsp;", $str);
return <<<HTML
<SCRIPT>l('{$str}', $color);</SCRIPT>

HTML;
}

function tpl_enableBack(){
return <<<HTML
<SCRIPT>document.getElementById('back').disabled = 0;</SCRIPT>

HTML;
}

function tpl_s($st, $so){
$st = round($st * 100);
$st = $st > 100 ? 100 : $st;
$so = round($so * 100);
$so = $so > 100 ? 100 : $so;
return <<<HTML
<SCRIPT>s({$st},{$so});</SCRIPT>

HTML;
}

function tpl_backup_index(){
return <<<HTML
<CENTER>
<H1>У вас нет прав для просмотра этого каталога</H1>
</CENTER>

HTML;
}

function tpl_error($error){
return <<<HTML
<FIELDSET>
<LEGEND>Ошибка при подключении к БД</LEGEND>
<TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=2>
<TR>
<TD ALIGN=center>{$error}</TD>
</TR>
</TABLE>
</FIELDSET>

HTML;
}

function SXD_errorHandler($errno, $errmsg, $filename, $linenum, $vars) {
	if ($errno == 2048) return true;
	//if (preg_match("/chmod\(\): Operation not permitted/", $errmsg)) return true;
    $dt = date("Y.m.d H:i:s");
    $errmsg = addslashes($errmsg);

	echo tpl_l("{$dt}<BR><B>Возникла ошибка!</B>", C_ERROR);
	echo tpl_l("{$errmsg} ({$errno})", C_ERROR);
	echo tpl_enableBack();
	die();
}
?>