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
//$headは先頭の日時だけ出力する場合
function smarty_modifier_unserialize_date($serialize_date,$head = null,$isNow = SMARTY_BOOL_ON)
{
    require_once('user/school/util.php');
    $util = new schoolUtil();
    if(is_null($serialize_date)) return '-';
    global $school_property;
    $aid = FALSE;
    $said = FALSE;
    $isAreaSelectSeminar = FALSE;
    if(is_null($school_property)){
        global $school_util;
        if(is_null($school_util)){
            require_once('area/logic.php');
            $area_logic = new areaLogic();
        }else{
            $area_logic = $school_util->member_seminar_property->area_logic;
            $aid = $school_util->member_seminar_property->aid;
            $said = $school_util->member_seminar_property->said;
            $isAreaSelectSeminar = $school_util->member_seminar_property->isAreaSelectSeminar;
        }

        //if($member_auth->member_aid){
        //    $isAreaSelectSeminar = TRUE;
        //    $aid = $member_auth->member_aid;
        //    $said = $member_auth->member_said;
        //}
    }else{
        $area_logic = $school_property->area_logic;
        $aid = $school_property->aid;
        $said = $school_property->said;
        $isAreaSelectSeminar = $school_property->isAreaSelectSeminar;
    }
    $date = unserialize($serialize_date);
    $result = '';
    $toPutComma = FALSE;
    $i = 0;

    $list = array();
    $return = '';
    $now = time();

    foreach ($date as $key => $array){
        $go = FALSE;
        $string = '';
        $bl = FALSE;

        if($isAreaSelectSeminar && $aid){

            if(strcasecmp($aid,$array['aid']) == 0){
                if($said){
                    if(strcasecmp($said,$array['said']) == 0){
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

        if($isNow == SMARTY_BOOL_ON && $array['from'] > $now){
            $bl = TRUE;
        }elseif($isNow == SMARTY_BOOL_OFF){
            $bl = TRUE;
        }
        if($bl && $go){
            $string .= $util->makeDateFormat($array['from'],$array['to'],FALSE).'：'.$area_logic->sub_area_info[$array['said']]['col_name'];
            $list[] = $string;
            $i++;
        }
    }

//var_dump($list);

    $list = array_unique($list);
    $count = count($list);
    if($count == 0) return '';
    
    $end = '';
    if(!is_null($head)){
        if($count > $head){
            //return 1;
            //頭出しの処理
            $toPutComma = FALSE;
            $i = 0;
            foreach ($list as $key => $value){
                if($head == $i) break;
                if ( $toPutComma ) {
                    $return .= '、';
                } else {
                    $toPutComma = TRUE;
                }
                $return .= $value;
                $i++;
            }
            $end = '、他';
            return $return.$end;
        }else{
            //return 2;
            return implode('、',$list);
        }
    }else{
        return implode('、',$list);
    }
}
?>