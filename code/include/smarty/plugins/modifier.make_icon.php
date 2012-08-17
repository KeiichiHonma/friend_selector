<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
/*ex) 
'メールアドレス'=>array('name'=>'mail','type'=>'text','func'=>null,'class'=>'form_text_common','must'=>TRUE,'front'=>'','back'=>'')*/

function smarty_modifier_make_icon($icon_type,$width = 200)
{
    switch ($icon_type){
        case ICON_QUESTION:
            return '<img src="/img/system/type_question.gif" alt="" width="31" height="30" />';
          break;
        case ICON_REPLY:
            return '<img src="/img/system/type_reply.gif" alt="" width="31" height="30" />';
          break;
        case ICON_THANK:
            return '<img src="/img/system/type_thank.gif" alt="" width="31" height="30" />';
          break;
        default:
          break;
    }
}
?>
