<?php
function smarty_modifier_mobile_img($path,$alt = '',$width = 15,$height = 15)
{
    global $carrier;
    if($carrier->softbank_vga){
        $width = $width *2;
        $height = $height *2;
    }
    return '<img src="'.$path.'" width="'.$width.'" height="'.$height.'" alt="'.$alt.'" />';
}
?>
