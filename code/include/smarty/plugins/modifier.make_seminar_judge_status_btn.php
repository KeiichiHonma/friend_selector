<?php
function smarty_modifier_make_seminar_judge_status_btn($seminar,$isString = SMARTY_BOOL_OFF)
{
    $html = '';
/*    if(strcasecmp($seminar['hub_validate'],VALIDATE_DENY) == 0){
        if(strcasecmp($isString,SMARTY_BOOL_ON) == 0) return '<span class="stop">注意</span>';
        return '<td colspan="4" class="btn">現在の状態 - <span class="stop">注意 - 対応した開催校が削除されています。</span></td>';
    }*/
    //準備
    if(strcasecmp($seminar['seminar_validate'],VALIDATE_DENY) == 0){
        if(strcasecmp($isString,SMARTY_BOOL_ON) == 0) return '<span class="stop">準備中</span>';
        //有効な日時があるということ
        //if(!is_null($seminar['col_from']) && $seminar['col_from'] >= time() && !is_null($seminar['col_date_serialize'])){
        if(!is_null($seminar['col_date_last_from']) && $seminar['col_date_last_from'] >= time()){
            $html .= '<td colspan="4" class="btn">現在の状態 - <span class="stop">準備</span><br />';
            global $con;
            $html .= '<form id="seminarStatusForm" name="seminarStatusForm" action= "'.CAURLSSL.'/system/school/seminar/validate/scsid/'.$seminar['school_seminar_id'].'" method="post" style="display:inline;margin-top:0em; margin-bottom:0em">';
            $html .= '<input type="hidden" name="csrf_ticket" value="'.$con->session->get( 'csrf_ticket' ).'" />';
            $html .= '<input type="image" src="/img/school/b_keisai.gif" value="submit" class="btn" onClick="return form_submit(\'seminarStatusForm\')" />';
            $html .= '<input type="hidden" name="validate" value="'.VALIDATE_ALLOW.'" />';
            $html .= '<input type="hidden" name="operation" value="" />';
            $html .= '</form></td>';
        }elseif(is_null($seminar['col_date_last_from'])){
            if(strcasecmp($isString,SMARTY_BOOL_ON) == 0) return '<span class="stop">準備中</span>';
            $html .= '<td colspan="4" class="btn">現在の状態 - <span class="stop">準備</span><br />※セミナー・イベントを掲載するには、有効な開催日時が必要です。</td>';
        }else{
            if(strcasecmp($isString,SMARTY_BOOL_ON) == 0) return '<span class="stop">準備中</span>';
            $html .= '<td colspan="4" class="btn">現在の状態 - <span class="stop">準備</span><br />※セミナー・イベントを掲載するには、有効な開催日時が必要です。</td>';
        }
    //掲載中
    }elseif(strcasecmp($seminar['seminar_validate'],VALIDATE_ALLOW) == 0){
        //if(!is_null($seminar['col_from']) && $seminar['col_from'] >= time()){
        if(!is_null($seminar['col_date_last_from']) && $seminar['col_date_last_from'] >= time()){
            if(strcasecmp($isString,SMARTY_BOOL_ON) == 0) return '<span class="display">掲載中</span>';
            $html .= '<td colspan="4" class="btn">現在の状態 - <span class="display">掲載</span><br />';
            global $con;
            $html .= '<form id="seminarStatusForm" name="seminarStatusForm" action= "'.CAURLSSL.'/system/school/seminar/validate/scsid/'.$seminar['school_seminar_id'].'" method="post" style="display:inline;margin-top:0em; margin-bottom:0em">';
            $html .= '<input type="hidden" name="csrf_ticket" value="'.$con->session->get( 'csrf_ticket' ).'" />';
            $html .= '<input type="image" src="/img/school/b_teishi.gif" value="submit" class="btn" onClick="return form_submit(\'seminarStatusForm\')" />';
            $html .= '<input type="hidden" name="validate" value="'.VALIDATE_DENY.'" />';
            $html .= '<input type="hidden" name="operation" value="" />';
            $html .= '</form></td>';
        }elseif(is_null($seminar['col_date_last_from'])){
            if(strcasecmp($isString,SMARTY_BOOL_ON) == 0) return '<span class="stop">準備中</span>';
            $html .= '<td colspan="4" class="btn">現在の状態 - <span class="stop">準備</span><br />※セミナー・イベントを掲載するには、開催日時の追加を実施して下さい。</td>';
        }else{
            //掲載状態で有効な日時がないということは、終了状態
            if(strcasecmp($isString,SMARTY_BOOL_ON) == 0) return '<span class="stop">終了</span>';
            $html .= '<td colspan="4" class="btn">現在の状態 - <span class="stop">終了</span><br />※本セミナー・イベントは終了している状態です。</td>';
        }
    }
    return $html;
}
?>
