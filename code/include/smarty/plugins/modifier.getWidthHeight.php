<?php
function smarty_modifier_getWidthHeight($width,$height,$max_width = 180,$max_height = 140,$isSlide = 1)
{
    $margin_text = '';
    if($width >= $height){
        if($width > $max_width){
            $x = $max_width * $height / $width;
            $height = ceil($x);
            //トップマージンを取る
            if($isSlide == 0 && $height < $max_height){
                $margin = ceil(($max_height - $height) / 2);
                $margin_text = '" style="margin-top:'.$margin.'px;"';
            }

            return 'width="'.$max_width.'" height="'.$height.'"'.$margin_text;
        }else{
            if($height > $max_height){
                $x = $max_height * $width / $height;
                return 'width="'.ceil($x).'" height="'.$max_height.'"';
            }else{
                
                if($isSlide == 0){
                    //引き延ばし
                    $height = $max_width * $height / $width;
                    $width = $max_width;

                    if($height > $max_height){
                        $width = $max_height * $width / $height;
                        $height = $max_height;
                    }

                    //トップマージンを取る
                    $margin = ceil(($max_height - $height) / 2);
                    $margin_text = '" style="margin-top:'.$margin.'px;"';
                }
                return 'width="'.$width.'" height="'.$height.'"'.$margin_text;
            }
        }
    }else{
        if($height > $max_height){
            $x = $max_height * $width / $height;
            return 'width="'.ceil($x).'" height="'.$max_height.'"';
        }else{
            if($width > $max_width){
                $x = $max_width * $height / $width;
                return 'width="'.$max_width.'" height="'.ceil($x).'"';
            }else{
                //この場合に限りトップマージンを取る
                if($isSlide == 0){
                    //$margin = ceil(($max_height - $height) / 2);
                    //$margin_text = '" style="margin-top:'.$margin.'px;"';
                    //引き延ばし
                    $width = $max_height * $width / $height;
                    $height = $max_height;
                    if($width > $max_width){
                        $height = $max_width * $height / $width;
                        $width = $max_width;
                    }
                }
                return 'width="'.$width.'" height="'.$height.'"'.$margin_text;
            }
        }
    }
}
?>
