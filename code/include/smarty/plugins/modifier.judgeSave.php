<?php
function smarty_modifier_judgeSave($rid,$page = 'list')
{
    global $con;
    $save = $con->getSave();
    $html = '';
    
    //sx sy パラメータを除去
    $array = explode('/sx',$_SERVER['REQUEST_URI']);
    $url = $array !== FALSE ? $array[0] : $_SERVER['REQUEST_URI'];
    
    switch ($page){
        case 'list':
            $img = '/img/common/bt_mylist';
            if($save !== FALSE && count($save) > 0 && in_array($rid,$save)) return '<img src="'.$img.'_act.gif" width="93" height="27" alt="" />';

            $html .= '<form action="'.$url.'" name="save'.$rid.'" method="get">';
            $html .= '<input type="image" src="'.$img.'.gif" alt="" id="saveBtn'.$rid.'" onclick="getScroll(save'.$rid.','.$rid.');" class="btn" /></form>';
            return $html;
        break;
        case 'view-top':
            $img = '/img/common/bt_add_mylist';
            if($save !== FALSE && count($save) > 0 && in_array($rid,$save)) return '<img src="'.$img.'_act.jpg" width="240" height="39" alt="" />';

            $html .= '<form action="'.$url.'" name="save'.$rid.'" method="get">';
            $html .= '<input type="image" src="'.$img.'.jpg" alt="" id="saveBtn'.$rid.'" onclick="getScroll(save'.$rid.','.$rid.');" class="btn" /></form>';
            return $html;
        break;
        case 'view-close':
            $img = '/img/common/bt_add_mylist_m';
            if($save !== FALSE && count($save) > 0 && in_array($rid,$save)) return '<img src="'.$img.'_act.jpg" width="186" height="35" alt="" />';

            $html .= '<form action="'.$url.'" name="save'.$rid.'" method="get">';
            $html .= '<input type="image" src="'.$img.'.jpg" alt="" id="saveBtn'.$rid.'" onclick="getScroll(save'.$rid.','.$rid.');" class="btn" /></form>';
            return $html;
        break;
    }
}
?>
