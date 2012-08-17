<?php
function smarty_modifier_mobile_make_form($form_name,$form_setting,$error,$isConfirm = SMARTY_BOOL_OFF)
{
    global $con;
    if($isConfirm === SMARTY_BOOL_ON){
        require_once('fw/uri.php');
        $uri = new uri();
    }
    
    $start_block = '<div style="color:black;padding:2px;">'."\n";
    $end_block = '</div>'."\n";

    $html = '';
    $html .= '<div style="color:black;">'."\n";
    
    //最初の要素が配列かどうか調査し、ｾｯﾃｨﾝｸﾞ情報を設定
    $first = reset($form_setting);
    if(is_array($first)){
        foreach ($form_setting as $key => $value){
            $setting[] = $value;
        }
    }else{
        $setting[] = $form_setting;
    }
    $count = count($setting);

    //ｴﾗｰが存在するかどうか,必須項目かどうか
    $error_flag = FALSE;
    $error_message = '';
    $must_flag = FALSE;
    for ($i=0;$i<$count;$i++){
        if($setting[$i]['name'] == 'password_c' || $setting[$i]['name'] == 'mail_c') return FALSE;//必要ない
        
        //複数の場合、どちらか一方がｴﾗｰまたは必須だったらTRUE
        if(isset($error[$setting[$i]['name']])){
            
            if(!$error_flag) $error_flag = TRUE;
            $error_message .= '<span style="color:red;">'.$error[$setting[$i]['name']].'</span><br />';//ｴﾗｰﾒｯｾｰｼﾞ
        }else{
            $error_message .= '';//ｴﾗｰﾒｯｾｰｼﾞ
        }
        if(!$must_flag  && $setting[$i]['must']) $must_flag = TRUE;
    }

    if(!is_null($setting[0]['m_remarks'])){
        $remarks = $setting[0]['m_remarks'];
    }else{
    }
    
   
    
    $html .= '■'.$form_name;
    $html .= $must_flag &&  $isConfirm === SMARTY_BOOL_OFF ? '<span style="color:red;">※必須</span><br />' : '<br />';//必須
    if($isConfirm === SMARTY_BOOL_OFF) $html .= $remarks;
    $html .= "</div>\n";
    //$html .= '<div style="background-color:#000000;height:1px;"><img alt="Spacer" height="1" src="/img/spacer.gif" width="1" /></div>'."\n";
    
    //ﾌｫｰﾑｺﾝﾃﾝﾂ生成開始
    
    //if($isConfirm === SMARTY_BOOL_OFF) $html .= '<span style="color:black;">'.$form_name.'</span>';
    if($isConfirm === SMARTY_BOOL_OFF) $html .= '<div style="background-color:white;height:1px;margin-top:3px;margin-bottom:3px;">&nbsp;</div>';
    $html .= $error_message;
    if($isConfirm === SMARTY_BOOL_ON) $html .= $start_block;//確認だけ
    $html .= '<div style="color:black;">'."\n";
    $isEnd = FALSE;
    for ($i=0;$i<$count;$i++){
        if($i == $count - 1) $isEnd = TRUE;
        
        $data = '';
        $escape_post = isset($_POST[$setting[$i]['name']]) ? htmlspecialchars($_POST[$setting[$i]['name']]) : '';//post
        //$maxlength = is_null($setting[$i]['maxlength']) ? '' : ' maxlength="'.$setting[$i]['maxlength'].'"';
        
        switch ($setting[$i]['type']){
        case 'noedit':
            if($isConfirm === SMARTY_BOOL_ON){
                $data = $setting[$i]['value'];
            }else{
                $data = $setting[$i]['value'];
            }
            $html .= $setting[$i]['front'].$data.$setting[$i]['back'];
            break;

        case 'text':
            if($isConfirm === SMARTY_BOOL_ON){
                //特殊処理
                if($setting[$i]['name'] == 'mail'){
                    $data = $escape_post.$setting[$i]['back'].'<br />';
                }elseif($setting[$i]['name'] == 'mail_m'){
                    $data = $escape_post.$setting[$i]['back'].'@';
                }else{
                    $data = $escape_post;
                }
                $html .= $setting[$i]['front'].$data.$setting[$i]['back'];
            }else{
                //特殊処理
                if($setting[$i]['name'] == 'mail'){
                    $data .= $start_block;
                    $data .= $setting[$i]['front'];
                    $data .= '<input type="text" name="'.$setting[$i]['name'].'" value="'.$escape_post.'"'.getMobileTextStyle($setting[$i]['m_text_style']).' />';
                    $data .= $setting[$i]['back'];
                    $data .= $end_block;
                }elseif($setting[$i]['name'] == 'mail_m'){
                    $data .= $start_block;
                    $data .= $setting[$i]['front'];
                    $data .= '@ﾏｰｸより前を記入してください。<br /><input type="text" name="'.$setting[$i]['name'].'" value="'.$escape_post.'"'.getMobileTextStyle($setting[$i]['m_text_style']).' />';
                    $data .= $setting[$i]['back'];
                    $data .= $end_block;
                }else{
                    if($i == 0) $data .= $start_block;
                    $data .= $setting[$i]['front'];
                    $data .= '<input type="text" name="'.$setting[$i]['name'].'" value="'.$escape_post.'"'.getMobileTextStyle($setting[$i]['m_text_style']).' />';
                    $data .= $setting[$i]['back'];
                    if($isEnd) $data .= $end_block;
                }
                $html .= $data;
            }
            break;

        case 'password':
            if($isConfirm === SMARTY_BOOL_ON){
                $data = $escape_post;
            }else{
                $data .= $start_block;
                $data .= $setting[$i]['front'];
                $data .= '<input type="text" name="'.$setting[$i]['name'].'" value="'.$escape_post.'"'.getMobileTextStyle($setting[$i]['m_text_style']).' />';
                $data .= $setting[$i]['back'];
                $data .= $end_block;
            }
            $html .= $data;
            break;
        
        case 'textarea':
            if($isConfirm === SMARTY_BOOL_ON){
                $data = $con->base->make_text($_POST[$setting[$i]['name']]);
            }else{
                $data .= $start_block;
                $data .= $setting[$i]['front'];
                $data .= '<textarea name="'.$setting[$i]['name'].'"'.getMobileTextStyle($setting[$i]['m_text_style'],'height:80px;width:100%;').'>'.$escape_post.'</textarea>';
                $data .= $setting[$i]['back'];
                $data .= $end_block;
            }
            $html .= $data;
            break;

        case 'radio'://ﾗｼﾞｵﾎﾞﾀﾝ：valueの数だけinputが表示される形のため、foreach内でinputが登場する
            if($isConfirm === SMARTY_BOOL_ON){
                $data = $setting[$i]['value'][$escape_post];
            }else{
                $data .= $start_block;
                $data .= $setting[$i]['front'];
                $i2 = 0;
                //重要！新規追加は無視
                //メール通知系で且つ、パソコン又は携帯のアドレスが存在しなかった場合、グレイアウトするように設定。エラーチェックは残しておく
                if($con->pagepath != 'entry/input' && (strcasecmp($setting[$i]['name'],'magazine_mail') == 0 || strcasecmp($setting[$i]['name'],'notification_mail') == 0)){
                    foreach ($setting[$i]['value'] as $key => $value){
                        $checked = '';
                        //パソコンで受信
                        if(strlen($_POST['mail']) == 0 && strcasecmp($key,RECEIVE_PC) == 0){
                            $labe_id = '';
                            //$data .= '<input disabled="true" type="radio" id="'.$labe_id.'" name="'.$setting[$i]['name'].'" value="'.$key.'" '.$checked.' /><label for="'.$labe_id.'">'.$value.'</label><br />';
                            $data .= '<div style="margin-bottom:5px;">';
                            $data .= '<span style="color:#666666;">・'.$value.'</span><br />';
                            $guid = $con->getGuidString('mypage/member/edit/mail/input');
                            $data .= '※選択するには<a href="'.CAURL.'/mypage/member/edit/mail/input'.$guid.'">パソコンのメールアドレスを設定</a>する必要があります。';
                            $data .= '</div>';
                        //携帯で受信
                        }elseif(strlen($_POST['mail_m']) == 0 && strcasecmp($key,RECEIVE_MOBILE) == 0){
                            $labe_id = '';
                            //$data .= '<input disabled="true" type="radio" id="'.$labe_id.'" name="'.$setting[$i]['name'].'" value="'.$key.'" '.$checked.' /><label for="'.$labe_id.'">'.$value.'</label><br />';
                            $data .= '<div style="margin-bottom:5px;">';
                            $data .= '<span style="color:#666666;">・'.$value.'</span><br />';
                            $guid = $con->getGuidString('mypage/member/edit/mail/input');
                            $data .= '※選択するには<a href="'.CAURL.'/mypage/member/edit/mail/input'.$guid.'">携帯のメールアドレスを設定</a>する必要があります。';
                            $data .= '</div>';
                        //パソコン,携帯で受信
                        }elseif((strlen($_POST['mail']) == 0 || strlen($_POST['mail_m']) == 0) && strcasecmp($key,RECEIVE_ALL) == 0){
                            $labe_id = '';
                            //$data .= '<input disabled="true" type="radio" id="'.$labe_id.'" name="'.$setting[$i]['name'].'" value="'.$key.'" '.$checked.' /><label for="'.$labe_id.'">'.$value.'</label><br />';
                            $data .= '<div style="margin-bottom:5px;">';
                            $data .= '<span style="color:#666666;">・'.$value.'</span><br />';
                            $guid = $con->getGuidString('mypage/member/edit/mail/input');
                            $data .= '※選択するには<a href="'.CAURL.'/mypage/member/edit/mail/input'.$guid.'">パソコン、携帯両方のメールアドレスを設定</a>する必要があります。';
                            $data .= '</div>';
                            
                        }else{
                            if(strcasecmp($escape_post,$key) == 0){
                                $checked = 'checked="checked"';
                            }elseif((strlen($escape_post) == 0 || !isset($escape_post)) && $i2 == 0){
                                $checked = 'checked="checked"';
                            }
                            $labe_id = $setting[$i]['name'].'_'.$i2;
                            $data .= '<div style="margin-bottom:5px;">';
                            $data .= '<input type="radio" id="'.$labe_id.'" name="'.$setting[$i]['name'].'" value="'.$key.'" '.$checked.' /><label for="'.$labe_id.'">'.$value.'</label><br />';
                            $data .= '</div>';
                            $i2++;
                        }
                    }
                }else{
                    foreach ($setting[$i]['value'] as $key => $value){
                        $checked = '';
                        if(strcasecmp($escape_post,$key) == 0){
                            $checked = 'checked="checked"';
                        }elseif((strlen($escape_post) == 0 || !isset($escape_post)) && $i2 == 0){
                            $checked = 'checked="checked"';
                        }
                        $labe_id = $setting[$i]['name'].'_'.$i2;
                        $data .= '<input type="radio" id="'.$labe_id.'" name="'.$setting[$i]['name'].'" value="'.$key.'" '.$checked.' /><label for="'.$labe_id.'">'.$value.'</label><br />';
                        $i2++;
                    }
                }
                $data .= $setting[$i]['back'];
                $data .= $end_block;
            }
            $html .= $data;
            break;
        
        case 'select'://ｾﾚｸﾄ：valueの数だけoptionが表示される.selectではないことに注意
            
            if($isConfirm === SMARTY_BOOL_ON){
                if($setting[$i]['front'] != '') $data .= $setting[$i]['front'].'&nbsp;';//携帯の場合1ﾌﾞﾛｯｸづつ
                $data .= $setting[$i]['value'][$escape_post].'<br />';
            }else{
                $data .= $start_block;
                //ｷｬﾘｱ選択
                if($setting[$i]['name'] == 'carrier'){
                    $data .= '@ﾏｰｸより後ろを選択してください。<br />@';
                }else{

                }
                if($setting[$i]['front'] != '') $data .= $setting[$i]['front'].'<br />';//携帯の場合1ﾌﾞﾛｯｸづつ
                
                $data .= '<select name="'.$setting[$i]['name'].'">'."\n";
                $data .= $setting[$i]['must'] ? '<option value="">選択してください</option>'."\n" : '<option value="">指定しない</option>'."\n";

                foreach ($setting[$i]['value'] as $key => $value){
                    
                    $selected = '';
                    if(strcasecmp($escape_post,$key) == 0) $selected = 'selected="selected"';
                    $data .= '<option value="'.$key.'" '.$selected.' style="font-size:x-small;">'.$value.'</option>'."\n";
                    
                }

                $data .= '</select>'."\n";
                
                $data .= $setting[$i]['back']."\n";
                $data .= $end_block;
            }
            
            
            $html .= $data;
            break;
        }
        
        if($count == 1 &&$i != $count -1) $html .= '<br />';//改行
        //if($isConfirm === SMARTY_BOOL_ON) $html .= '<input type="hidden" name="'.$setting[$i]['name'].'" value="'.$escape_post.'" />';//hidden
        //hidden内容改行が半角スペースになってしまうので
        if($isConfirm === SMARTY_BOOL_ON) $html .= '<input type="hidden" name="'.$setting[$i]['name'].'" value="'.$con->encodeKaigyou($escape_post).'" />';
        
        $isEnd = FALSE;
    }
    $html .= "</div>\n";
    if($isConfirm === SMARTY_BOOL_ON) $html .= $end_block;//確認だけ
    if($isConfirm === SMARTY_BOOL_OFF) $html .= '<div style="background-color:#000000;height:1px;margin-top:5px;margin-bottom:5px;"><img alt="Spacer" height="1" src="/img/spacer.gif" width="1" /></div>'."\n";
    //$html .= "<br />\n";
    
    //1ﾌﾞﾛｯｸづつ出力
    return mb_convert_kana( mb_convert_encoding($html,"Shift_JIS", "UTF-8"), "akV","Shift_JIS");
}
    //$m_text_style = array
