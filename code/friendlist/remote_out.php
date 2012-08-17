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

$queries = array();
//指定リスト内の全ての友達
if($isList){
    //$facebook->api_setting = array('','','uid','uid');//index変更 なぜかq1とq3が先に配列に入れられていることが判明
    //$queries['q1'] = 'SELECT uid,flid FROM friendlist_member WHERE flid = '.$_GET['flid'];//条件で指定したリスト内の全てのフレンド
    //$queries['q2'] = 'SELECT '.$column_comma.' FROM user WHERE uid in ( SELECT uid FROM #q1 ) '.$andQuery.' ORDER BY '.$order;;//条件下での全てのフレンド
    //$queries['q3'] = 'SELECT uid,flid FROM friendlist_member WHERE uid in ( SELECT uid FROM friendlist_member WHERE flid = '.$flid.' ) AND flid = '.$_GET['flid'];
    //$queries['q4'] = 'SELECT '.$column_comma.' FROM user WHERE uid in ( SELECT uid FROM #q3 ) '.$andQuery.' ORDER BY '.$order;
    
    $facebook->api_setting = array('uid','','uid');
    $queries['q1'] = 'SELECT '.$column_comma.' FROM user WHERE uid in ( SELECT uid,flid FROM friendlist_member WHERE flid = '.$_GET['flid'].' ) '.$andQuery.' ORDER BY '.$order;//条件下での全てのフレンド
    $queries['q2'] = 'SELECT uid,flid FROM friendlist_member WHERE uid in ( SELECT uid FROM friendlist_member WHERE flid = '.$flid.' ) AND flid = '.$_GET['flid'];
    $queries['q3'] = 'SELECT '.$column_comma.' FROM user WHERE uid in ( SELECT uid FROM #q2 ) '.$andQuery.' ORDER BY '.$order;
}else{
    $facebook->api_setting = array('uid','uid');//index変更
    $queries['q1'] = 'SELECT '.$column_comma.' FROM user WHERE uid in ( SELECT uid2 FROM friend WHERE uid1 = me() ) '.$andQuery.' ORDER BY '.$order;//条件下での全てのフレンド
    $queries['q2'] = 'SELECT '.$column_comma.' FROM user WHERE uid in ( SELECT uid FROM friendlist_member WHERE flid = '.$flid.' ) '.$andQuery.' ORDER BY '.$order;//指定リスト内の条件下での全てのフレンド
}


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
if($isList){
    //$all_friend = $result[2]['fql_result_set'];//indexを変更すると番号がずれます
    //$friendlist_all_friend = $result[3]['fql_result_set'];//indexを変更すると番号がずれます
    $all_friend = $result[0]['fql_result_set'];//indexを変更すると番号がずれます
    $friendlist_all_friend = $result[2]['fql_result_set'];//indexを変更すると番号がずれます
}else{
    $all_friend = $result[0]['fql_result_set'];
    $friendlist_all_friend = $result[1]['fql_result_set'];
}


//リストに入っていない友達を調査
$friendlist_diff_friend = array_diff_key($all_friend, $friendlist_all_friend);
$count_friendlist_diff_friend = count($friendlist_diff_friend);

$index = $page-1;

if( $count_friendlist_diff_friend != 0){
    //クッキー削除
    if(isset($_COOKIE[IN_IDS])){
        $in_ids = explode(',',$_COOKIE[IN_IDS]);
        $new_in_ids = array();
        foreach ($friendlist_friend as $key => $value){
            if(in_array($value['uid'],$in_ids)){
                $new_in_ids[] = $value['uid'];
            }
        }
        if(count($new_in_ids) > 0){
            setcookie(NEW_IN_IDS,implode(',',$new_in_ids),0,'/');
        }else{
            setcookie(NEW_IN_IDS,'',0,'/');
        }
    }
    
    $friendlist_diff_friend_chunk = array_chunk($friendlist_diff_friend,$rows);
    //描画の時は数が減る可能性あり
    $rows = count($friendlist_diff_friend_chunk[$index]);
    
    //html
    print '<table>';
    print '<tbody>';
    for ($i=0; $i<$rows; $i++){
        print '<tr id='.$friendlist_diff_friend_chunk[$index][$i]['uid'].'>';
        print '<td class="first"><img src='.'"'.$friendlist_diff_friend_chunk[$index][$i]['pic_square'].'" width="25" height="25" />'.$friendlist_diff_friend_chunk[$index][$i]['name'].'</td><td>'.util::makeSexText($friendlist_diff_friend_chunk[$index][$i]['sex']).'</td><td>'.util::makeAgeText($friendlist_diff_friend_chunk[$index][$i]['birthday_date']).'</td><td>'.util::makeRelationshipStatusText($friendlist_diff_friend_chunk[$index][$i]['relationship_status']).'</td>';
        print '</tr>';
    }
    print '<script type="text/javascript">_ingrid_table2_0_total = '.$count_friendlist_diff_friend.';</script>';
    print '</tbody>';
    print '</table>';
}else{
    //ない場合
    //html
    print '<table>';
    print '<tbody>';
    print '<tr>';
    print '<td>表示する友達がありません</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>';
    print '</tr>';
    print '<script type="text/javascript">_ingrid_table2_0_total = 0;</script>';
    print '</tbody>';
    print '</table>';
}

?>