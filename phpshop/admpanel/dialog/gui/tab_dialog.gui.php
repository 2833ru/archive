<?php

function tab_dialog() {
    global $PHPShopInterface;

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['dialog']);



    if (empty($_GET['search']) and empty($_GET['uid'])) {
        //$where = array('staffid' => "='1'");
        $limit = array('limit' => 50);
    } elseif (!empty($_GET['search'])) {
        $where = array('staffid' => "='1'", 'name' => " LIKE '%" . PHPShopSecurity::TotalClean($_GET['search']) . "%'");
        $limit = array('limit' => 100);
    } elseif (!empty($_GET['uid'])) {
        $where = array('user_id' => "=" . intval($_GET['uid']));
        $limit = array('limit' => 100);
    }

    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('chat_id,id,message,name,time,bot,user_id,name'), $where, array('group' => 'chat_id order by staffid desc'), $limit);

    if (is_array($data)) {
        $tab = '<ul class="nav nav-pills nav-stacked">';
        foreach ($data as $row) {
            
            $name=$row['name'];

            if ($row['chat_id'] == $_GET['id'])
                $class = 'active';
            else
                $class = null;

            $data_chat = $PHPShopOrm->select(array('chat_id,id,message,name,time,bot,user_id'), array('staffid' => "='1'", 'isview' => "='0'", 'chat_id' => '=' . $row['chat_id']), array('order' => 'id desc'), array('limit' => '50'));
            
            if(is_array($count))
            $count = count($data_chat);
            else $count = 0;

            if (empty($data_chat[0]['staffid'])){
                $row['name'] = $PHPShopOrm->getOne(array('name'), array('staffid' => "='1'", 'chat_id' => '=' . $row['chat_id']))['name'];
                if(empty($row['name']) and $data_chat[0]['bot'] != 'message')
                    continue;
            }

            if (!empty($count))
                $badge = '<span class="badge pull-right" id="badge-' . $row['chat_id'] . '">' . $count . '</span>';
            else
                $badge = null;

            if (!empty($data_chat[0]['message']) and $_GET['id'] != $row['chat_id'])
                $message = '<div style="padding-top:5px"><span class="text-muted">' . substr($data_chat[0]['message'], 0, 20) . '</span><span class="pull-right text-muted">' . PHPShopDate::get($row['time'], false, false, '.') . '</span></div>';
            else
                $message = null;

            $tab .= '<li class="' . $class . '"><a href="?path=dialog&id=' . $row['chat_id'] . '&bot=' . $row['bot'] . '&user=' . $row['user_id'] . '&return=dialog"><img src="../lib/templates/messenger/' . $row['bot'] . '.svg" title="' . ucfirst($row['bot']) . '" class="bot-icon">' . $row['name'] . $badge . $message . '</a></li>';
        }
        $tab .= '</ul>';
    } else
        $tab = $PHPShopInterface->setAlert('Записи отсутствуют.', 'info', true);

    return $tab;
}

?>