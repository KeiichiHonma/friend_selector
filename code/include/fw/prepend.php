<?php
header("Content-type: text/html; charset=utf-8");
header("P3P: CP='UNI CUR OUR'");

require_once('fw/container.php');
$con = new container();

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
require 'facebook-facebook-php-sdk/src/facebook.php';
$facebook = new Facebook(array(
  'appId'  => $appId,
  'secret' => $secret
));

//ページ遷移でクッキー削除
if(isset($_GET['c']) && $_GET['c'] == 'd'){
    if(isset($_COOKIE[IN_IDS])) setcookie(IN_IDS,'',-1,'/');
    if(isset($_COOKIE[NEW_IN_IDS])) setcookie(NEW_IN_IDS,'',-1,'/');
    if(isset($_COOKIE[OUT_IDS])) setcookie(OUT_IDS,'',-1,'/');
    if(isset($_COOKIE[NEW_OUT_IDS])) setcookie(NEW_OUT_IDS,'',-1,'/');
}

function auth($facebook,$path = '/'){
    $user = $facebook->getUser();

    if (!$user) {

        $par = array(
            //'scope' => 'publish_stream,read_friendlists,manage_friendlists,user_birthday,friends_birthday,user_likes,friends_likes',
            'scope' => 'read_friendlists,manage_friendlists,user_birthday,friends_birthday,user_groups,friends_groups,user_relationships,friends_relationships',
            'redirect_uri' => FSURL.$path
        );
        $fb_login_url = $facebook->getLoginUrl($par);
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
$cols = 4;//name pic_squareは同一カラム

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
?>