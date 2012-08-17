<?php
function smarty_modifier_unserialize_seminar_info($serialize_date,$isAreaSelect = SMARTY_BOOL_OFF)
{
    if(is_null($serialize_date)) return '';
    global $school_property;//area
    
    require_once('user/school/util.php');
    $util = new schoolUtil();
    $now = time();
    $date = unserialize($serialize_date);



    $html = '';
    $html .= '<dl class="seminar_date_list">'."\n";
    $html .= '<dd class="index_line">'."\n";
    $html .= '<dl>'."\n";
    $html .= '<dd class="location_name">開催場所</dd>'."\n";
    $html .= '<dd class="date_name">開催日時</dd>'."\n";
    $html .= '<dd class="close_name">締切日</dd>'."\n";
    $html .= '</dl>'."\n";
    $html .= '</dd>'."\n";
    //$count = count($date);
    $i = 1;
    foreach ($date as $key => $array){
        $go = FALSE;
        $area = '';
        $close = '-';
        $class = 'close';
        if($array['from'] > $now){
            if($school_property->isAreaSelectSeminar && $school_property->aid){
                if(strcasecmp($school_property->aid,$array['aid']) == 0){
                    if($school_property->said){
                        if(strcasecmp($school_property->said,$array['said']) == 0){
                            //saidで表示する
                            $go = TRUE;
                        }else{
                            $go = TRUE;
                        }
                    }else{
                        //aidで表示する
                        $go = TRUE;
                    }
                }else{
                    //表示しない
                }
            }else{
                //全て表示する
                $go = TRUE;
            }
            if($go){
                if(isset($array['aid']) && isset($array['said'])){
                    $area = $school_property->area_logic->sub_area_info[$array['said']]['col_name'].'/';
                }
                if(isset($array['close']) && $array['close'] != 0){
                    if($array['close'] < $now) $class = 'close_end';
                    $close = $util->makeSingleDate($array['close']);
                }
                $date= $util->makeDateFormat($array['from'],$array['to']);
                $location = $array['location_name'];
                $html .= $i > 1 ? '<dd class="line clearfix">'."\n" : '<dd class="line_top clearfix">'."\n";
                $html .= '<dl>'."\n";
                $html .= '<dd class="location">'.$area.$location.'</dd>'."\n";
                $html .= '<dd class="date">'.$date.'</dd>'."\n";
                $html .= '<dd class="'.$class.'">'.$close.'</dd>'."\n";
                $html .= '</dl>'."\n";
                $html .= '</dd>'."\n";
                $i++;
            }
        }
    }
    $html .= '</dl>'."\n";
    return $html;
}
?>