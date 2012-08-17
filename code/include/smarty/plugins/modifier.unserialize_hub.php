<?php
function smarty_modifier_unserialize_hub($serialize_date,$head = null,$isNow = SMARTY_BOOL_ON)
{
    if(is_null($serialize_date)) return '';
    $date = unserialize($serialize_date);
    $string = '';
    $toPutComma = FALSE;
    $i = 0;
    //$count = count($date);
    $return = array();
    $now = time();
    foreach ($date as $key => $array){
        $bl = FALSE;
        if($isNow == SMARTY_BOOL_ON && $array['from'] > $now){
            $bl = TRUE;
        }elseif($isNow == SMARTY_BOOL_OFF){
            $bl = TRUE;
        }
        if($bl){
            $return[] = $array['hub'];
            $i++;
        }
    }
    $return = array_unique($return);
    $count = count($return);
    if($count == 0) return '';
    //頭出しの処理
    //全て表示
    if(is_null($head)){
        return implode(',',$return);
    //まるめて表示表示
    }elseif($count > $head){
        for ($i=$head;$i<$count;$i++){
            unset($return[$i]);
        }
        return implode(',',$return).'…';
    //表示しきれる場合
    }else{
        return implode(',',$return);
    }
}
?>