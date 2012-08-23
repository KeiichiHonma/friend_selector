<?php
require_once('fw/prepend.php');
$flid = $con->base->getPath('flid',TRUE);

if(isset($_GET['page']) && is_numeric($_GET['page'])){
    $page = $_GET['page'];
    $from = $_GET['page']*$rows - $rows;
    $to   = $rows;
}else{
    $page = 1;
    $from = 0;
    $to   = $rows;
}

//where///////////////////////////////////////////////
$andQuery = '';
$andArray = array();

//sex////////////////////
if(isset($_GET['sex']) && strlen($_GET['sex']) > 0){
    if($_GET['sex'] == 'male'){
        $andArray['sex'] = 'sex = \'male\'';
    }elseif($_GET['sex'] == 'female'){
        $andArray['sex'] = 'sex = \'female\'';
    }elseif($_GET['sex'] == 'open'){
        unset($andArray['sex']);
    }
}

//relationship//////////
/*'Single'=>'独身',
'In a relationship'=>'交際中',
'Engaged'=>'婚約中',
'Married'=>'既婚',
'It\'s complicated'=>'複雑な関係',
'In an open relationship'=>'オープンな関係',
'Widowed'=>'配偶者と死別',
'Separated'=>'別居中',
'Divorced'=>'離婚'


'single'=>'独身',
'in_a_relationship'=>'交際中',
'engaged'=>'婚約中',
'married'=>'既婚',
'complicated'=>'複雑な関係',
'open'=>'オープンな関係',
'widowed'=>'配偶者と死別',
'separated'=>'別居中',
'divorced'=>'離婚'*/
if(isset($_GET['relationship']) && strlen($_GET['relationship']) > 0){
    $relationship = '';
    switch ($_GET['relationship']){
        case 'single':
            $relationship = 'Single';
        break;

        case 'in_a_relationship':
            $relationship = 'In a relationship';
        break;

        case 'engaged':
            $relationship = 'Engaged';
        break;

        case 'married':
            $relationship = 'Married';
        break;

        case 'complicated':
            $relationship = "It's complicated";
        break;

        case 'in_an_open_relationship':
            $relationship = 'In an open relationship';
        break;

        case 'widowed':
            $relationship = 'Widowed';
        break;

        case 'separated':
            $relationship = 'Separated';
        break;

        case 'divorced':
            $relationship = 'Divorced';
        break;
    }
    if($relationship != ''){
        $andArray['relationship'] = 'relationship_status = "'.$relationship.'"';
    }else{
        unset($andArray['relationship']);
    }
}

if(count($andArray) > 0){
    $andQuery .= implode(' AND ',$andArray);
    $andQuery = ' AND '.$andQuery;
}

//order by//////////////////////////
$order = '';
if(isset($_GET['sort'])){
    switch ($_GET['sort']){
        case 0:
            $order .= 'name';
        break;
        case 1:
            $order .= 'name';
        break;
        
        default:
            $order .= 'uid';
    }
}else{
    $order .= 'uid';
}

if(isset($_GET['dir'])){
    if($_GET['dir'] == 'asc'){
        $order .= ' ASC';
    }else{
        $order .= ' DESC';
    }
}else{
    $order .= ' ASC';
}

//list 追加でリストで絞り込み
$isList = false;
if(isset($_GET['flid']) && is_numeric($_GET['flid'])){
    $isList = true;
}

//group 追加でグループで絞り込み
$isGroup = false;
if(isset($_GET['gid']) && is_numeric($_GET['gid'])){
    $isGroup = true;
}

//指定リスト内の全ての友達/////////////////////////////////////////////

