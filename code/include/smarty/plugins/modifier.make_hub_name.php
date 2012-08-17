<?php
function smarty_modifier_make_hub_name($name,$validate)
{

    if(strcasecmp($validate,VALIDATE_DENY) == 0){
        return '<img src="/img/school/school_off.gif" alt="スクール" width="16" height="16" />(削除済)&nbsp;'.$name;
    }else{
        return '<img src="/img/school/school.gif" alt="スクール" width="16" height="16" />'.$name;
    }
}
?>
