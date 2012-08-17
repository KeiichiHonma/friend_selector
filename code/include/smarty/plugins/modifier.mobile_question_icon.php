<?php
function smarty_modifier_mobile_question_icon($status)
{
/*    global $ca_emoji;
    switch ($status){
        case STATUS_PUBLIC:
            return $ca_emoji->Convert('F8F3');
            //$icon = 'Ｑ';
          break;
        case STATUS_FINISH:
            return $ca_emoji->Convert('F9B0');
            //$icon = '解';
          break;

        default:
            return $ca_emoji->Convert('F8F3');
            //$icon = 'Ｑ';
          break;
    }*/
    global $carrier;
    $width = 15;
    $height = 15;
    if($carrier->softbank_vga){
        $width = 30;
        $height = 30;
    }
    switch ($status){
        case STATUS_PUBLIC:
            return '<img src="/img/question/question.gif" width="'.$width.'" height="'.$width.'" alt="" />';
          break;
        case STATUS_FINISH:
            return '<img src="/img/question/question_finish.gif" width="'.$width.'" height="'.$width.'" alt="" />';
          break;
        default:
            return '<img src="/img/question/question.gif" width="'.$width.'" height="'.$width.'" alt="" />';
          break;
    }
}
?>
