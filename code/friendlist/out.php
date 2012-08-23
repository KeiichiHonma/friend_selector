<?php
require_once('fw/prepend.php');
if(!$con->isPost) $con->safeExitRedirect('',TRUE);

$flid = $con->base->getPath('flid',TRUE);
if(!is_numeric($flid)){
    $con->safeExitRedirect('',TRUE);
}
$user = auth($facebook,'/friendlist/view/flid/'.$flid);



//どっちもない
if(!isset($_COOKIE[OUT_IDS]) && !isset($_COOKIE[NEW_OUT_IDS])){
    $con->safeExitRedirect('friendlist/view/flid/'.$flid,TRUE);
}

//blankのままPOSTしてきた。本来はありえない。直POSTか
if(isset($_COOKIE[NEW_OUT_IDS]) && $_COOKIE[NEW_OUT_IDS] == 'blank'){
    require_once('fw/errorManager.php');
    errorManager::throwError(E_CMMN_UNEXPEDTED_ERROR);
}

//if(!isset($_COOKIE[OUT_IDS]) || !is_numeric($flid)) $con->safeExitRedirect('friendlist/view/flid/'.$flid,TRUE);

//newがある場合は優先
if(isset($_COOKIE[NEW_OUT_IDS])){
    $out_ids = explode(',',$_COOKIE[NEW_OUT_IDS]);
}else{
    $out_ids = explode(',',$_COOKIE[OUT_IDS]);
}

if( !is_array($out_ids) ){
    $con->safeExitRedirect('friendlist/view/flid/'.$flid,TRUE);
}

//削除
foreach ($out_ids as $key => $uid){
    if(is_numeric($uid)){
        $requests[] = array( 'method' =>'DELETE', 'relative_url' =>'/'.$flid.'/members/'.$uid );
    }
}

$result = $facebook->api
(
    '/?batch=' . Jsphon::encode($requests),
    'POST',
    array('access_token' => $access_token)
);

setcookie(OUT_IDS,'',time() - 3600,"/");
setcookie(NEW_OUT_IDS,'',time() - 3600,"/");
$con->safeExitRedirect('friendlist/view/flid/'.$flid,TRUE);
?>