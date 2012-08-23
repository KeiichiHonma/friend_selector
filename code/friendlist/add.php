<?php
require_once('fw/prepend.php');
if(!$con->isPost) $con->safeExitRedirect('',TRUE);

$flid = $con->base->getPath('flid',false);
if(isset($_POST['add_friendlist_name']) && strlen($_POST['add_friendlist_name']) > 0){
    //FQL
    $facebook->is_multiquery = true;
    $result = $facebook->api
    (
        array
        (
            'method'=>'fql.multiquery',
            'queries'=>array
            (
                'q1' =>'select '.$con->permissions_comma.' from permissions where uid = me()',
                'q2' =>'SELECT flid, owner, name FROM friendlist WHERE owner = me() AND name = "'.$_POST['add_friendlist_name'].'"'
            ),
            'access_token'=>$access_token
        )
    );
    $con->checkPermissions($result[0]['fql_result_set']);
    $friendlist = $result[1]['fql_result_set'];
    //リストにない場合だけ
    if(count($friendlist) > 0){
        require_once('fw/errorManager.php');
        errorManager::throwError(E_CMMN_FRIENDLIST_SAME);
    }
    $result = $facebook->api('/me/friendlists', 'POST', array(
    'name'      => $_POST['add_friendlist_name'],
    'access_token' => $access_token
    ));
}

if(isset($result['id'])){
    $con->safeExitRedirect('friendlist/view/flid/'.$result['id'],TRUE);
}elseif(is_numeric($flid)){
    $con->safeExitRedirect('friendlist/view/flid/'.$flid,TRUE);
}else{
    $con->safeExitRedirect('',TRUE);
}



?>