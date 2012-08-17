<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
/*ex) 
'メールアドレス'=>array('name'=>'mail','type'=>'text','func'=>null,'class'=>'form_text_common','must'=>TRUE,'front'=>'','back'=>'')*/

function smarty_modifier_make_form($form_name,$form_setting,$error,$colspan = SMARTY_BOOL_OFF,$isConfirm = SMARTY_BOOL_OFF)
{
    global $con;
    if($isConfirm === SMARTY_BOOL_ON){
        require_once('fw/uri.php');
        $uri = new uri();
    }

    $html = '';
    $html .= "<tr>\n";
    $html .= '<td width="150" valign="top">';
    
    //最初の要素が配列かどうか調査し、セッティング情報を設定
    $first = reset($form_setting);
    if(is_array($first)){
        foreach ($form_setting as $key => $value){
            $setting[] = $value;
        }
    }else{
        $setting[] = $form_setting;
    }
    $count = count($setting);

    //エラーが存在するかどうか,必須項目かどうか
    $error_flag = FALSE;
    $error_message = '';
    $must_flag = FALSE;
    for ($i=0;$i<$count;$i++){
        //複数の場合、どちらか一方がエラーまたは必須だったらTRUE
        if(isset($error[$setting[$i]['name']])){
            if(!$error_flag) $error_flag = TRUE;
            $error_message .= '<span class="error_alert">'.$error[$setting[$i]['name']].'</span><br />';//エラーメッセージ
        }else{
            $error_message .= '';//エラーメッセージ
        }
        if(!is_null($setting[$i]['remarks'])) $remarks = $setting[$i]['remarks'];
        if(!$must_flag  && $setting[$i]['must']) $must_flag = TRUE;
    }

    $html .= $error_flag ? '<b>'.$form_name.'</b>' : $form_name;//フォーム名
    $html .= $must_flag &&  $isConfirm === SMARTY_BOOL_OFF ? '<span class="attention">＊</span>' : '';//必須
    $html .= "</td>\n";
    $html .= '<td valign="top">';
    if($isConfirm === SMARTY_BOOL_OFF && strlen($remarks) > 0) $html .= '<p class="remarks">'.$remarks.'</p>';
    $html .= $error_message;

    //フォームコンテンツ生成開始
    for ($i=0;$i<$count;$i++){
        
        $data = '';
        if(isset($_POST[$setting[$i]['name']])){
            if(!is_array($_POST[$setting[$i]['name']])){
                $escape_post = htmlspecialchars($_POST[$setting[$i]['name']]);
            }else{
                $escape_post = $_POST[$setting[$i]['name']];
            }
        }else{
            if(!is_array($_POST[$setting[$i]['name']])){
                $escape_post = '';
            }else{
                $escape_post = array();
            }
        }
        //$escape_post = isset($_POST[$setting[$i]['name']]) && !is_array($_POST[$setting[$i]['name']])  ? htmlspecialchars($_POST[$setting[$i]['name']]) : '';//post
        
        $maxlength = is_null($setting[$i]['maxlength']) ? '' : ' maxlength="'.$setting[$i]['maxlength'].'"';
        
        
        
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
                    $data = $escape_post.'<br class="clear" />';
                }elseif($setting[$i]['name'] == 'mail_m'){
                    //既存メンバーがからのまま修正しようとした場合は何もない
                    if(strlen($escape_post) > 0){
                        $data = $escape_post.'＠';
                    }
                }else{
                    $data = $escape_post;
                }
                
            }else{
                //特殊処理
                if($setting[$i]['name'] == 'mail'){
                    $data = '<input type="text" name="'.$setting[$i]['name'].'" value="'.$escape_post.'" class="'.$setting[$i]['class'].'"'.$maxlength.' /><br class="clear" />';
                }elseif($setting[$i]['name'] == 'mail_m'){
                    $data = '<input type="text" name="'.$setting[$i]['name'].'" value="'.$escape_post.'" class="'.$setting[$i]['class'].'"'.$maxlength.' /><p class="p_fl">&nbsp;＠&nbsp;</p>';
                }else{
                    $data = '<input type="text" name="'.$setting[$i]['name'].'" value="'.$escape_post.'" class="'.$setting[$i]['class'].'"'.$maxlength.' />';
                }
                
            }
            $html .= $setting[$i]['front'].$data.$setting[$i]['back'];
            break;

        case 'password':
            if($isConfirm === SMARTY_BOOL_ON){
                $data = '*******************';
            }else{
                $data = '<input type="password" name="'.$setting[$i]['name'].'" value="" class="'.$setting[$i]['class'].'"'.$maxlength.' />';
            }
            $html .= $setting[$i]['front'].$data.$setting[$i]['back'];
            break;
        
        case 'textarea':
            if($isConfirm === SMARTY_BOOL_ON){
                $data = $con->base->make_text($_POST[$setting[$i]['name']]);
            }else{
                $data = '<textarea name="'.$setting[$i]['name'].'" class="'.$setting[$i]['class'].'" />'.$escape_post.'</textarea>';
            }
            $html .= $setting[$i]['front'].$data.$setting[$i]['back'];
            break;

        case 'radio'://ラジオボタン：valueの数だけinputが表示される形のため、foreach内でinputが登場する
            if($isConfirm === SMARTY_BOOL_ON){
                $data = $setting[$i]['value'][$escape_post];
            }else{
                $i2 = 0;
                $head_flag = FALSE;
                $foot_flag = FALSE;
                //重要！新規追加は無視
                //メール通知系で且つ、パソコン又は携帯のアドレスが存在しなかった場合、グレイアウトするように設定。エラーチェックは残しておく
                if($con->pagepath != 'entry/input' && (strcasecmp($setting[$i]['name'],'magazine_mail') == 0 || strcasecmp($setting[$i]['name'],'notification_mail') == 0)){
                    foreach ($setting[$i]['value'] as $key => $value){
                        $checked = '';
                        if(strcasecmp($escape_post,$key) == 0){
                            $checked = 'checked';
                        }elseif((strlen($escape_post) == 0 || !isset($escape_post)) && $i2 == 0){
                            $checked = 'checked';
                        }
                        $labe_id = $setting[$i]['name'].'_'.$i2;
                        $data .= !$head_flag ?  '' : '<br />';
                        $data .= !$foot_flag ?  '' : '<br />';
                        $data .= '<input type="radio" id="'.$labe_id.'" name="'.$setting[$i]['name'].'" value="'.$key.'" class="'.$setting[$i]['class'].'" '.$checked.' /><label for="'.$labe_id.'">'.$value.'</label>&nbsp;';
                        $i2++;
                    }
                }else{
                    foreach ($setting[$i]['value'] as $key => $value){
                        $checked = '';
                        if(strcasecmp($escape_post,$key) == 0){
                            $checked = 'checked';
                        }elseif((strlen($escape_post) == 0 || !isset($escape_post)) && $i2 == 0){
                            $checked = 'checked';
                        }
                        $labe_id = $setting[$i]['name'].'_'.$i2;
                        $data .= '<input type="radio" id="'.$labe_id.'" name="'.$setting[$i]['name'].'" value="'.$key.'" class="'.$setting[$i]['class'].'" '.$checked.' /><label for="'.$labe_id.'">'.$value.'</label>&nbsp;';
                        $i2++;
                    }
                }
            }
            $html .= $setting[$i]['front'].$data.$setting[$i]['back'];
            break;
 
        case 'checkbox'://ラジオボタン：valueの数だけinputが表示される形のため、foreach内でinputが登場する
            if($isConfirm === SMARTY_BOOL_ON){
                foreach ($escape_post as $key => $value){
                    $tmp[] = $setting[$i]['value'][$value];
                    $data = implode(',',$tmp);
                }
            }else{
                $i2 = 0;
                foreach ($setting[$i]['value'] as $key => $value){
                    $checked = '';
                    if(is_array($escape_post) && in_array($key,$escape_post)){
                        $checked = 'checked';
                    }
/*                    elseif((strlen($escape_post) == 0 || !isset($escape_post)) && $i2 == 0){
                        $checked = 'checked';
                    }*/
                    $labe_id = $setting[$i]['name'].'_'.$i2;
                    $data .= '<input type="checkbox" id="'.$labe_id.'" name="'.$setting[$i]['name'].'[]" value="'.$key.'" class="'.$setting[$i]['class'].'" '.$checked.' /><label for="'.$labe_id.'">'.$value.'</label>&nbsp;';
                    $i2++;
                }
            }
            $html .= $setting[$i]['front'].$data.$setting[$i]['back'];
            break;
        
        case 'select'://セレクト：valueの数だけoptionが表示される.selectではないことに注意
            if($isConfirm === SMARTY_BOOL_ON){
                $data = $setting[$i]['value'][$escape_post];
            }else{
                //キャリア選択
                if($setting[$i]['name'] == 'carrier'){
                    $data .= '<select name="'.$setting[$i]['name'].'" class="mail_select_fl">'."\n";
                    $data .= '<option value="">＠より後ろを選択してください</option>'."\n";
                    foreach ($setting[$i]['value'] as $key => $value){
                        $selected = '';
                        if(strcasecmp($escape_post,$key) == 0) $selected = 'selected';
                        $data .= '<option value="'.$key.'" class="'.$setting[$i]['class'].'" '.$selected.'>'.$value.'</option>'."\n";
                    }
                    $data .= '</select>'."\n";
                    $data .= '<br class="clear" />※ドメイン指定受信を設定されている方は「oshiete-ca.com」を受信できるように指定してください。';
                }else{
                    $data .= '<select name="'.$setting[$i]['name'].'">'."\n";
                    $data .= $setting[$i]['must'] ? '<option value="">選択してください</option>'."\n" : '<option value="">指定しない</option>'."\n";
                    foreach ($setting[$i]['value'] as $key => $value){
                        $selected = '';
                        if(strcasecmp($escape_post,$key) == 0) $selected = 'selected';
                        $data .= '<option value="'.$key.'" class="'.$setting[$i]['class'].'" '.$selected.'>'.$value.'</option>'."\n";
                    }
                    $data .= '</select>'."\n";
                }
            }
            if($setting[$i]['name'] == 'carrier'){
                $html .= $data;
            }else{
                if($count > 1) $html .= '<p class="m_b10">';//複数ある場合はpタグで囲む
                if($setting[$i]['front'] != '') $html .= '<span class="m_r10">'.$setting[$i]['front'].'</span>';//前テキストがある場合はマージンをとる
                $html .= $data;
                $html .= $setting[$i]['back']."\n";
                if($count > 1) $html .= '</p>';//複数ある場合はpタグで囲む
            }

            break;
        }
        
        if($count == 1 &&$i != $count -1) $html .= '<br />';//改行

        if($isConfirm === SMARTY_BOOL_ON){
            if(!is_array($escape_post)){
                $html .= '<input type="hidden" name="'.$setting[$i]['name'].'" value="'.$escape_post.'" />';//hidden
            }else{
                foreach ($escape_post as $key => $value){
                    $html .= '<input type="hidden" name="'.$setting[$i]['name'].'[]" value="'.$value.'" />';//hidden
                }
            }
            
        }
    }
    
    $html .= "</td>\n";
    //if($isConfirm === SMARTY_BOOL_OFF) $html .= '<td class="help" valign="top">'.$remarks.'</td>';
    
    $html .= "</tr>\n";

    return $html;
}
?>
