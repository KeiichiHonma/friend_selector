<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
/*ex) 
'メールアドレス'=>array('name'=>'mail','type'=>'text','func'=>null,'class'=>'form_text_common','must'=>TRUE,'front'=>'','back'=>'')*/

function smarty_modifier_make_member_url($mid,$name,$isStrim = SMARTY_BOOL_ON,$length = 14,$isGray = SMARTY_BOOL_OFF)
{
    if($isStrim == SMARTY_BOOL_ON){
        global $con;
        $name = $con->base->mb_strimbyte($name,$length);
    }
    if($isGray == SMARTY_BOOL_ON){
        return '<a href="'.CAURL.'/member/view/mid/'.$mid.'" class="gray_link">'.htmlspecialchars($name).'</a>';
    }else{
        return '<a href="'.CAURL.'/member/view/mid/'.$mid.'">'.htmlspecialchars($name).'</a>';
    }
    
}
?>
