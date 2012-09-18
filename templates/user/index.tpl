<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF8">

<link type="text/css" href="/css/user_ingrid.css" rel="stylesheet" media="all" />
<link type="text/css" href="/css/list.css" rel="stylesheet" media="all" />
<link type="text/css" href="/css/jquery.treeview.css" rel="stylesheet" media="all" />
<link rel="stylesheet" href="/css/colorbox.css" />

<script type="text/javascript" charset="utf-8" src="/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/js/jquery.ingrid.js?{$tail_number}"></script>
<script src="/js/jquery.colorbox.js"></script>
<script type="text/javascript" src="/js/spin.min.js"></script>
<script type="text/javascript" src="/js/fs.js?{$tail_number}"></script>


<script type="text/javascript">
var _ingrid_table1_0_total = 'def';
var _ingrid_table2_0_total = 'def';
var _ingrid_table3_0_total = 'def';
var is_user = true;
$(document).ready(
    function() {ldelim}
        $("#table3").ingrid({ldelim}
            height:{$user_height|default:440},
            colWidths: [680],
            rowSelection: false,
            url: '/user/remote',
            initialLoad: false,
            rowClasses: ['grid-row-style1','grid-row-style1','grid-row-style2','grid-row-style1','grid-row-style1','grid-row-style3'],
            sorting: false,
            paging: true,
            totalRecords: {$count_all_friend|default:0},
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
            form_handle('index',true);
            document.getElementById('add').disabled = true;
            // spin実行
            document.getElementById('add_spin').style.display='block';
            var spinner = new Spinner(opts).spin(target);
            $('#add_form').attr('action', '/friendlist/add');
            $('#add_form').submit();
        {rdelim}
    {rdelim});
{rdelim});

$(document).ready(function(){ldelim}
    $(".cb1").colorbox();
{rdelim});
var is_colorbox_ob = false;
</script>

<title>{$smarty.const.APP_NAME}</title>
</head>
<body class="bg">
{include file="include/common/headbar.inc"}
<table width="100%" cellpadding="5" cellspacing="0" style="border-collapse: collapse;" bgcolor="#ffffff">
    <tbody>
        <tr valign="top">
            <td class="wrap-friendlist-select">
                {include file="include/common/friendlist.inc"}
                {include file="include/common/search.inc"}
            </td>
        </tr>

        <tr valign="top">
            <td width="90%" id="tree_view_right" class="wrap-friendlist-select-bottom">
                <div class="friendlist-name"><img src="/img/help.png">任意の友達をクリックして、選択した友達リストに一括追加、削除できます。</div>
            <div class="left_grid">
                <table id="table3">
                 <thead>
                  <tr>
                   <th>友達一覧</th>
                  </tr>
                 </thead>
                 <tbody>
                 <tr><td>
                    {if $all_friend}
                    {foreach from=$all_friend key="key" item="value" name="all_friend"}
                        <span class="grid-user"><a class='cb1' href="/user/view/uid/{$value.uid}" title="一度に更新(追加、削除)できるのは50個までです。"><img src="{$value.pic_square}" width="50" height="50" /><br />{$value.name}</a></span>
                    {/foreach}
                    {else}
                    表示する友達がありません
                    {/if}
                </td></tr>
                 </tbody>
                </table>
            </div>
            </td>
        </tr>
    </tbody>
</table>
<div id="jquery-ajax"></div>
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
var target = $('.add_spin')
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
</script>
{/literal}
    {include file="include/common/footer.inc"}
  </body>
</html>