<?php
require_once('fw/prepend.php');
if(!$con->isPost) $con->safeExitRedirect('',TRUE);

$flid = $con->base->getPath('flid',TRUE);
if(!is_numeric($flid)) $con->safeExitRedirect('',TRUE);

$user = auth($facebook,'/friendlist/view/flid/'.$flid);

//どっちもない
if(!isset($_COOKIE[IN_IDS]) && !isset($_COOKIE[NEW_IN_IDS])){
    $con->safeExitRedirect('friendlist/view/flid/'.$flid,TRUE);
}

//blankのままPOSTしてきた。本来はありえない。直POSTか
if(isset($_COOKIE[NEW_IN_IDS]) && $_COOKIE[NEW_IN_IDS] == 'blank'){
    require_once('fw/errorManager.php');
    errorManager::throwError(E_CMMN_UNEXPEDTED_ERROR);
}

//newがある場合は優先
if(isset($_COOKIE[NEW_IN_IDS])){
    $in_ids = explode(',',$_COOKIE[NEW_IN_IDS]);
}else{
    $in_ids = explode(',',$_COOKIE[IN_IDS]);
}

if( !is_array($in_ids) ){
    $con->safeExitRedirect('friendlist/view/flid/'.$flid,TRUE);
}

//$time_start = microtime(true); 

//追加
foreach ($in_ids as $key => $uid){
    if(is_numeric($uid)){
        $requests[] = array( 'method' =>'POST', 'relative_url' =>'/'.$flid.'/members/'.$uid );
    }
}
$result = $facebook->api
(
    '/?batch=' . Jsphon::encode($requests),
    'POST',
    array('access_token' => $access_token)
);


/*$time_end = microtime(true);  
$time = $time_end - $time_start;  
print $time.'秒';  
die();*/

setcookie(IN_IDS,'',time() - 3600,"/");
setcookie(NEW_IN_IDS,'',time() - 3600,"/");
$con->safeExitRedirect('friendlist/view/flid/'.$flid,TRUE);
?>