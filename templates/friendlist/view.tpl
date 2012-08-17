<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF8">

<script type="text/javascript" charset="utf-8" src="/js/jquery-1.7.2.min.js"></script>

<!-- include jquery lib -->
<script type="text/javascript" src="/js/jquery.js"></script>
<!-- include ingrid lib -->
<script type="text/javascript" src="/js/jquery.ingrid.js"></script>
<!-- ingrid default stylesheet -->
<link type="text/css" href="/css/ingrid.css" rel="stylesheet" media="all" />
<!-- to make ingrid save her state (selected rows, page number, column sort & direction); just include the jQ cookie plugin -->
<script type="text/javascript" src="/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/js/spin.min.js"></script>
<script type="text/javascript" src="/js/fs.js"></script>
<script type="text/javascript">
var _ingrid_table1_0_total = 'def';
var _ingrid_table2_0_total = 'def';
$(document).ready(
    function() {ldelim}
        $("#table1").ingrid({ldelim}
            url: '/friendlist/remote_in/flid/{$flid}',
            height: 350,
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
            height: 350,
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
    $('#add').click(function() {ldelim}
        form_stop();
        if( document.getElementById('add_friendlist_name').value != ''){ldelim}
            
            // spin実行
            document.getElementById('add_view_spin').style.display='block';
            var spinner = new Spinner(opts).spin(target2);
            $('#add_form').attr('action', '/friendlist/add/flid/{$flid}');
            $('#add_form').submit();
        {rdelim}
    {rdelim});
    $('#out').click(function() {ldelim}
        _ingrid_table1_0_rows_ids = jQuery.cookie( '_ingrid_table1_0_rows' );
        if(_ingrid_table1_0_rows_ids != null && _ingrid_table1_0_rows_ids != ''){ldelim}
            form_stop();
            // spin実行
            document.getElementById('spin').style.display='block';
            var spinner = new Spinner(opts).spin(target);
            $('#out_form').attr('action', '/friendlist/out/flid/{$flid}');
            $('input[type=hidden][name="out_ids"]').val(_ingrid_table1_0_rows_ids);
            $('#out_form').submit();
        {rdelim}
    {rdelim});
    $('#in').click(function() {ldelim}
        _ingrid_table2_0_rows_ids = jQuery.cookie( '_ingrid_table2_0_rows' );
        if(_ingrid_table2_0_rows_ids != null && _ingrid_table2_0_rows_ids != ''){ldelim}
            form_stop();
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

<link type="text/css" href="/css/list.css" rel="stylesheet" media="all" />

<title>フレンドセレクター</title>
<body class="bg">
{include file="include/common/headbar.inc"}
<table width="100%" cellpadding="5" cellspacing="0" style="border-collapse: collapse;" bgcolor="#ffffff">
    <tbody>
    <tr valign="top">
        <td class="wrap-friendlist-select">
            {include file="include/common/friendlist.inc"}
            <div id="search">
            <div class="line">&nbsp;</div>
            <table class="search">
                <tr>
                    <td>
                        <fieldset>
                        <legend><img src="/img/male.png" width="16" height="16">性別<img src="/img/female.png" width="16" height="16"></legend>
                        <table>
                        <tr><td nowrap class="radio"><form id="sex_form"><label><input type="radio" id="sex" name="sex" value="open" checked>指定しない</label><br /><label><input type="radio" id="sex" name="sex" value="male">男性</label><br /><label><input type="radio" id="sex" name="sex" value="female">女性</label></form></td></tr>
                        </table>
                        </fieldset>
                    </td>
                    <td class="arrow"><img src="/img/arrows.gif"></td>
                    <td>
                        <fieldset>
                            <legend><img src="/img/heart.png" width="16" height="16">交際ステータス<img src="/img/heart.png" width="16" height="16"></legend>
                            <table>
                            <tr><td colspan="3" class="radio"><form id="relationship_form"><label><input type="radio" id="relationship" name="relationship" value="open" checked>指定しない</label></td></tr>
                            {foreach from=$relationship_status key="key" item="value" name="relationship_status"}
                                <tr>
                                {foreach from=$value key="key2" item="value2" name="relationship_status2"}
                                    <td nowrap class="radio"><label><input type="radio" id="relationship" name="relationship" value="{$key2}">{$value2}</label></td>
                                {/foreach}
                                </tr>
                            {/foreach}
                            </form>
                            </table>
                        </fieldset>
                    </td>
                    {if $friendlist}
                    <td class="arrow"><img src="/img/arrows.gif"></td>
                    <td>
                        <fieldset>
                        <legend><img src="/img/group.png" width="16" height="16">友達リストで絞り込み<img src="/img/zoom.png" width="16" height="16"></legend>
                        <form id="flid_form"><select id="flid" name="flid">
                        <option value="open">指定しない</option>
                        {foreach from=$friendlist key="key" item="value" name="friendlist"}
                            {if strcasecmp($value.flid,$flid) != 0}
                            <option value="{$value.flid}">{$value.name|makeFriendlistName:28}</option>
                            {/if}
                        {/foreach}
                        </select></form>
                        </fieldset>
                        {if $grouplist}
                        <fieldset>
                        <legend>所属グループで絞り込み</legend>
                        <select id="gid" name="gid">
                        <option value="open">指定しない</option>
                        {foreach from=$grouplist key="key" item="value" name="grouplist"}
                            <option value="{$value.gid}">{$value.name}</option>
                        {/foreach}
                        </select>
                        </fieldset>
                        {/if}
                    </td>
                    {/if}
                </tr>
            </table>
            </div>
        </td>
    </tr>
<tr>
  <td width="90%" id="tree_view_right" class="wrap-friendlist-select-bottom">
    <div class="line">&nbsp;</div>
        <p class="grid_block">
            <div class="left_grid">
            <div class="friendlist-sec-name"><img src="/img/group_add.png" border="0">{$friendlist.$flid.name|makeFriendlistName:24}に入っている友達</div>
            <table id="table1">
             <thead>
              <tr>
               <th>名前</th>
               <th>性別</th>
               <th>年齢</th>
               <th>交際</th>
              </tr>
             </thead>
             <tbody>
                {if $friendlist_friend}
                {foreach from=$friendlist_friend key="key" item="value" name="friendlist_friend"}
                    <tr id="{$value.uid}"><td class="first"><img src="{$value.pic_square}" width="25" height="25" />{$value.name}</td><td>{$value.sex|makeSex}</td><td>{$value.birthday_date|makeAge}</td><td>{$value.relationship_status|makeRelationshipStatus}</td></tr>
                {/foreach}
                {else}
                <tr><td>表示する友達がありません</td><td></td><td></td><td></td></tr>
                {/if}
             </tbody>
            </table>
            </div>
            
            <div class="center_grid">
                <div id="spin" class="spin"></div>
                <p><form id="in_form" name="in_form" method="post" action="#"><input id="in" type="button" value="←追加" /></form></p>
                <p><form id="out_form" name="out_form" method="post" action="#"><input id="out" type="button" value="削除→" /></form></p>
            </div>
            
            <div class="right_grid">
            <div class="friendlist-sec-name"><img src="/img/group_delete.png" border="0">{$friendlist.$flid.name|makeFriendlistName:24}に入っていない友達</div>
            <table id="table2">
             <thead>
              <tr>
               <th>名前</th>
               <th>性別</th>
               <th>年齢</th>
               <th>交際</th>
              </tr>
             </thead>
             <tbody>
                {if $friendlist_diff_friend}
                {foreach from=$friendlist_diff_friend key="key" item="value" name="friendlist_diff_friend"}
                    <tr id="{$value.uid}"><td class="first"><img src="{$value.pic_square}" width="25" height="25" />{$value.name}</td><td>{$value.sex|makeSex}</td><td>{$value.birthday_date|makeAge}</td><td>{$value.relationship_status|makeRelationshipStatus}</td></tr>
                {/foreach}
                {else}
                <tr><td>表示する友達がありません</td><td></td><td></td><td></td></tr>
                {/if}
             </tbody>
            </table>
            </div>
        </p>
    </td>
    </tr>
    </tbody>
    </table>
{literal}
<script type="text/javascript">
// 以下はデフォルト値
var opts = {
  lines:   20,    // 回転する線の本数
  length:  30,     // 線の長さ
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