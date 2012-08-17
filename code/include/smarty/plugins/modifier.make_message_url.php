<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
/*ex) 
define('TARGET_COMMON',  0);
define('TARGET_MEMBER',  1);
define('TARGET_USER',    2);
define('TARGET_MU',      3);
define('TARGET_ALL',     4);
define('TARGET_BLANK',   5);
*/

function smarty_modifier_make_message_url($message,$isSystem = SMARTY_BOOL_OFF)
{
    $title = htmlspecialchars($message['col_title']);
    if(strlen($message['col_url']) > 0){
        return '<a href="'.$message['col_url'].'">'.$title.'</a>';
    }
    switch ($message['col_target']){
        case TARGET_MEMBER:
            return $message['col_link'] == 1 ? $title : '<a href="/mypage/message/view/msid/'.$message['_id'].'">'.$title.'</a>';
          break;
        case TARGET_USER:
            return $message['col_link'] == 1 ? $title : '<a href="/system/message/view/msid/'.$message['_id'].'">'.$title.'</a>';
          break;
        case TARGET_MU:
            return $isSystem == SMARTY_BOOL_ON ? $message['col_link'] == 1 ? $title :  '<a href="/system/message/view/msid/'.$message['_id'].'">'.$title.'</a>' : $message['col_link'] == 1 ? $title :  '<a href="/mypage/message/view/msid/'.$message['_id'].'">'.$title.'</a>';
          break;
        case TARGET_BLANK:
            return $message['col_link'] == 1 ? $title : '<a href="'.$message['col_url'].'">'.$title.'</a>';
          break;
        default:
            return $message['col_link'] == 1 ? $title : '<a href="/message/view/msid/'.$message['_id'].'">'.$title.'</a>';
          break;
    }
}
?>
