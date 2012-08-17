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
<body class="bg">
{include file="include/common/headbar.inc"}
<table width="100%" cellpadding="5" cellspacing="0" style="border-collapse: collapse;" bgcolor="#ffffff">
    <tbody>
        <tr valign="top">
            <td class="wrap-friendlist-select">
            {include file="include/common/friendlist.inc"}
            <div class="line">&nbsp;</div>
            </td>
        </tr>
        <tr valign="top">
            <td width="90%" id="tree_view_right" class="wrap-friendlist-select-bottom">
                <div class="friendlist-name"><img src="/img/group.png">{$smarty.const.APP_NAME}について</div>
                <ul class="explain">
                <li>現在ベータ公開してます。動きがおかしかった等ありましたら連絡ください。<a href="https://www.facebook.com/keiichi.honma2" target="_parent">@Keiichi Honma</a></li>
                <li>facebookの友達リストを使いやすくしたアプリです。</li>
                <li>友達リストとは近況アップデートの公開範囲として利用できる便利なリストです。<br /><img src="/img/explain/ex1.gif"></li>
                <li>友達の種類によって見せる近況を選択することができるようになります。</li>
                <li>友達の人数が増えてきた場合、facebookの友達リスト管理では追加、削除等が煩雑であり、<br />その問題を解決するアプリです。</li>
                {*<li>友達が1,000人以上の方は現時点で制限をかけています。<br />ご要望あれば制限解除するかもしれません。連絡ください。</li>*}
                <li>使い方はシンプルで「友達リストを選ぶ→友達を条件で絞る→追加、削除」<br /><img src="/img/explain/ex2.jpg"></li>
                <li>一度に追加、削除できる人数は25人までです。facebookに負荷をかけ過ぎないようにする仕様です。<br />状況を見て制限を柔らかくするかもしれません。</li>
                <li>友達リストの追加は可能ですが、削除機能は実装していません。</li>
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
  length:  25,     // 線の長さ
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