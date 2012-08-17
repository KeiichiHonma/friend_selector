<?php
function smarty_modifier_unserialize_mail($serialize_mail)
{
    return strlen($serialize_mail) > 0 ? implode(',',unserialize($serialize_mail)) : '';
}
?>