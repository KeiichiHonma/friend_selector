<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
/*ex) 
'メールアドレス'=>array('name'=>'mail','type'=>'text','func'=>null,'class'=>'form_text_common','must'=>TRUE,'front'=>'','back'=>'')*/

function smarty_modifier_make_text($string,$isLink = SMARTY_BOOL_ON,$strimbyte = SMARTY_BOOL_OFF)
{
    global $con;
    return $con->base->make_text($string,$isLink,$strimbyte);
}
?>
