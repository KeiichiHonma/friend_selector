<?php
function smarty_modifier_mobile_encode_kaigyou($string)
{
    global $con;
    return $con->encodeKaigyou($string);
}
?>
