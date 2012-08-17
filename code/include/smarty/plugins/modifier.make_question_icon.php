<?php
function smarty_modifier_make_question_icon($status,$isLarge = SMARTY_BOOL_OFF)
{
    $class = 'question';
    $r = '';
    if(strcasecmp($status,STATUS_FINISH) == 0){
        $r = $class.'_finish';
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
