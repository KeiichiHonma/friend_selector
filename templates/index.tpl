<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF8">
<script type="text/javascript" charset="utf-8" src="/js/jquery-1.7.2.min.js"></script>
<link type="text/css" href="/css/list.css" rel="stylesheet" media="all" />
<link type="text/css" href="/css/jquery.treeview.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="/js/spin.min.js"></script>
<script type="text/javascript" src="/js/fs.js"></script>
<script type="text/javascript">
$(function(){ldelim}
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
            <ul class="explain">
            <li>友達リストから複数の友達を選択して一括で追加、削除する場合<br />友達リストを使い始める時、あるいは友達リストの大きな変更時等、複数人を一気に変更する場合<br /><img src="/img/explain/ex2.jpg"></li>
            </ul>
            <div class="line">&nbsp;</div>
            </td>
        </tr>
        <tr valign="top">
            <td class="wrap-friendlist-select-middle">
                <table class="search">
                    <tr>
                        <td class="arrow"><img src="/img/user_go.png"></td>
                        <td><a href="/user/">友達から友達リストを選ぶ</a></td>
                    </tr>
                </table>
                <ul class="explain">
                <li>任意の友達から複数の友達リストを選択して一括で追加、削除する場合<br />後日、友達になった方を友達リストに振り分ける場合に使用します。<br /><img src="/img/explain/ex3.jpg"></li>
                </ul>            <div class="line">&nbsp;</div>
            </td>
        </tr>
        <tr valign="top">
            <td width="90%" id="tree_view_right" class="wrap-friendlist-select-bottom">
                <div class="friendlist-name"><img src="/img/help.png">{$smarty.const.APP_NAME}について</div>
                <ul class="explain">
                <li>facebookの友達リストを使いやすくしたアプリです。<br />友達リストとは近況アップデートの公開範囲として利用できる便利なリストです。<br /><img src="/img/explain/ex1.gif"></li>
                <li>facebook側では提供していない<strong>一括追加、一括削除機能を提供</strong>している友達リスト管理アプリです。<br />また、絞り込み条件を指定する機能もあるため、任意の友達まですぐに辿り着くことが可能です。</li>
                <li>不明な動作等ありましたら連絡ください。<a href="https://www.facebook.com/keiichi.honma2" target="_parent">81@Keiichi Honma</a></li>
                </ul>
            </td>
        </tr>
    </tbody>
</table>
{literal}
<script type="text/javascript">
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