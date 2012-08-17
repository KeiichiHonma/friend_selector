<?php
function smarty_modifier_make_seminar_date_judge_status_btn($status,$scsid,$scsdid)
{
    $html = '';
    if(strcasecmp($status,SCHOOL_SEMINAR_DATE_READY) == 0){
        global $con;
        $html .= '<form id="seminarDateStatusForm" name="seminarDateStatusForm" action= "'.CAURLSSL.'/system/school/seminar/date/validate/scsid/'.$scsid.'/scsdid/'.$scsdid.'" method="post" style="display:inline;margin-top:0em; margin-bottom:0em">';
        $html .= '<input type="hidden" name="csrf_ticket" value="'.$con->session->get( 'csrf_ticket' ).'" />';
        $html .= '<input type="image" src="/img/school/b_keisai.gif" value="submit" class="btn" onClick="return form_submit(\'seminarDateStatusForm\')" />';
        $html .= '<input type="hidden" name="validate" value="'.VALIDATE_ALLOW.'" />';
        $html .= '<input type="hidden" name="operation" value="" />';
        $html .= '</form>';
    }elseif(strcasecmp($status,SCHOOL_SEMINAR_DATE_PUBLIC) == 0){
        global $con;
        $html .= '<form id="seminarDateStatusForm" name="seminarDateStatusForm" action= "'.CAURLSSL.'/system/school/seminar/date/validate/scsid/'.$scsid.'/scsdid/'.$scsdid.'" method="post" style="display:inline;margin-top:0em; margin-bottom:0em">';
        $html .= '<input type="hidden" name="csrf_ticket" value="'.$con->session->get( 'csrf_ticket' ).'" />';
        $html .= '<input type="image" src="/img/school/b_teishi.gif" value="submit" class="btn" onClick="return form_submit(\'seminarDateStatusForm\')" />';
        $html .= '<input type="hidden" name="validate" value="'.VALIDATE_DENY.'" />';
        $html .= '<input type="hidden" name="operation" value="" />';
        $html .= '</form>';
    }
    return $html;

}
?>
