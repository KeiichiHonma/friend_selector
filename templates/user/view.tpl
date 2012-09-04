<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF8">
<script type="text/javascript" charset="utf-8" src="/js/jquery-1.7.2.min.js"></script>
<script src="/js/jquery.colorbox.js"></script>
<link type="text/css" href="/css/list.css" rel="stylesheet" media="all" />
<link type="text/css" href="/css/jquery.treeview.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="/js/spin.min.js"></script>
<script type="text/javascript" src="/js/fs.js?{$tail_number}"></script>
<script type="text/javascript">

$(document).ready(function() {ldelim}
 
    /** 送信ボタンクリック */
    $('#update').click(function() {ldelim}
        user_form_handle(true);
        
        // spin実行
        document.getElementById('update_spin').style.display='block';
        var spinner = new Spinner(opts).spin(target);
        
        var checks=[];
        $("[name='user_update[]']:checked").each(function(){ldelim}
            checks.push(this.value);
        {rdelim});
        //POSTメソッドで送るデータを定義します var data = パラメータ名 : 値;
        var data = {ldelim}flids : checks , fs_method : "post" , csrf_ticket : "{$csrf_ticket}" {rdelim};
        /**
         * Ajax通信メソッド
         * @param type  : HTTP通信の種類
         * @param url   : リクエスト送信先のURL
         * @param data  : サーバに送信する値
         */
        $.ajax({ldelim}
            type: 'POST',
            url: '/user/update/uid/{$uid}',
            data: data,
            success: function(data, dataType) {ldelim} /** Ajax通信が成功した場合に呼び出される */
                //返ってきたデータの表示（当サンプルだと「OK」か「The parameter of "request" is not found.」）
                if(data != ''){ldelim}
                    alert(data);
                {rdelim}else{ldelim}
                    user_form_handle(false);
                {rdelim}
                document.getElementById('update_spin').style.display='none';
            {rdelim},
            error: function(XMLHttpRequest, textStatus, errorThrown) {ldelim}  /** Ajax通信が失敗した場合に呼び出される */
                    // 通常はここでtextStatusやerrorThrownの値を見て処理を切り分けるか、
                    // 単純に通信に失敗した際の処理を記述します。
                    this; // thisは他のコールバック関数同様にAJAX通信時のオプションを示します。
                    alert('Error : ' + errorThrown);
                    user_form_handle(false);
                    document.getElementById('update_spin').style.display='none';
            {rdelim}
        {rdelim});
    {rdelim});
     
    /** サブミット後、ページをリロードしないようにする */
    $('#user_update_form').submit(function() {ldelim}
        return false;
    {rdelim});
{rdelim});

</script>


<title>{$smarty.const.APP_NAME}</title>
</head>
<body class="bg">
<table width="100%" cellpadding="5" cellspacing="0" style="border-collapse: collapse;" bgcolor="#ffffff">
    <tbody>
        <tr valign="top">
            <td class="wrap-friendlist-select">
            <div class="user-name"><img src="{$user.0.pic_square}" width="50" height="50" style="float: left;" />{$user.0.name}<br />{$user.0.sex|makeSex}/{$user.0.birthday_date|makeAge|default:"非公開"}/{$user.0.relationship_status|makeRelationshipStatus|default:"非公開"}</div>
            <div class="line">&nbsp;</div>
            </td>
        </tr>
        <tr valign="top">
            <td width="90%" id="tree_view_right" class="wrap-friendlist-select-bottom">
                <div class="friendlist-name"><img src="/img/group.png">{$user.0.name}の所属している友達リスト</div>
                
                    <form id="user_update_form" method="POST" action="/user/update/uid/{$uid}" target="_self">
                    <div style="width:350px; height:400px; overflow:auto;">
                    {foreach from=$friendlist key="key" item="value" name="friendlist"}
                    <label><input type="checkbox" class="user_update" name="user_update[]" value="{$value.flid}"{if isset($user_in_friendlist) && array_key_exists($value.flid,$user_in_friendlist)} checked{/if}>{$value.name|makeFriendlistName:40}</label><br />
                    {/foreach}
                    </div>
                    <input type="hidden" name="fs_method" value="post" />
                    <input type="hidden" name="csrf_ticket" value="{$csrf_ticket}" />
                    <input id="update" type="submit" value="更新する" />
                    </form>
                    <div id="update_spin" class="update_spin"></div>
                
            </td>
        </tr>
    </tbody>
</table>
{literal}
<script type="text/javascript">
// 以下はデフォルト値
var opts = {
  lines:   20,    // 回転する線の本数
  length:  10,     // 線の長さ
  width:   4,     // 線の太さ
  radius:  15,    // 線の丸み
  color: ' #000', // 線の色　#rgb or #rrggbb
  speed:   1,     // 1回転に要する時間 秒
  trail:   60,    // Afterglow percentage
  shadow:  false, // 線に影を付ける場合、true
  hwaccel: false  // Whether to use hardware acceleration
};

// アニメーションを挿入する要素
var target = $('.update_spin')
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
  </body>
</html>