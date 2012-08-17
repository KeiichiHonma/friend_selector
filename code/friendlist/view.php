<?php
require_once('fw/prepend.php');
$flid = $con->base->getPath('flid',TRUE);
$user = auth($facebook,'/friendlist/view/flid/'.$flid);
$access_token = $facebook->getAccessToken();
$con->t->assign('flid',$flid);

//$facebook->api_setting = array('flid','uid','uid');//index変更
//$facebook->is_multiquery = TRUE;
$facebook->api_setting = array('flid','uid','uid');//index変更
$facebook->is_multiquery = true;
$result = $facebook->api
(
    array
    (
        'method'=>'fql.multiquery',
        'queries'=>array
        (
            'q1' =>'SELECT flid, owner, name FROM friendlist WHERE owner = me() ORDER BY flid DESC',//全ての友達リスト
            'q2' =>'SELECT '.$column_comma.' FROM user WHERE uid in ( SELECT uid FROM friendlist_member WHERE flid = '.$flid.' ) ORDER BY uid ASC',//指定の友達リスト内のフレンド
            'q3' =>'SELECT '.$column_comma.' FROM user WHERE uid in ( SELECT uid2 FROM friend WHERE uid1 = me() )',//全てのフレンド
            //'q4' =>'select gid, uid from group_member where uid = me()',//自分が入っているグループ取得
            //'q5' =>'SELECT gid, name FROM group WHERE gid in ( SELECT gid FROM #q4 ) '//グループ情報取得
        ),
        'access_token'=>$access_token
    )
);

//リスト一覧
if(isset($result[0]['fql_result_set']) && count($result[0]['fql_result_set']) > 0){
    $friendlist = $result[0]['fql_result_set'];
    $con->t->assign('friendlist',$friendlist);
}


//グループ一覧
if(isset($result[4]['fql_result_set']) && count($result[4]['fql_result_set']) > 0){
    $grouplist = $result[4]['fql_result_set'];
    $con->t->assign('grouplist',$grouplist);
}


//指定リスト内の全ての友達
$friendlist_friend = $result[1]['fql_result_set'];
$count_friendlist_friend = count($friendlist_friend);
if( $count_friendlist_friend != 0){
    $friendlist_friend_first = array_chunk($friendlist_friend,$rows);
    $con->t->assign('friendlist_friend',$friendlist_friend_first[0]);
    $con->t->assign('count_friendlist_friend',$count_friendlist_friend);//リスト内の友だち総数
}


//全ての友達
$all_friend = $result[2]['fql_result_set'];

//リストに入っていない友達を調査
$friendlist_diff_friend = array_diff_key($all_friend, $friendlist_friend);
$count_friendlist_diff_friend = count($friendlist_diff_friend);//リスト内に入っていない友だち総数

if( $count_friendlist_diff_friend != 0){
    $friendlist_diff_friend_first = array_chunk($friendlist_diff_friend,$rows);
    $con->t->assign('friendlist_diff_friend',$friendlist_diff_friend_first[0]);
    $con->t->assign('count_friendlist_diff_friend',$count_friendlist_diff_friend);
}

//seo
$con->t->assign('h1','view');

$con->append();
?>
