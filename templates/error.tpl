<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF8">
<script type="text/javascript" charset="utf-8" src="/js/jquery-1.7.2.min.js"></script>
<link type="text/css" href="/css/list.css" rel="stylesheet" media="all" />
<title>{$smarty.const.APP_NAME}</title>
</head>
<body class="bg">
{include file="include/common/headbar.inc"}
<table width="100%" cellpadding="5" cellspacing="0" style="border-collapse: collapse;" bgcolor="#ffffff">
    <tbody>
        <tr valign="top">
            <td width="90%" id="tree_view_right" class="wrap-friendlist-select-bottom">
                <div class="friendlist-name"><img src="/img/exclamation.png">エラー内容を確認してください。</div>
                <p class="alert">
                    {foreach from=$errorlist key="key" item="value" name="errorlist"}
                    {$value|nl2br}<br />
                    {/foreach}
                </p>
            </td>
        </tr>
    </tbody>
</table>
    {include file="include/common/footer.inc"}
  </body>
</html>