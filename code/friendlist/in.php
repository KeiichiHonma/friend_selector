<?php
require_once('fw/prepend.php');
$flid = $con->base->getPath('flid',TRUE);

$user = auth($facebook,'/friendlist/view/flid/'.$flid);

if(!isset($_COOKIE[IN_IDS]) || !is_numeric($flid)) $con->safeExitRedirect('friendlist/view/flid/'.$flid,TRUE);

$in_ids = explode(',',$_COOKIE[IN_IDS]);
if( !is_array($in_ids) ){
    $con->safeExitRedirect('friendlist/view/flid/'.$flid,TRUE);
}

//追加
foreach ($in_ids as $key => $uid){
    if(is_numeric($uid)){
        $result = $facebook->api('/'.$flid.'/members/'.$uid, 'POST', array(
        'access_token' => $access_token
        ));
    }

}

setcookie(IN_IDS,'',time() - 3600, "/");

$con->safeExitRedirect('friendlist/view/flid/'.$flid,TRUE);
?>