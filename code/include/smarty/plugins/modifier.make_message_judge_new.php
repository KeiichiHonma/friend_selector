<?php
function smarty_modifier_make_message_judge_new($date)
{
    $html = '<img src="/img/common/arrow_p.gif" alt="" width="5" height="7" border="0" valign="middle" />&nbsp;';
    $now = time();
    $before_week = $now - 604800;
    if($date > $before_week) $html = '<img src="/img/index/icon_new.gif" width="24" height="11">&nbsp;';
    return $html;
}
?>
