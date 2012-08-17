<?php
function smarty_modifier_make_square($string,$brLength = 60,$isAutoLink = SMARTY_BOOL_ON)
{
    global $con;
    return $con->base->mb_square($string,$brLength,$isAutoLink);
}
?>
