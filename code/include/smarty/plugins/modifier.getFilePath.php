<?php
function smarty_modifier_getFilePath($fid,$ext)
{
    global $con;
    return $con->base->getFilePath($fid,$ext);
}

/* vim: set expandtab: */

?>
