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
function smarty_modifier_make_report_judge($date)
{
    $now = time();
    $now_year = date("Y",$now);
    $now_month = date("m",$now);
    $now_day = date("d",$now);
    
    $report_year = date("Y",$date);
    $report_month = date("m",$date);
    $report_day = date("d",$date);
    
    $today_report_time = mktime(13, 0, 0, $now_month,$now_day,$now_year);//13時配信

    if($now_year == $report_year && $now_month == $report_month && $now_day == $report_day){
        if($now > $today_report_time){
            return '配信済み';
        }else{
            return '本日配信';
        }
    }elseif($date > $today_report_time){
        return '配信待ち';
    }else{
        return '配信済み';
    }
}
?>
