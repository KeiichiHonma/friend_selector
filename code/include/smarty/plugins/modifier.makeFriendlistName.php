<?php
//08/22/1979
function smarty_modifier_makeFriendlistName($fiendlist_name,$byte = 30, $marker = '…')
{
    global $con;
    switch ($fiendlist_name){
        case 'Close Friends':
            return '親しい友達';
        break;

        case 'Family':
            return '家族';
        break;

        case 'Acquaintances':
            return '知り合い';
        break;

        default:
            return $con->base->mb_strimbyte($fiendlist_name,$byte,$marker);
    }
}

?>
