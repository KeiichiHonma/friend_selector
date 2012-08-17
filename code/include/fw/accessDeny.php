<?php
class accessDeny
{
    public $mobile_access_deny = array
    (
        'NTe2XR0',//みかん、さくら
        'c2uukn7Dvl0oaehT',//メンバー : 967：エブプー
        '05004011605818_vh.ezweb.ne.jp'//おたにまる
    );
    function __construct($subscriber){
        if(in_array($subscriber,$this->mobile_access_deny)){
            header("Location: ".CAURL.'/deny');
            die();
        }
    }
}

?>