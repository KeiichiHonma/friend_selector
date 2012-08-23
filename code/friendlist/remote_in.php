<?php
require_once('fw/prepend.php');
$flid = $con->base->getPath('flid',TRUE);

if(isset($_GET['page']) && is_numeric($_GET['page'])){
    $page = $_GET['page'];
    $from = $_GET['page']*$rows - $rows;
    $to   = $rows;
}else{
    $page = 1;
    $from = 0;
    $to   = $rows;
}

//where///////////////////////////////////////////////
$andQuery = '';
$andArray = array();

//sex////////////////////
if(isset($_GET['sex']) && strlen($_GET['sex']) > 0){
    if($_GET['sex'] == 'male'){
        $andArray['sex'] = 'sex = \'male\'';
    }elseif($_GET['sex'] == 'female'){
        $andArray['sex'] = 'sex = \'female\'';
    }elseif($_GET['sex'] == 'open'){
        unset($andArray['sex']);
    }
}

//relationship//////////
/*'Single'=>'独身',
'In a relationship'=>'交際中',
'Engaged'=>'婚約中',
'Married'=>'既婚',
'It\'s complicated'=>'複雑な関係',
'In an open relationship'=>'オープンな関係',
'Widowed'=>'配偶者と死別',
'Separated'=>'別居中',
'Divorced'=>'離婚'


'single'=>'独身',
'in_a_relationship'=>'交際中',
'engaged'=>'婚約中',
'married'=>'既婚',
'complicated'=>'複雑な関係',
'open'=>'オープンな関係',
'widowed'=>'配偶者と死別',
'separated'=>'別居中',
'divorced'=>'離婚'*/
if(isset($_GET['relationship']) && strlen($_GET['relationship']) > 0){
    $relationship = '';
    switch ($_GET['relationship']){
        case 'single':
            $relationship = 'Single';
        break;

        case 'in_a_relationship':
            $relationship = 'In a relationship';
        break;

        case 'engaged':
            $relationship = 'Engaged';
        break;

        case 'married':
            $relationship = 'Married';
        break;

        case 'complicated':
            $relationship = "It's complicated";
        break;

        case 'in_an_open_relationship':
            $relationship = 'In an open relationship';
        break;

        case 'widowed':
            $relationship = 'Widowed';
        break;

        case 'separated':
            $relationship = 'Separated';
        break;

        case 'divorced':
            $relationship = 'Divorced';
        break;
    }

    if($relationship != ''){
        $andArray['relationship'] = 'relationship_status = "'.$relationship.'"';
    }else{
        //unset($andArray['relationship']);
    }
}

if(count($andArray) > 0){
    $andQuery .= implode(' AND ',$andArray);
    $andQuery = ' AND '.$andQuery;
}

//order by//////////////////////////
$order = '';
if(isset($_GET['sort'])){
    switch ($_GET['sort']){
        case 0:
            $order .= 'name';
        break;
        case 1:
            $order .= 'name';
        break;
        
        default:
            $order .= 'uid';
    }
}else{
    $order .= 'uid';
}

if(isset($_GET['dir'])){
    if($_GET['dir'] == 'asc'){
        $order .= ' ASC';
    }else{
        $order .= ' DESC';
    }
}else{
    $order .= ' ASC';
}

//list 追加でリストで絞り込み
$isList = false;
if(isset($_GET['flid']) && is_numeric($_GET['flid'])){
    $isList = true;
}

//group 追加でグループで絞り込み
$isGroup = false;
if(isset($_GET['gid']) && is_numeric($_GET['gid'])){
    $isGroup = true;
}

//指定リスト内の全ての友達/////////////////////////////////////////////

//query番号のチェック
if($isList && $isGroup){
    $list_query_no = 'q1';
    $group_query_no = 'q2';
    $use_query_no = 'q2';
    $user_query_no = 'q3';
    $result_no = 2;
    $facebook->api_setting = array('','','uid');//index変更
}elseif($isList && !$isGroup){
    $list_query_no = 'q1';
    $use_query_no = 'q1';
    $user_query_no = 'q2';
    $result_no = 1;
    $facebook->api_setting = array('','uid');//index変更
}elseif(!$isList && $isGroup){
    $list_query_no = 'q1';
    $group_query_no = 'q2';
    $use_query_no = 'q2';
    $user_query_no = 'q3';
    $result_no = 2;
    $facebook->api_setting = array('','','uid');//index変更
}

//query生成
if($isList){
    $queries[$list_query_no] = 'SELECT uid,flid FROM friendlist_member WHERE uid in ( SELECT uid FROM friendlist_member WHERE flid = '.$flid.' ) AND flid = '.$_GET['flid'];
}else{
    //$queries[$list_query_no] = 'SELECT uid,flid FROM friendlist_member WHERE uid in ( SELECT uid FROM friendlist_member WHERE flid = '.$flid.' )';
    $queries[$list_query_no] = 'SELECT uid,flid FROM friendlist_member WHERE flid = '.$flid;
}

