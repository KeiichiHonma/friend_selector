<?php
function smarty_modifier_mobile_emoji($string)
{
    global $ca_emoji;
    return $ca_emoji->Convert($string);

}
?>
