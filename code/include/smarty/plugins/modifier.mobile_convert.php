<?php
function smarty_modifier_mobile_convert($string)
{
    mb_substitute_character('none');//存在しない文字コードがある場合（いわゆるゲタ or ?になる場合）の処理を設定をするため = 削除
    return htmlspecialchars(mb_convert_kana( mb_convert_encoding($string,"Shift_JIS", "UTF-8"), "akV","Shift_JIS"));
}
?>
