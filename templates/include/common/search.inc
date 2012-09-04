                <div id="search">
                    <div class="line">&nbsp;</div>
                    <table class="search">
                        <tr>
                            <td>
                                <input id="reset" type="button" value=" 全条件リセット " />
                                <fieldset>
                                <legend><img src="/img/male.png" width="16" height="16">性別<img src="/img/female.png" width="16" height="16"></legend>
                                <table>
                                <tr><td nowrap class="radio"><form id="sex_form"><label><input type="radio" id="sex1" name="sex" value="open" checked="checked">指定しない</label><br /><label><input type="radio" id="sex2" name="sex" value="male">男性</label><br /><label><input type="radio" id="sex3" name="sex" value="female">女性</label></form></td></tr>
                                </table>
                                </fieldset>
                            </td>
                            <td class="arrow"><img src="/img/arrows.gif"></td>
                            <td>
                                <fieldset>
                                    <legend><img src="/img/heart.png" width="16" height="16">交際ステータス<img src="/img/heart.png" width="16" height="16"></legend>
                                    <table>
                                    <tr><td colspan="3" class="radio"><form id="relationship_form"><label><input type="radio" id="relationship00" name="relationship" value="open" checked>指定しない</label></td></tr>
                                    {foreach from=$relationship_status key="key" item="value" name="relationship_status"}
                                        <tr>
                                        {foreach from=$value key="key2" item="value2" name="relationship_status2"}
                                            <td nowrap class="radio"><label><input type="radio" id="relationship{$smarty.foreach.relationship_status.iteration}{$smarty.foreach.relationship_status2.iteration}" name="relationship" value="{$key2}">{$value2}</label></td>
                                        {/foreach}
                                        </tr>
                                    {/foreach}
                                    </form>
                                    </table>
                                </fieldset>
                            </td>
                            <td class="arrow"><img src="/img/arrows.gif"></td>
                            {if $friendlist}
                            
                            <td>
                                <fieldset>
                                    <legend><img src="/img/group.png" width="16" height="16">友達リストで絞り込み<img src="/img/zoom.png" width="16" height="16"></legend>
                                    {if $user}<form id="new_friend_form"><label><input type="checkbox" id="new_friend" name="new_friend" value="new">友達リストに入っていない友達</label></form><span class="attention">※新しい友達を抽出する場合等</span>{/if}
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
                                    <legend><img src="/img/group_link.png" width="16" height="16">所属グループで絞り込み<img src="/img/zoom.png" width="16" height="16"></legend>
                                    <form id="gid_form"><select id="gid" name="gid">
                                    <option value="open">指定しない</option>
                                    {foreach from=$grouplist key="key" item="value" name="grouplist"}
                                        <option value="{$value.gid}">{$value.name|make_strim:28}</option>
                                    {/foreach}
                                    </select></form>
                                </fieldset>
                                {/if}
                            </td>
                            {/if}
                        </tr>
                    </table>
                </div>