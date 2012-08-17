<?php
function smarty_modifier_make_seminar_judge_apply($isApply,$serialize_date)
{
    if($isApply == 1) return FALSE;
    if(is_null($serialize_date)) return FALSE;
    $now = time();
    $date = unserialize($serialize_date);
    //申込OKがあるかのチェック
    foreach ($date as $key => $array){
        if($array['from'] > $now){
            if($array['close'] == 0 || (isset($array['close']) && $array['close'] > $now)) return TRUE;
        }
    }
    return FALSE;
}
?>