if($isGroup){
    $queries[$group_query_no] = 'select uid,gid from group_member WHERE gid = '.$_GET['gid'].' AND uid != me() AND uid in ( SELECT uid FROM #'.$list_query_no.' )';
}

if($isList || $isGroup){
    $queries[$user_query_no] = 'SELECT '.$column_comma.' FROM user WHERE uid in ( SELECT uid FROM #'.$use_query_no.' ) '.$andQuery.' ORDER BY '.$order;
    ksort($queries);

    $facebook->is_multiquery = TRUE;
    $result = $facebook->api
    (
        array
        (
            'method'=>'fql.multiquery',
            'queries'=>$queries,
            'access_token'=>$access_token
        )
    );
    $friendlist_friend = $result[$result_no]['fql_result_set'];
}else{
    $facebook->api_setting = array('uid');//index変更
    $friendlist_friend = $facebook->api(array('method' => 'fql.query',
                                   'query' =>'SELECT '.$column_comma.' FROM user WHERE uid in ( SELECT uid FROM friendlist_member WHERE flid = '.$flid.' ) '.$andQuery.' ORDER BY '.$order,
                                   'access_token' =>$access_token,
                                   ));
}
$index = $page-1;
$count_friendlist_friend = count($friendlist_friend);

$is_zero = false;
if( $count_friendlist_friend != 0){
    //クッキー削除
    if(isset($_COOKIE[OUT_IDS])){
        $out_ids = explode(',',$_COOKIE[OUT_IDS]);
        $new_out_ids = array();
        foreach ($friendlist_friend as $key => $value){
            if(in_array($value['uid'],$out_ids)){
                $new_out_ids[] = $value['uid'];
            }
        }
        $new_out_ids_string = '';
        if(count($new_out_ids) > 0){
            $new_out_ids_string = implode(',',$new_out_ids);
            setcookie(NEW_OUT_IDS,$new_out_ids_string,0,'/');
        }else{
            //表示する友達はいるが、クッキーの中にある友達以外であった場合。なのでblankではない
            setcookie(NEW_OUT_IDS,'blank',0,'/');
        }
    }
    $friendlist_friend_chunk = array_chunk($friendlist_friend,$rows,true);
    //描画の時は数が減る可能性あり
    $rows = count($friendlist_friend_chunk[$index]);
    if($rows != 0){
        //html
        print '<table>';
        print '<tbody>';
        $i = 1;
        foreach ($friendlist_friend_chunk[$index] as $uid => $value){
            print '<tr id="'.$uid.'" name="tr_1">';
            print '<td><input type="checkbox" name="chk1['.$i.']" /></td>';
            print '<td class="first"><img src='.'"'.$value['pic_square'].'" width="25" height="25" />'.$value['name'].'</td><td>'.util::makeSexText($value['sex']).'</td><td>'.util::makeAgeText($value['birthday_date']).'</td><td>'.util::makeRelationshipStatusText($value['relationship_status']).'</td>';
            print '</tr>';
            $i++;
        }
        print '<script type="text/javascript">_ingrid_table1_0_total = '.$count_friendlist_friend.';isSearch = true;form_handle(\'view\',false);</script>';
        //table1側 全チェック確認 //NEW_OUT_IDSでチェック
        if(util::isAllCheckbox($friendlist_friend_chunk[$index],$new_out_ids_string) == 'checked'){
            print '<script type="text/javascript">$(function(){doAllCheck(1);});</script>';
        }else{
            print '<script type="text/javascript">$(function(){resetAllCheck(1);});</script>';
        }
        print '</tbody>';
        print '</table>';
    }else{
        $is_zero = true;
    }
}else{
    if(isset($_COOKIE[OUT_IDS])){
        //表示する友達がいないが、クッキーがあるため見えないPOSTが発生するため、blankで処理を止める
        setcookie(NEW_OUT_IDS,'blank',0,'/');
    }
    $is_zero = true;
}
if($is_zero){
    //ない場合
    //html
    print '<table>';
    print '<tbody>';
    print '<tr id="blank1">';
    print '<td><img src="/img/exclamation.png" border="0"></td><td>表示する友達がありません</td><td></td><td></td><td></td>';
    print '</tr>';
    print '<script type="text/javascript">_ingrid_table1_0_total = 0;isSearch = true;document.getElementById(\'allCheck1\').disabled = true;form_handle(\'view\',false);</script>';
    print '<script type="text/javascript">$(function(){resetAllCheck(1);});</script>';
    print '</tbody>';
    print '</table>';
}
?>