/*    function getMobileTextStyle($m_text_style){
        $common_style = $m_text_style['common'];
        $input_style = $m_text_style['input'];
        $style = '';
        
        $style .= $common_style;
        
        //入力制限ｽﾀｲﾙ
        $zen_hira_kana ='-wap-input-format:&quot;*&lt;ja:h&gt;&quot;;-wap-input-format:*M;';//全角かな
        $han_kata_kana ='-wap-input-format:&quot;*&lt;ja:hk&gt;&quot;;-wap-input-format:*M;';//半角ｶﾅ
        $eiji          ='-wap-input-format:&quot;*&lt;ja:en&gt;&quot;;-wap-input-format:*m;';//英字
        $suuji         ='-wap-input-format:&quot;*&lt;ja:n&gt;&quot;;-wap-input-format:*N;';//数字
        switch ($input_style){
        case 'zen_hira_kana':
          $style .= $zen_hira_kana;
          break;
        case 'han_kata_kana':
          $style .= $han_kata_kana;
          break;
        case 'eiji':
          $style .= $eiji;
          break;
        case 'suuji':
          $style .= $suuji;
          break;
        default:
          break;
        }
        return $style;
    }*/

/*
DoCoMo
    style=-wap-input-formatのみ
au/SoftBank
    istyle、format、modeをすべて記述

 mode

これはsoftbank特有の指定です。

パラメータには

    * hiragana : 全角かな
    * katakana : 全角カナ
    * hankakukana : 半角カナ
    * alphabet : 英字
    * numeric : 数字

 istyle

元々はdocomoの「HTML」の仕様です(docomoのXHTMLでは使えません。)。

auとsoftbankが後追いで互換仕様を実装しています。

パラメータは

    * 1 : 全角かな
    * 2 : 半角カナ
    * 3 : 英字
    * 4 : 数字


*/

