<?php
function smarty_modifier_make_auto_bold($title,$words,$byte = 58)
{
    global $con;
    $r = $con->base->mb_strimbyte($title,$byte,'…');
    require_once('fw/uri.php');
    $uri = new uri();
    return $uri->autobold($r,$words);
}
?>