//query番号のチェック
if($isList && $isGroup){
    $list_query_no = 'q1';
    $group_query_no = 'q2';
    $use_query_no = 'q2';
    $user_query_no = 'q3';
    
    $all_list_query_no = 'q4';
    $all_group_query_no = 'q5';
    $all_use_query_no = 'q5';
    $all_user_query_no = 'q6';
    
    //結果が 1 4 2 5 3 6
    $result_no = 4;
    $all_result_no = 5;
    $facebook->api_setting = array('','','','','uid','uid');//index変更 
}elseif($isList && !$isGroup){
    $list_query_no = 'q1';
    $use_query_no = 'q1';
    $user_query_no = 'q2';
    
    $all_list_query_no = 'q3';
    $all_use_query_no = 'q3';
    $all_user_query_no = 'q4';
    
    //結果が 1 3 2 4 になる
    $result_no = 2;
    $all_result_no = 3;
    $facebook->api_setting = array('','','uid','uid');//index変更 
}elseif(!$isList && $isGroup){
    $list_query_no = 'q1';
    $group_query_no = 'q2';
    $use_query_no = 'q2';
    $user_query_no = 'q3';
    
    $all_group_query_no = 'q4';
    $all_use_query_no = 'q4';
    $all_user_query_no = 'q5';
    
    //結果が 1 4 2 5 3 になる
    $result_no = 4;
    $all_result_no = 3;
    $facebook->api_setting = array('','','','uid','uid');//index変更
}else{
    $user_query_no = 'q1';
    $all_user_query_no = 'q2';

    $result_no = 0;
    $all_result_no = 1;
    $facebook->api_setting = array('uid','uid');//index変更
}

//query生成
if($isList){
    //入ってる人用
    $queries[$list_query_no] = 'SELECT uid,flid FROM friendlist_member WHERE uid in ( SELECT uid FROM friendlist_member WHERE flid = '.$flid.' ) AND flid = '.$_GET['flid'];
    
    //全体取得用
    $queries[$all_list_query_no] = 'SELECT uid,flid FROM friendlist_member WHERE flid = '.$_GET['flid'];
}elseif($isGroup){
    //入ってる人用
    //$queries[$list_query_no] = 'SELECT uid,flid FROM friendlist_member WHERE uid in ( SELECT uid FROM friendlist_member WHERE flid = '.$flid.' )';
    $queries[$list_query_no] = 'SELECT uid,flid FROM friendlist_member WHERE  flid = '.$flid;
}

if($isGroup){
    $group_string = 'select uid,gid FROM group_member WHERE gid = '.$_GET['gid'].' AND uid != me()';
    //$group_string = 'select uid,gid FROM group_member WHERE gid = '.$_GET['gid'].' AND uid != me() AND uid in ( SELECT uid FROM #'.$all_list_query_no.' )';
    
    if($isList){
        //全体取得用
        $all_group_string = $group_string.' AND uid in ( SELECT uid FROM #'.$all_list_query_no.' )';
        $queries[$all_group_query_no] = $all_group_string;
        
    }else{
        //全体取得用
        //これをいれないとグループに入っている知らない人も表示されてしまう。
        $group_string .= ' AND uid in ( SELECT uid2 FROM friend WHERE uid1 = me() )';///自分の友達限定
        $queries[$all_group_query_no] = $group_string;
    }
    
    //入ってる人用
    $group_string .= ' AND uid in ( SELECT uid FROM #'.$list_query_no.' )';
    $queries[$group_query_no] = $group_string;
    
}

if($isList || $isGroup){
    //入ってる人用
    $queries[$user_query_no] = 'SELECT '.$column_comma.' FROM user WHERE uid in ( SELECT uid FROM #'.$use_query_no.' ) '.$andQuery.' ORDER BY '.$order;
    //全体取得用
    $queries[$all_user_query_no] = 'SELECT '.$column_comma.' FROM user WHERE uid in ( SELECT uid FROM #'.$all_use_query_no.' ) '.$andQuery.' ORDER BY '.$order;    
}else{
    $queries[$user_query_no] = 'SELECT '.$column_comma.' FROM user WHERE uid in ( SELECT uid FROM friendlist_member WHERE flid = '.$flid.' ) '.$andQuery.' ORDER BY '.$order;//指定リスト内の条件下での全てのフレンド
    $queries[$all_user_query_no] = 'SELECT '.$column_comma.' FROM user WHERE uid in ( SELECT uid2 FROM friend WHERE uid1 = me() ) '.$andQuery.' ORDER BY '.$order;//条件下での全てのフレンド
}
ksort($queries);

