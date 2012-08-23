<?php
require_once('fw/prepend.php');
$flid = $con->base->getPath('flid',TRUE);

//ページ遷移でクッキー削除
if(isset($_GET['c']) && $_GET['c'] == 'd'){
    if(isset($_COOKIE[IN_IDS])) setcookie(IN_IDS,'',-1,'/');
    if(isset($_COOKIE[NEW_IN_IDS])) setcookie(NEW_IN_IDS,'',-1,'/');
    if(isset($_COOKIE[OUT_IDS])) setcookie(OUT_IDS,'',-1,'/');
    if(isset($_COOKIE[NEW_OUT_IDS])) setcookie(NEW_OUT_IDS,'',-1,'/');
    $con->safeExitRedirect('friendlist/view/flid/'.$flid,TRUE);
}

$user = auth($facebook,'/friendlist/view/flid/'.$flid);
$access_token = $facebook->getAccessToken();
$con->t->assign('flid',$flid);

$facebook->api_setting = array('','flid','uid','uid');//index変更
$facebook->is_multiquery = true;
$result = $facebook->api
(
    array
    (
        'method'=>'fql.multiquery',
        'queries'=>array
        (
            'q1' =>'select '.$con->permissions_comma.' from permissions where uid = me()',
            'q2' =>'SELECT flid, owner, name FROM friendlist WHERE owner = me() ORDER BY flid DESC',//全ての友達リスト
            'q3' =>'SELECT '.$column_comma.' FROM user WHERE uid in ( SELECT uid FROM friendlist_member WHERE flid = '.$flid.' ) ORDER BY uid ASC',//指定の友達リスト内のフレンド
            'q4' =>'SELECT '.$column_comma.' FROM user WHERE uid in ( SELECT uid2 FROM friend WHERE uid1 = me() )',//全てのフレンド
            'q5' =>'select gid, uid from group_member where uid = me()',//自分が入っているグループ取得
            'q6' =>'SELECT gid, name FROM group WHERE gid in ( SELECT gid FROM #q5 ) '//グループ情報取得
        ),
        'access_token'=>$access_token
    )
);
$con->checkPermissions($result[0]['fql_result_set']);

//リスト一覧
if(isset($result[1]['fql_result_set']) && count($result[1]['fql_result_set']) > 0){
    $friendlist = $result[1]['fql_result_set'];
    $con->t->assign('friendlist',$friendlist);
}

if(!array_key_exists($flid,$friendlist)){
    require_once('fw/errorManager.php');
    errorManager::throwError(E_CMMN_FRIENDLIST_EXISTS);
}

//存在しないリスト

//ありえない
if( count($friendlist) == 0 ){
    require_once('fw/errorManager.php');
    errorManager::throwError(E_CMMN_PERMISSIONS_ERROR);
}
//グループ一覧
if(isset($result[5]['fql_result_set']) && count($result[5]['fql_result_set']) > 0){
    $grouplist = $result[5]['fql_result_set'];
    $con->t->assign('grouplist',$grouplist);
}


//指定リスト内の全ての友達
$friendlist_friend = $result[2]['fql_result_set'];
$count_friendlist_friend = count($friendlist_friend);
if( $count_friendlist_friend != 0){
    $friendlist_friend_first = array_chunk($friendlist_friend,$rows,true);
    $con->t->assign('friendlist_friend',$friendlist_friend_first[0]);
    $con->t->assign('count_friendlist_friend',$count_friendlist_friend);//リスト内の友だち総数
    //table1側 全チェック確認
    $con->t->assign('is_allCheck1',util::isAllCheckbox($friendlist_friend_first[0],$_COOKIE[OUT_IDS]));
}


//全ての友達
$all_friend = $result[3]['fql_result_set'];

//リストに入っていない友達を調査
$friendlist_diff_friend = array_diff_key($all_friend, $friendlist_friend);
$count_friendlist_diff_friend = count($friendlist_diff_friend);//リスト内に入っていない友だち総数

if( $count_friendlist_diff_friend != 0){
    $friendlist_diff_friend_first = array_chunk($friendlist_diff_friend,$rows,true);
    $con->t->assign('friendlist_diff_friend',$friendlist_diff_friend_first[0]);
    $con->t->assign('count_friendlist_diff_friend',$count_friendlist_diff_friend);
    //table2側 全チェック確認
    $con->t->assign('is_allCheck2',util::isAllCheckbox($friendlist_diff_friend_first[0],$_COOKIE[IN_IDS]));
}


//seo
$con->t->assign('h1','view');

$con->append();
?>
