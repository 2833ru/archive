<?php
/**
 * Панель сортировок каталога
 * @param array $data массив данных
 * @return string 
 */
function tab_sorts($data) {
    global $SysValue,$link_db;
    
    $sort=unserialize($data['sort']);
    
    $dis = null;
    $sql = "select * from " . $SysValue['base']['sort_categories'] . " where category=0 order by num";
    $result = mysqli_query($link_db,$sql);
    while ($row = mysqli_fetch_array($result)) {
        $id = $row['id'];
        $name = $row['name'];
        $sel = "";
        if (is_array($sort))
            foreach ($sort as $v) {
                if ($id == $v)
                    $sel = "selected";
            }
        $dis.='
	<optgroup label="'.$name.'">
	'. tab_sorts_val($id, $sort) . '
	</optgroup>
	';
    }
    $disp = '<select name=sort_new[] class="selectpicker" data-container=".sidebarcontainer"  data-style="btn btn-default btn-sm" data-width="auto" data-size="auto"  multiple>'.$dis.'</select>';
    return $disp;
}

function tab_sorts_val($n, $sort) {
    global $SysValue,$link_db;
    $dis=null;
    $sql = "select * from " . $SysValue['base']['sort_categories'] . " where category=$n order by num";
    $result = mysqli_query($link_db,$sql);
    while ($row = mysqli_fetch_array($result)) {
        $id = $row['id'];
        $name = substr($row['name'], 0, 35);
        $sel = "";
        if (is_array($sort))
            foreach ($sort as $v) {
                if ($id == $v)
                    $sel = "selected";
            }
        $dis.="<option value=" . $id . " " . $sel . ">" . $name . "</option>\n";
    }
    return $dis;
}

?>