/* format

auの仕様です。

softbankは互換仕様を実装している機種もある。。と仕様書にはありますが、ごく一部のみのようで、3GC系はこれだけじゃ何も変わりません。

docomoには、互換実装はないみたいです。

    * istyle=”1″　→　format=”*M”
    * istyle=”2″　→　format=”*M”
    * istyle=”3″　→　format=”*m”
    * istyle=”4″　→　format=”*N”

    * A : 英大文字のみ (句読点の入力も可能)
    * a : 英小文字のみ (句読点の入力も可能)
    * N : 数字のみ
    * X : 大文字と数字記号 (句読点、記号の入力も可能)
    * x : 英小文字と数字記号 (句読点、記号の入力も可能)
    * M : 全角漢字 (トグルによりすべての文字)
    * m : 英小文字 (トグルによりすべての文字)
*/

    function getMobileTextStyle($m_text_style,$add_style = ''){
        global $con;
        $common_style = $m_text_style['common'];
        $input_style = $m_text_style['input'];
        
        if($con->isDocomo){
            $style = ' style="';
            
            $style .= $add_style.$common_style;
            
            //入力制限ｽﾀｲﾙ
            $zen_hira_kana ='-wap-input-format:&quot;*&lt;ja:h&gt;&quot;;-wap-input-format:*M;';//全角かな
            $han_kata_kana ='-wap-input-format:&quot;*&lt;ja:hk&gt;&quot;;-wap-input-format:*M;';//半角ｶﾅ
            $eiji          ='-wap-input-format:&quot;*&lt;ja:en&gt;&quot;;-wap-input-format:*m;';//英字
            $suuji         ='-wap-input-format:&quot;*&lt;ja:n&gt;&quot;;-wap-input-format:*N;';//数字
            switch ($input_style){
                case 'zen_hira_kana':
                  $style .= $zen_hira_kana;
                  break;
                case 'han_kata_kana':
                  $style .= $han_kata_kana;
                  break;
                case 'eiji':
                  $style .= $eiji;
                  break;
                case 'suuji':
                  $style .= $suuji;
                  break;
                default:
                  break;
            }
            $style .= '"';
            return $style;
        }else{
            $style = ' style="'.$add_style.$common_style.'"';
            
            //istyle
            $istyle = ' istyle="';
            switch ($input_style){
                case 'zen_hira_kana':
                  $istyle .= '1';
                  break;
                case 'han_kata_kana':
                  $istyle .= '2';
                  break;
                case 'eiji':
                  $istyle .= '3';
                  break;
                case 'suuji':
                  $istyle .= '4';
                  break;
                default:
                  break;
            }
            $istyle .= '"';

            //format
            $format = ' format="';
            switch ($input_style){
                case 'zen_hira_kana':
                  $format .= '*M';
                  break;
                case 'han_kata_kana':
                  $format .= '*M';
                  break;
                case 'eiji':
                  $format .= '*m';
                  break;
                case 'suuji':
                  $format .= '*N';
                  break;
                default:
                  break;
            }
            $format .= '"';

            //mode
            $mode = ' mode="';
            switch ($input_style){
                case 'zen_hira_kana':
                  $mode .= 'hiragana';
                  break;
                case 'han_kata_kana':
                  $mode .= 'katakana';
                  break;
                case 'eiji':
                  $mode .= 'alphabet';
                  break;
                case 'suuji':
                  $mode .= 'numeric';
                  break;
                default:
                  break;
            }
            $mode .= '"';
            return $istyle.$format.$mode.$style;
        }
    }
?>
