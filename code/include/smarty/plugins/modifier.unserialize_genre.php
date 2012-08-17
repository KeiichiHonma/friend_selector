<?php
function smarty_modifier_unserialize_genre($setting,$isString = SMARTY_BOOL_OFF)
{

    if(strlen($setting) == 0) return '';
    global $school_property;
    $html = '';
    $unserialize = unserialize($setting);
    $genre = $unserialize['genre'];
    $i = 0;
    foreach ($school_property->genre_logic->genre_info as $gid => $value){
        if($isString == SMARTY_BOOL_OFF){
/*            $class = '';
            if($i == 1){
                $class = 'genre_right';
                $i = 0;
            }else{
                $class = 'genre';
                $i = 1;
            }*/
            if(in_array($gid,$genre)){
                $html .= '<img src="/img/school/genre/genre'.$gid.'.gif" alt="'.$value['col_name'].'" width="88" height="24" />';
            }else{
                $html .= '<img src="/img/school/genre/genre'.$gid.'_off.gif" alt="'.$value['col_name'].'" width="88" height="24" />';
            }
        }else{
            if(in_array($gid,$genre)){
                $html[] = $value['col_name'];
            }
        }
    }
    return is_array($html) ? implode(',',$html) : $html;
}
?>