<?php
$facebook->api_setting = array('index'=>'flid');//index変更
$friendlist = $facebook->api(array('method' => 'fql.query',
                               'query' =>'SELECT flid, owner, name FROM friendlist WHERE owner = me() ORDER BY flid DESC',
                               'access_token' =>$access_token,
                               ));
$con->t->assign('friendlist',$friendlist);
?>