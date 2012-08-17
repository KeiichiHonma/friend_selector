<?php
function smarty_modifier_make_bulletin_icon($tid,$can_user,$isLarge = SMARTY_BOOL_OFF)
{
    $class = 'bulletin';
    $r = '';
    if(strcasecmp($tid,BULLETIN_THEME_DELIVERY) == 0){
        $r = $class.'_delivery';
    }elseif(strcasecmp($can_user,VALIDATE_ALLOW) == 0){
        $r = $class.'_teacher';
    }else{
        $r = $class;
    }
    if($isLarge == SMARTY_BOOL_ON){
        return $r.'_l';
    }else{
        return $r;
    }
}
?>
