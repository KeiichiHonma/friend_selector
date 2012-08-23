<table class="search">
    <tr>
        <td class="arrow"><img src="/img/group_go.png"></td>
        <td>
            <form id="friendlist_form" name="friendlist_form">
            <select name="friendlist_menu" id="friendlist_menu">
            <option value="">友達リストを選択</option>
            {foreach from=$friendlist key="key" item="value" name="friendlist"}
            <option value="/friendlist/view/flid/{$value.flid}{if isset($flid) && strcasecmp($value.flid,$flid) != 0}?c=d{/if}" {if isset($flid) && strcasecmp($value.flid,$flid) == 0}selected{/if}>{$value.name|makeFriendlistName:26}</option>
            {/foreach}
            </select>
            <input type="button" id="friendlist" name="friendlist" value="表示" onClick="selectFriendlist('friendlist_menu')">
            </form>
        </td>
        <td class="arrow"><img src="/img/arrow_s_r.gif"></td>
        <td>
            <form id="add_form" name="add_form" method="post" action="/friendlist/add"><input name="add_friendlist_name" id="add_friendlist_name" type="text" value="" /><input type="hidden" name="fs_method" value="post" /><input type="hidden" name="csrf_ticket" value="{$csrf_ticket}" /><input id="add" type="button" value="リストを追加" /></form>
            <div id="add{if isset($flid)}_view{/if}_spin" class="add{if isset($flid)}_view{/if}_spin"></div>
        </td>
        {if isset($flid)}<td><form name="search_d_form"><input type="button" value="↓隠す↓" id="search_d_btn" name="search_d_btn" onclick="search_d()"></form></td>{/if}
        <td><iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FFriend-Selector-Community%2F495253630504678&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21&amp;appId=350725135008165" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;" allowTransparency="true"></iframe></td>
    </tr>
</table>
