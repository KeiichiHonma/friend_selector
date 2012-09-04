<?php
header("Content-type: text/html; charset=utf-8");
header("P3P: CP='UNI CUR OUR'");

require_once('fw/container.php');
$con = new container();

//app id
if($con->isDebug){
    //hermes
    $appId = '284194714999778';
    $secret = '91e9f8ae5a023acdb5599c78a08de6ea';
}else{
    //sakura
    $appId = '350725135008165';
    $secret = '28196746b471ad2d988d3028dd70f4fc';
}
$con->t->assign('appId',$appId);

//permissions facebook側からの戻り値と合わせるため形が特殊
//$permissions[] = array('read_friendlists'=>'1','manage_friendlists'=>'1','user_birthday'=>'1','friends_birthday'=>'1','user_groups'=>'1','friends_groups'=>'1','user_relationships'=>'1','friends_relationships'=>'1');
//$permissions_comma = implode(',',array_keys($permissions[0]));

require 'facebook-facebook-php-sdk/src/facebook.php';
$facebook = new Facebook(array(
  'appId'  => $appId,
  'secret' => $secret,
  'cookie' => true, // enable optional cookie support
));

function auth($facebook,$path = '/',$isMost = false){
    global $con;
    $user = $facebook->getUser();

    if (!$user|| $isMost == true) {
        $par = array(
            //'scope' => 'publish_stream,read_friendlists,manage_friendlists,user_birthday,friends_birthday,user_likes,friends_likes',
            'scope' => $con->permissions_comma,
            'redirect_uri' => FSURLSSL.$path
        );
        $fb_login_url = $facebook->getLoginUrl($par);
        //header("Location: ".$fb_login_url);
        //die();
        echo "<script type='text/javascript'>top.location.href = '$fb_login_url';</script>";
        exit();
    }

    return $user;
}

//設定/////////////////////////////////////
//util
require_once('friendlist/util.php');

//user情報
$column = array('uid','name','pic_square','sex','relationship_status','birthday_date','friend_count');
$column_comma = implode(',',$column);

//ページング
$rows = 50;//最大
$user_rows = 50;//最大
$user_height = 450;
$cols = 5;//name pic_squareは同一カラム

//設定
$sex = array
(
'male'=>'男性',
'female'=>'女性'
);
$con->t->assign('sex',$sex);

$relationship_status = array
(
'single'=>'独身',
'in_a_relationship'=>'交際中',
'engaged'=>'婚約中',
'married'=>'既婚',
'complicated'=>'複雑な関係',
'in_an_open_relationship'=>'オープンな関係',
'widowed'=>'配偶者と死別',
'separated'=>'別居中',
'divorced'=>'離婚'
);
$con->t->assign('relationship_status',array_chunk($relationship_status,3,true));
$con->readyPostCsrf();
$con->checkPostCsrf();

?>