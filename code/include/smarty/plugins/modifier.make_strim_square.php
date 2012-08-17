<?php
function smarty_modifier_make_strim_square($string,$strimbyte = 180,$brLength = 60,$isAutoLink = SMARTY_BOOL_ON)
{
    global $con;
    return $con->base->mb_strim_square($string,$strimbyte,$brLength,$marker,$isAutoLink);
    
    
    
/*    require_once('fw/uri.php');
    $uri = new uri();
    $result1 = $uri->autolink($string,TRUE,$isAutoLink);

    $pa = '[a-zA-Z0-9_\(\)\.\/\~\%\:\#\?=&\;\-\+\,]{'.$brLength.',}';
    $result2 = $con->base->mb_strimbyte($result1,0,$strimbyte);
    $bl = ereg($pa, $result2);
    if($bl !== FALSE){
        $result3 = $con->base->convertSpecial($result2);
        $result4='';
        for($i=0;$i<=strlen($result3)/$brLength;$i++){
            $result4.=substr($result3,$i*$brLength,$brLength)."\r\n";
        }
        return nl2br(mb_convert_encoding( $result4,'UTF-8','SJIS'));
    }else{
        return $result2;
    }*/
}
?>
