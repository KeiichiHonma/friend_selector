<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF8">
<link type="text/css" href="/css/ingrid.css" rel="stylesheet" media="all" />
<link type="text/css" href="/css/list.css" rel="stylesheet" media="all" />

<script type="text/javascript" charset="utf-8" src="/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/js/jquery.ingrid.js"></script>
<script type="text/javascript" src="/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/js/spin.min.js"></script>
<script type="text/javascript" src="/js/fs.js"></script>

<script type="text/javascript">
var _ingrid_table1_0_total = 'def';
var _ingrid_table2_0_total = 'def';
var _ingrid_table3_0_total = 'def';
var is_user = false;
$(document).ready(
    function() {ldelim}
        $("#table1").ingrid({ldelim}
            url: '/friendlist/remote_in/flid/{$flid}',
            height: grid_height,
            initialLoad: false,
            rowClasses: ['grid-row-style1','grid-row-style1','grid-row-style2','grid-row-style1','grid-row-style1','grid-row-style3'],
            sorting: false,
            paging: true,
            totalRecords: {$count_friendlist_friend|default:0},
            extraParams: {ldelim}sessid : 'some_session_token_here'{rdelim}
        {rdelim});
    {rdelim}
);
$(document).ready(
    function() {ldelim}
        $("#table2").ingrid({ldelim}
            url: '/friendlist/remote_out/flid/{$flid}',
            height: grid_height,
            initialLoad: false,
            rowClasses: ['grid-row-style1','grid-row-style1','grid-row-style2','grid-row-style1','grid-row-style1','grid-row-style3'],
            sorting: false,
            paging: true,
            totalRecords: {$count_friendlist_diff_friend|default:0},
            extraParams: {ldelim}sessid : 'some_session_token_here'{rdelim}
        {rdelim});
    {rdelim}
);

$(function(){ldelim}
    $('#reset').click(function() {ldelim}
        form_reset();
    {rdelim});
    $('#add').click(function() {ldelim}
        if( document.getElementById('add_friendlist_name').value != ''){ldelim}
            form_handle('view',true);
            // spin実行
            document.getElementById('add_view_spin').style.display='block';
            var spinner = new Spinner(opts).spin(target2);
            $('#add_form').attr('action', '/friendlist/add/flid/{$flid}');
            $('#add_form').submit();
        {rdelim}
    {rdelim});
    $('#out').click(function() {ldelim}
        _ingrid_table1_0_rows_ids = jQuery.cookie( table1_cookie_name );
        _ingrid_table1_0_new_rows_ids = jQuery.cookie( table1_new_cookie_name );

        if(_ingrid_table1_0_new_rows_ids != 'blank' && _ingrid_table1_0_rows_ids != null && _ingrid_table1_0_rows_ids != ''){ldelim}
            form_handle('view',true);
            // spin実行
            document.getElementById('spin').style.display='block';
            var spinner = new Spinner(opts).spin(target);
            $('#out_form').attr('action', '/friendlist/out/flid/{$flid}');
            $('input[type=hidden][name="out_ids"]').val(_ingrid_table1_0_rows_ids);
            $('#out_form').submit();
        {rdelim}
    {rdelim});
    $('#in').click(function() {ldelim}
        _ingrid_table2_0_rows_ids = jQuery.cookie( table2_cookie_name );
        _ingrid_table2_0_new_rows_ids = jQuery.cookie( table2_new_cookie_name );
        
        if(_ingrid_table2_0_new_rows_ids != 'blank' && _ingrid_table2_0_rows_ids != null && _ingrid_table2_0_rows_ids != ''){ldelim}
            form_handle('view',true);
            // spin実行
            document.getElementById('spin').style.display='block';
            var spinner = new Spinner(opts).spin(target);
            $('#in_form').attr('action', '/friendlist/in/flid/{$flid}');
            $('input[type=hidden][name="in_ids"]').val(_ingrid_table2_0_rows_ids);
            $('#in_form').submit();
        {rdelim}
    {rdelim});
{rdelim});
</script>
<title>フレンドセレクター</title>
</head>
<body class="bg">
{include file="include/common/headbar.inc"}
<table width="100%" cellpadding="5" cellspacing="0" style="border-collapse: collapse;" bgcolor="#ffffff">
    <tbody>
    <tr valign="top">
        <td class="wrap-friendlist-select">
            {include file="include/common/friendlist.inc"}
                <table class="search">
                    <tr>
                        <td class="arrow"><img src="/img/user_go.png"></td>
                        <td><a href="/user/">友達から友達リストを選ぶ</a>(任意の友達から複数の友達リストを選択して一括で追加、削除する場合)</td>
                    </tr>
                </table>
            {include file="include/common/search.inc"}
        </td>
    </tr>
