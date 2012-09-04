<?php
require_once('fw/prepend.php');
if(!$con->isPost) $con->safeExitRedirect('',TRUE);
$uid = $con->base->getPath('uid',TRUE);

//FQL
$facebook->api_setting = array('','flid','flid');//index変更1 2 4 3
$facebook->is_multiquery = true;
$result = $facebook->api
(
    array
    (
        'method'=>'fql.multiquery',
        'queries'=>array
        (
            'q1' =>'select '.$con->permissions_comma.' from permissions where uid = me()',
            'q2' =>'SELECT flid, name FROM friendlist WHERE owner = me()',
            'q3' =>'SELECT flid FROM friendlist_member WHERE flid in ( SELECT flid FROM #q2 ) AND uid = '.$uid,
        ),
        'access_token'=>$access_token
    )
);
$con->checkPermissions($result[0]['fql_result_set']);

if(isset($_POST['flids']) && count($_POST['flids']) > 0){
    $flip_flids = array_flip($_POST['flids']);
}else{
    //チェックボックス前解除。全削除の命令
    $flip_flids = array();
}


$friendlist_diff = array_diff_key( $flip_flids , $result[1]['fql_result_set'] );

//リストにない場合だけ
if(count($friendlist_diff) > 0){
    //require_once('fw/errorManager.php');
    //errorManager::throwError(E_CMMN_FRIENDLIST_EXISTS);
    //print 'error'."\n";
    print '友達リストが削除されたか存在しません。';
    die();
}

//追加するリスト
$friendlist_diff_in = array_diff_key( $flip_flids , $result[2]['fql_result_set'] );
$count_friendlist_diff_in = count($friendlist_diff_in);

//削除するリスト
$friendlist_diff_out = array_diff_key( $result[2]['fql_result_set'] , $flip_flids );
$count_friendlist_diff_out = count($friendlist_diff_out);

if($count_friendlist_diff_in + $count_friendlist_diff_out > 50 ){
    //print 'error'."\n";
    print '一度に変更できる友達リストは追加と削除を合わせて50個までです。';
    die();
}

//追加
if( $count_friendlist_diff_in > 0){
    
    foreach ($friendlist_diff_in as $flid => $value){
        if(is_numeric($flid)){
            $in_requests[] = array( 'method' =>'POST', 'relative_url' =>'/'.$flid.'/members/'.$uid );
        }
    }
    $result = $facebook->api
    (
        '/?batch=' . Jsphon::encode($in_requests),
        'POST',
        array('access_token' => $access_token)
    );
}else{
    //何もしない
}

//削除
if( $count_friendlist_diff_out > 0){
    foreach ($friendlist_diff_out as $flid => $value){
        if(is_numeric($flid)){
            $out_requests[] = array( 'method' =>'DELETE', 'relative_url' =>'/'.$flid.'/members/'.$uid );
        }
    }

    $result = $facebook->api
    (
        '/?batch=' . Jsphon::encode($out_requests),
        'POST',
        array('access_token' => $access_token)
    );
}
?>