$facebook->is_multiquery = true;
$result = $facebook->api
(
    array
    (
        'method'=>'fql.multiquery',
        'queries'=>$queries,
        'access_token'=>$access_token
    )
);

$all_friend = $result[$all_result_no]['fql_result_set'];
$friendlist_all_friend = $result[$result_no]['fql_result_set'];
/*var_dump($friendlist_all_friend);
var_dump($all_friend);
die();*/
//リストに入っていない友達を調査
$friendlist_diff_friend = array_diff_key($all_friend, $friendlist_all_friend);
$count_friendlist_diff_friend = count($friendlist_diff_friend);

$index = $page-1;
$is_zero = false;
if( $count_friendlist_diff_friend != 0){
    //クッキー削除
    if(isset($_COOKIE[IN_IDS])){
        $in_ids = explode(',',$_COOKIE[IN_IDS]);
        $new_in_ids = array();
        foreach ($friendlist_diff_friend as $key => $value){
            if(in_array($value['uid'],$in_ids)){
                $new_in_ids[] = $value['uid'];
            }
        }
        $new_in_ids_string = '';
        if(count($new_in_ids) > 0){
            $new_in_ids_string = implode(',',$new_in_ids);
            setcookie(NEW_IN_IDS,implode(',',$new_in_ids),0,'/');
        }else{
            //表示する友達はいるが、クッキーの中にある友達以外であった場合。なのでblankではない
            setcookie(NEW_IN_IDS,'blank',0,'/');
        }
    }
    
    $friendlist_diff_friend_chunk = array_chunk($friendlist_diff_friend,$rows,true);
    //描画の時は数が減る可能性あり
    $rows = count($friendlist_diff_friend_chunk[$index]);
    if($rows != 0){
        //html
        print '<table>';
        print '<tbody>';
        $i = 1;
        foreach ($friendlist_diff_friend_chunk[$index] as $uid => $value){
            print '<tr id="'.$uid.'" name="tr_2">';
            print '<td><input type="checkbox" name="chk2['.$i.']" /></td>';
            print '<td class="first"><img src='.'"'.$value['pic_square'].'" width="25" height="25" />'.$value['name'].'</td><td>'.util::makeSexText($value['sex']).'</td><td>'.util::makeAgeText($value['birthday_date']).'</td><td>'.util::makeRelationshipStatusText($value['relationship_status']).'</td>';
            print '</tr>';
            $i++;
        }
        print '<script type="text/javascript">_ingrid_table2_0_total = '.$count_friendlist_diff_friend.';isSearch = true;form_handle(\'view\',false);</script>';
        //table1側 全チェック確認 //NEW_OUT_IDSでチェック
        if(util::isAllCheckbox($friendlist_diff_friend_chunk[$index],$new_in_ids_string) == 'checked'){
            print '<script type="text/javascript">$(function(){doAllCheck(2);});</script>';
        }else{
            print '<script type="text/javascript">$(function(){resetAllCheck(2);});</script>';
        }
        print '</tbody>';
        print '</table>';
    }else{
        $is_zero = true;
    }
}else{
    if(isset($_COOKIE[IN_IDS])){
        //表示する友達がいないが、クッキーがあるため見えないPOSTが発生するため、blankで処理を止める
        setcookie(NEW_IN_IDS,'blank',0,'/');
    }
    $is_zero = true;
}
if($is_zero){
    //ない場合
    //html
    print '<table>';
    print '<tbody>';
    print '<tr id="blank2">';
    print '<td><img src="/img/exclamation.png" border="0"></td><td>表示する友達がありません</td><td></td><td></td><td></td>';
    print '</tr>';
    print '<script type="text/javascript">_ingrid_table2_0_total = 0;isSearch = true;document.getElementById(\'allCheck2\').disabled = true;form_handle(\'view\',false);</script>';
    print '<script type="text/javascript">$(function(){resetAllCheck(2);});</script>';
    print '</tbody>';
    print '</table>';
}
?>