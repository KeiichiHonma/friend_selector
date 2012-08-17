<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
/*ex) 
'メールアドレス'=>array('name'=>'mail','type'=>'text','func'=>null,'class'=>'form_text_common','must'=>TRUE,'front'=>'','back'=>'')*/

function smarty_modifier_make_strim($string,$byte = 30, $marker = '…')
{
    global $con;
    //return mb_strimwidth($string,0,$byte,'…','UTF-8');
    return $con->base->mb_strimbyte($string,$byte,$marker);
    //mb_strimwidth($string,0,$byte,'…','UTF-8');
}
?>
