<?php
function smarty_modifier_mobile_tag_a($url,$sign = '?')
{
    global $con;
    $guid = $con->isDocomo ? $sign.'guid=ON' : '';
    return CAURL.$url.$guid;
}
?>
