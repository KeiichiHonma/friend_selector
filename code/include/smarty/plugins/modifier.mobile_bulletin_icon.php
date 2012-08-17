<?php
function smarty_modifier_mobile_bulletin_icon($tid,$can_user)
{
/*    global $ca_emoji;
    
    if(strcasecmp($tid,BULLETIN_THEME_DELIVERY) == 0){
        return $ca_emoji->Convert('F8D1');
        //$icon = '模';//模擬面接
    }else{
        return $ca_emoji->Convert('F8EA');
        //$icon = '掲';//掲示板
    }*/
    global $carrier;
    $width = 15;
    $height = 15;
    if($carrier->softbank_vga){
        $width = 30;
        $height = 30;
    }
    
    if(strcasecmp($tid,BULLETIN_THEME_DELIVERY) == 0){
        return '<img src="/img/bulletin/bulletin_delivery.gif" width="'.$width.'" height="'.$width.'" alt="" />';
    }elseif(strcasecmp($can_user,VALIDATE_ALLOW) == 0){
        return '<img src="/img/bulletin/bulletin_teacher.gif" width="'.$width.'" height="'.$width.'" alt="" />';
    }else{
        return '<img src="/img/bulletin/bulletin.gif" width="'.$width.'" height="'.$width.'" alt="" />';
    }
}
?>
