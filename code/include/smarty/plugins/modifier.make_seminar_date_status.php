<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty plugin
 *
 * Type:     modifier<br>
 * Name:     nl2br<br>
 * Date:     Feb 26, 2003
 * Purpose:  convert \r\n, \r or \n to <<br>>
 * Input:<br>
 *         - contents = contents to replace
 *         - preceed_test = if true, includes preceeding break tags
 *           in replacement
 * Example:  {$text|nl2br}
 * @link http://smarty.php.net/manual/en/language.modifier.nl2br.php
 *          nl2br (Smarty online manual)
 * @version  1.0
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @return string
 */
function smarty_modifier_make_seminar_date_status($status)
{
    $html = '';
    switch ($status){
        case SCHOOL_SEMINAR_DATE_HUB_DELETE:
            $html = '<span class="alert">【開催校削除】</span>';
        break;
        case SCHOOL_SEMINAR_DATE_READY:
            $html = '<span class="alert">【無効】</span>';
        break;
        case SCHOOL_SEMINAR_DATE_FINISH:
            $html = '<span class="alert">【終了】</span>';
        break;
        case SCHOOL_SEMINAR_DATE_CLOSE:
            $html = '<span class="alert">【終了】</span>';
        break;
        case SCHOOL_SEMINAR_DATE_PUBLIC:
            $html = '<span class="public">【有効】</span>';
        break;
    }
    return $html;
}
?>