<tr>
  <td width="90%" class="wrap-friendlist-select-bottom">
    <div class="line">&nbsp;</div>
        <p class="grid_block">
            <div class="left_grid">
            <div class="friendlist-sec-name"><img src="/img/group_add.png" border="0">「{$friendlist.$flid.name|makeFriendlistName:24}」の友達</div>
                <table id="table1">
                 <thead>
                  <tr>
                   <th><input type="checkbox" id="allCheck1" class="allCheck1" {$is_allCheck1} {if !$friendlist_friend}disabled{/if} /></th>
                   <th>名前</th>
                   <th>性別</th>
                   <th>年齢</th>
                   <th>交際</th>
                  </tr>
                 </thead>
                 <tbody>
                    {if $friendlist_friend}
                    {foreach from=$friendlist_friend key="key" item="value" name="friendlist_friend"}
                        <tr id="{$value.uid}" name="tr_1"><td><input type="checkbox"  name="chk1[{$smarty.foreach.friendlist_friend.iteration}]" /></td><td class="first"><img src="{$value.pic_square}" width="20" height="20" />{$value.name}</td><td>{$value.sex|makeSex}</td><td>{$value.birthday_date|makeAge}</td><td>{$value.relationship_status|makeRelationshipStatus}</td></tr>
                    {/foreach}
                    {else}
                    <tr id="blank1"><td><img src="/img/exclamation.png" border="0"></td><td>表示する友達がありません</td><td></td><td></td><td></td></tr>
                    {/if}
                 </tbody>
                </table>
            </div>
            
            <div id="center_grid" class="center_grid">
                <div id="spin" class="spin"></div>
                <p><form id="in_form" name="in_form" method="post" action="#"><input type="hidden" name="fs_method" value="post" /><input type="hidden" name="csrf_ticket" value="{$csrf_ticket}" /><input id="in" type="button" value="←追加" /></form></p>
                <p><form id="out_form" name="out_form" method="post" action="#"><input type="hidden" name="fs_method" value="post" /><input type="hidden" name="csrf_ticket" value="{$csrf_ticket}" /><input id="out" type="button" value="削除→" /></form></p>
            </div>
            
            <div class="right_grid">
            <div class="friendlist-sec-name"><img src="/img/group_delete.png" border="0">「{$friendlist.$flid.name|makeFriendlistName:24}」に入っていない友達</div>
                <table id="table2">
                 <thead>
                  <tr>
                   <th>
                   <input type="checkbox" id="allCheck2" class="allCheck2" {$is_allCheck2} {if !$friendlist_diff_friend}disabled{/if} />
                   </th>
                   <th>名前</th>
                   <th>性別</th>
                   <th>年齢</th>
                   <th>交際</th>
                  </tr>
                 </thead>
                 <tbody>
                    {if $friendlist_diff_friend}
                    {foreach from=$friendlist_diff_friend key="key" item="value" name="friendlist_diff_friend"}
                        <tr id="{$value.uid}" name="tr_2"><td><input type="checkbox" name="chk2[{$smarty.foreach.friendlist_diff_friend.iteration}]" /></td><td class="first"><img src="{$value.pic_square}" width="20" height="20" />{$value.name}</td><td>{$value.sex|makeSex}</td><td>{$value.birthday_date|makeAge}</td><td>{$value.relationship_status|makeRelationshipStatus}</td></tr>
                    {/foreach}
                    {else}
                    <tr id="blank2"><td><img src="/img/exclamation.png" border="0"></td><td>表示する友達がありません</td><td></td><td></td><td></td></tr>
                    {/if}
                 </tbody>
                </table>
            </div>
        </p>
    </td>
    </tr>
    </tbody>
    </table>
<script type="text/javascript" src="/js/all.js"></script>
<script type="text/javascript" src="/js/resize_screen.js"></script>
{literal}
<script type="text/javascript">
//init

$( 'input[id="sex1"]:radio' ).attr('checked', true);
$( 'input[id="relationship00"]:radio' ).attr('checked', true);

// 以下はデフォルト値
var opts = {
  lines:   20,    // 回転する線の本数
  length:  20,     // 線の長さ
  width:   4,     // 線の太さ
  radius:  25,    // 線の丸み
  color: ' #000', // 線の色　#rgb or #rrggbb
  speed:   1,     // 1回転に要する時間 秒
  trail:   60,    // Afterglow percentage
  shadow:  false, // 線に影を付ける場合、true
  hwaccel: false  // Whether to use hardware acceleration
};

// アニメーションを挿入する要素
var target = $('.spin')
             .css({
                height : 200,
                width  : 200,
                //border : '1px solid #000000',
                //"background-color" : '#e7ebf2',
                "display":"none",
                padding: 10
             })
             .get(0);
            // spin実行
            //var spinner = new Spinner(opts).spin(target);
var target2 = $('.add_view_spin')
             .css({
                height : 200,
                width  : 200,
                //border : '1px solid #000000',
                //"background-color" : '#e7ebf2',
                "display":"none",
                padding: 10
             })
             .get(0);
            // spin実行
            //var spinner = new Spinner(opts).spin(target2);
</script>
{/literal}
    {include file="include/common/footer.inc"}
  </body>
</html>