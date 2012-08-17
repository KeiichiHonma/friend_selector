<?php
//08/22/1979
function smarty_modifier_makeAge($birth)
{
    return util::makeAgeText($birth);
}

?>
