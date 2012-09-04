<?php
require_once('fw/prepend.php');
$uid = $con->base->getPath('uid',TRUE);
//$uid = 100002403327372;
//$uid = '100004133136080';

auth($facebook);
$access_token = $facebook->getAccessToken();


//FQL
$facebook->api_setting = array('','','','flid');//index変更1 2 4 3
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
            'q3' =>'SELECT flid FROM friendlist_member WHERE flid in ( SELECT flid FROM #q2 ) AND uid = '.$uid,
            'q4' =>'SELECT '.$column_comma.' FROM user WHERE uid = '.$uid
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

if(count($result[2]['fql_result_set']) == 0){
    require_once('fw/errorManager.php');
    errorManager::throwError(E_CMMN_USER_EXISTS);
}
$con->t->assign('uid',$uid);
$con->t->assign('user',$result[2]['fql_result_set']);

$con->t->assign('friendlist',$friendlist);

$user_in_friendlist = $result[3]['fql_result_set'];
if( count($user_in_friendlist) > 0){
    $con->t->assign('user_in_friendlist',$user_in_friendlist);
}



//seo
$con->t->assign('h1','index');

$con->append();
?>
