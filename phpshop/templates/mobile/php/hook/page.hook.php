<?php

function ListPage_mob_hook($obj, $dataArray, $rout) {

    if ($rout == 'END') {
        $dis = null;
        if (is_array($dataArray)) {
            foreach ($dataArray as $row)
                $dis.='<li class="table-view-cell">
    <a class="push-right" href="/page/' . $row['link'] . '.html" onclick="go(this.href)">
        <strong>' . $row['name'] . '</strong>
    </a>
</li>';
        }
        $obj->set('pageContent', '<div class="card"><ul class="table-view"><li class="table-view-divider">' . $obj->category_name . '</li>' . $dis . '</ul></div>');
    }
}

function index_mob_hook($obj, $row, $rout) {

    if ($rout == 'END') {
        $obj->set('pageContent', '<div class="card">
    <div class="content-padded">
        <h3>' . $row['name'] . '</h3>
        <p>' . $row['content'] . '</p>
    </div>
</div>');
    }
}

function ListCategory_mob_hook($obj, $dataArray, $rout) {
    if ($rout == 'END') {
        $dis = null;
        if (is_array($dataArray)) {
            foreach ($dataArray as $row)
                $dis.='<li class="table-view-cell">
    <a class="push-right" href="/page/CID_' . $row['id'] . '.html" onclick="go(this.href)">
        <strong>' . $row['name'] . '</strong>
    </a>
</li>';
        }
        $obj->set('pageContent', '<div class="card"><ul class="table-view"><li class="table-view-divider">' . $obj->category_name . '</li>' . $dis . '</ul></div>');
    }
}

$addHandler = array
    (
    'ListPage' => 'ListPage_mob_hook',
    'ListCategory' => 'ListCategory_mob_hook',
    'index' => 'index_mob_hook'
);
?>