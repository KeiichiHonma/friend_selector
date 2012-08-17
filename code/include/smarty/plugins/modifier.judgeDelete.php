<?php
function smarty_modifier_judgeDelete($rid,$isTop = 1)
{
    //sx sy パラメータを除去
    $array = explode('/sx',$_SERVER['REQUEST_URI']);
    $url = $array !== FALSE ? $array[0] : $_SERVER['REQUEST_URI'];
    
    $html = '';

    $html .= '<form action="'.FSURL.$url.'" name="delete'.$rid.'" method="get">';
    $html .= '<input type="image" src="/img/common/bt_delete.gif" alt="" id="deleteBtn'.$rid.'" onclick="return getDeleteScroll(delete'.$rid.','.$rid.','.$isTop.');" class="btn" /></form>';
    return $html;
}

?>
