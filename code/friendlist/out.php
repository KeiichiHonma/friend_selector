<?php
require_once('fw/prepend.php');
$flid = $con->base->getPath('flid',TRUE);

$user = auth($facebook,'/friendlist/view/flid/'.$flid);

if(!is_numeric($flid)) $con->safeExitRedirect('friendlist/view/flid/'.$flid,TRUE);

//どっちもない
if(!isset($_COOKIE[OUT_IDS]) && !isset($_COOKIE[NEW_OUT_IDS])){
    $con->safeExitRedirect('friendlist/view/flid/'.$flid,TRUE);
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

//追加
foreach ($out_ids as $key => $uid){
    if(is_numeric($uid)){
        $result = $facebook->api('/'.$flid.'/members/'.$uid, 'DELETE', array(
        'access_token' => $access_token
        ));
    }
}

setcookie(OUT_IDS,'',time() - 3600,"/");
setcookie(NEW_OUT_IDS,'',time() - 3600,"/");
$con->safeExitRedirect('friendlist/view/flid/'.$flid,TRUE);
?>