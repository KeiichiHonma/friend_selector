<?php
require_once('fw/prepend.php');

if(isset($_GET['page']) && is_numeric($_GET['page'])){
    $page = $_GET['page'];
    $from = $_GET['page']*$user_rows - $user_rows;
    $to   = $user_rows;
}else{
    $page = 1;
    $from = 0;
    $to   = $user_rows;
}

//where///////////////////////////////////////////////
$andQuery = '';
$andArray = array();

//new friend////////////////////
$is_new_friend = false;
if(isset($_GET['new_friend']) && strlen($_GET['new_friend']) > 0){
    if($_GET['new_friend'] == 'new'){
        $is_new_friend = true;
    }
}

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
        //unset($andArray['relationship']);
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
    $order .= ' DESC';
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
    $result_no = 2;
    $facebook->api_setting = array('','','uid');//index変更
}elseif($isList && !$isGroup){
    $list_query_no = 'q1';
    $use_query_no = 'q1';
    $user_query_no = 'q2';
    $result_no = 1;
    $facebook->api_setting = array('','uid');//index変更
}elseif(!$isList && $isGroup){
    $list_query_no = 'q1';
    $group_query_no = 'q2';
    $use_query_no = 'q2';
    $user_query_no = 'q3';
    $result_no = 2;
    
    if($is_new_friend){
        $new_friend_query_no = 'q4';
        $use_query_no2 = 'q4';
        $user_query_no2 = 'q5';
        $result_no = 4;
        $facebook->api_setting = array('','','uid','','uid');//index変更
    }else{
        $facebook->api_setting = array('','','uid');//index変更
    }
    
    
    
    
}elseif(!$isList && $is_new_friend){
    $list_query_no = 'q1';
    $new_friend_query_no = 'q2';
    $use_query_no = 'q1';
    $use_query_no2 = 'q2';
    $user_query_no = 'q3';
    $user_query_no2 = 'q4';
    $result_no = 2;
    $result_no2 = 3;
    
    $facebook->api_setting = array('','uid','uid','uid');//index変更
    
    
}

//query生成
if($isList){
    $queries[$list_query_no] = 'SELECT uid,flid FROM friendlist_member WHERE flid = '.$_GET['flid'];
}else{
    //$queries[$list_query_no] = 'SELECT uid,flid FROM friendlist_member WHERE uid in ( SELECT uid2 FROM friend WHERE uid1 = me() )';
    $queries[$list_query_no] = 'SELECT '.$column_comma.' FROM user WHERE uid in ( SELECT uid2 FROM friend WHERE uid1 = me() ) ORDER BY uid DESC';//全てのフレンド
}

if($isGroup){
    $queries[$group_query_no] = 'select uid,gid from group_member WHERE gid = '.$_GET['gid'].' AND uid != me() AND uid in ( SELECT uid FROM #'.$list_query_no.' )';
}

if($is_new_friend){
    $queries[$new_friend_query_no] = 'SELECT uid FROM friendlist_member WHERE flid in ( SELECT flid FROM friendlist WHERE owner = me() AND type = "user_created" ) ORDER BY uid DESC';//ユーザー作成のリストに入っている友達
}

if($isList || $isGroup || $is_new_friend){
    $queries[$user_query_no] = 'SELECT '.$column_comma.' FROM user WHERE uid in ( SELECT uid FROM #'.$use_query_no.' ) '.$andQuery.' ORDER BY '.$order;
    if($is_new_friend) $queries[$user_query_no2] = 'SELECT '.$column_comma.' FROM user WHERE uid in ( SELECT uid FROM #'.$use_query_no2.' ) '.$andQuery.' ORDER BY '.$order;
    ksort($queries);

    $facebook->is_multiquery = TRUE;
    $result = $facebook->api
    (
        array
        (
            'method'=>'fql.multiquery',
            'queries'=>$queries,
            'access_token'=>$access_token
        )
    );
    if($is_new_friend){
        $all_friendlist_friend = $result[$result_no]['fql_result_set'];
        $list_in_friendlist_friend = $result[$result_no2]['fql_result_set'];

        $count_list_in_friendlist_friend = count($list_in_friendlist_friend);
        if( $count_list_in_friendlist_friend != 0){
            //リストに入っていない友達を調査
            $friendlist_friend = array_diff_key($all_friendlist_friend, $list_in_friendlist_friend);
        }
    }else{
        $friendlist_friend = $result[$result_no]['fql_result_set'];
    }
    
}else{
    $facebook->api_setting = array('uid');//index変更
    $friendlist_friend = $facebook->api(array('method' => 'fql.query',
                                   //'query' =>'SELECT '.$column_comma.' FROM user WHERE uid in ( SELECT uid FROM friendlist_member WHERE flid = '.$flid.' ) '.$andQuery.' ORDER BY '.$order,
                                   'query' =>'SELECT '.$column_comma.' FROM user WHERE uid in ( SELECT uid2 FROM friend WHERE uid1 = me() ) '.$andQuery.' ORDER BY '.$order,
                                   'access_token' =>$access_token,
                                   ));
}

$index = $page-1;
$count_friendlist_friend = count($friendlist_friend);

$is_zero = false;
if( $count_friendlist_friend != 0){
    $friendlist_friend_chunk = array_chunk($friendlist_friend,$user_rows,true);

    //描画の時は数が減る可能性あり
    $user_rows = count($friendlist_friend_chunk[$index]);
    if($user_rows != 0){
        //html
        print '<table>';
        print '<tbody>';
        print '<tr>';
        print '<td>';
        $i = 1;
        foreach ($friendlist_friend_chunk[$index] as $uid => $value){
            //<a class='ajax' href="/user/view/uid/{$value.uid}" title="{$value.name}"><img src="{$value.pic_square}" width="50" height="50" /><br />{$value.name}</a>
            print '<span class="grid-user"><a class="cb2" href="/user/view/uid/'.$value['uid'].'" title="一度に更新(追加、削除)できるのは50個までです。"><img src='.'"'.$value['pic_square'].'" width="50" height="50" /><br />'.$value['name'].'</a></span>';
            $i++;
        }
        //print '<script type="text/javascript" id="test_s">_ingrid_table3_0_total = '.$count_friendlist_friend.';isSearch = true;form_handle(\'view\',false);</script>';
        print '<input type="hidden" id="_ingrid_table3_0_total_remote" class="_ingrid_table3_0_total_remote" value="'.$count_friendlist_friend.'" />';
        print '</td>';
        print '</tr>';
        print '</tbody>';
        print '</table>';
    }else{
        $is_zero = true;
    }
}else{
    $is_zero = true;
}
if($is_zero){
    //ない場合
    //html
    print '<table>';
    print '<tbody>';
    print '<tr>';
    print '<td><img src="/img/exclamation.png" border="0" style="vertical-align:middle;">表示する友達がありません</td>';
    print '</tr>';
    //print '<script type="text/javascript">_ingrid_table3_0_total = 0;isSearch = true;form_handle(\'view\',false);</script>';
    print '<input type="hidden" id="_ingrid_table3_0_total_remote" class="_ingrid_table3_0_total_remote" value="0" />';
    print '</tbody>';
    print '</table>';
}
?>