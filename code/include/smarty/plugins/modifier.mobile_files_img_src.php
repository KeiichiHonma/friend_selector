<?php
function smarty_modifier_mobile_files_img_src($fid,$ext,$max_width,$max_height)
{
    global $con;
    $path =  $con->base->getFilePath($fid,$ext,TRUE);//tailを付与しない
    global $carrier;
    if($carrier->softbank_vga){
        $max_width = $max_width*2;
        $max_height = $max_height*2;
    }
    return  CAURL.'/image/image.php'.$path.'?width='.$max_width.'&amp;height='.$max_height.'&amp;image='.$path;
    //<img src="<{$smarty.const.CAURL}>/image/image.php/20.jpg?width=50&amp;height=50&amp;image=files/0/0/4.jpg" />
    //return '<img src="'.$src.'" alt="'.$alt.'" />';
}
?>