<?php
require_once('fw/prepend.php');

//ページ遷移でクッキー削除
if(isset($_GET['c']) && $_GET['c'] == 'd'){
    if(isset($_COOKIE[IN_IDS])) setcookie(IN_IDS,'',-1,'/');
    if(isset($_COOKIE[NEW_IN_IDS])) setcookie(NEW_IN_IDS,'',-1,'/');
    if(isset($_COOKIE[OUT_IDS])) setcookie(OUT_IDS,'',-1,'/');
    if(isset($_COOKIE[NEW_OUT_IDS])) setcookie(NEW_OUT_IDS,'',-1,'/');
    $con->safeExitRedirect('',TRUE);
}

auth($facebook);
$access_token = $facebook->getAccessToken();


//FQL
$facebook->api_setting = array('','','uid','uid');//index変更
$facebook->is_multiquery = true;
$result = $facebook->api
(
    array
    (
        'method'=>'fql.multiquery',
        'queries'=>array
        (
            'q1' =>'select '.$con->permissions_comma.' from permissions where uid = me()',
            'q2' =>'SELECT flid, name FROM friendlist WHERE owner = me() ORDER BY flid DESC',
            'q3' =>'SELECT uid FROM friendlist_member WHERE flid in ( SELECT flid FROM friendlist WHERE owner = me() AND type = "user_created" ) ORDER BY uid DESC',//ユーザー作成のリストに入っている友達
            'q4' =>'SELECT '.$column_comma.' FROM user WHERE uid in ( SELECT uid2 FROM friend WHERE uid1 = me() ) ORDER BY uid DESC',//全てのフレンド
            'q5' =>'select gid, uid from group_member where uid = me()',//自分が入っているグループ取得
            'q6' =>'SELECT gid, name FROM group WHERE gid in ( SELECT gid FROM #q5 ) '//グループ情報取得
        ),
        'access_token'=>$access_token
    )
);

$con->checkPermissions($result[0]['fql_result_set']);
$friendlist = $result[1]['fql_result_set'];
//ありえない
if( count($friendlist) == 0 ){
    require_once('fw/errorManager.php');
    errorManager::throwError(E_CMMN_PERMISSIONS_ERROR);
}
$con->t->assign('friendlist',$friendlist);
//グループ一覧
if(isset($result[5]['fql_result_set']) && count($result[5]['fql_result_set']) > 0){
    $grouplist = $result[5]['fql_result_set'];
    $con->t->assign('grouplist',$grouplist);
}

//ユーザーが作成したリストに入っていない友達を抽出。つまり友達になったばかりの友達の可能性が高い
//指定リスト内の全ての友達
$friendlist_friend = $result[2]['fql_result_set'];
$count_friendlist_friend = count($friendlist_friend);

//全ての友達
$all_friend = $result[3]['fql_result_set'];

if( $count_friendlist_friend != 0){
    //リストに入っていない友達を調査
    $friendlist_diff_friend = array_diff_key($all_friend, $friendlist_friend);
    $count_friendlist_diff_friend = count($friendlist_diff_friend);//リスト内に入っていない友だち総数
    if($count_friendlist_diff_friend > 0){
        $con->t->assign('friendlist_diff_friend',$friendlist_diff_friend);
    }
}
$con->t->assign('count_all_friend',count($all_friend));
$all_friend_first = array_chunk($all_friend,$user_rows,true);
$con->t->assign('all_friend',$all_friend_first[0]);


$con->t->assign('user_height',util::getUserHeight($user_rows,$user_height,count($all_friend_first[0])));
$con->t->assign('user','user');

$con->append();
?>
