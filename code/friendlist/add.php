<?php
require_once('fw/prepend.php');
$flid = $con->base->getPath('flid',false);
if(isset($_POST['add_friendlist_name']) && strlen($_POST['add_friendlist_name']) > 0){
    $friendlist = $facebook->api(array('method' => 'fql.query',
                                   'query' =>'SELECT flid, owner, name FROM friendlist WHERE owner = me() AND name = "'.$_POST['add_friendlist_name'].'"',
                                   'access_token' =>$access_token,
                                   ));
    //リストにない場合だけ
    if(count($friendlist) == 0){
        $result = $facebook->api('/me/friendlists', 'POST', array(
        'name'      => $_POST['add_friendlist_name'],
        'access_token' => $access_token
        ));
    }
}

if(isset($result['id'])){
    $con->safeExitRedirect('friendlist/view/flid/'.$result['id'],TRUE);
}elseif(is_numeric($flid)){
    $con->safeExitRedirect('friendlist/view/flid/'.$flid,TRUE);
}else{
    $con->safeExitRedirect('',TRUE);
}



?>