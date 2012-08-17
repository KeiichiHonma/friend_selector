<?php
define('WIDTH_HEIGHT_EQUAL',  0);//横幅、縦幅一致
define('WIDTH_HEIGHT_WITHIN',  1);//横幅、縦幅以内
define('WIDTH_EQUAL_HEIGHT_WITHIN',  2);//横幅一致、縦幅以内
define('WIDTH_WITHIN_HEIGHT_EQUAL',  3);//横幅以内、縦幅一致
define('WIDTH_HEIGHT_OVER',          4);//横幅、縦幅以上

class checkManager
{
    
    static public $error = array();
    static private $stop = FALSE;
    static private $deny_word = null;
    
    static private $mobile_seiki_domain = array
    (
        'emnet\.ne\.jp',
        'willcom\.com',
        'wm\.pdx\.ne\.jp',
        'dk\.pdx\.ne\.jp',
        'di\.pdx\.ne\.jp',
        'dj\.pdx\.ne\.jp',
        'pdx\.ne\.jp',
        'bandai\.jp',
        'disney\.ne\.jp',
        //'i\.softbank\.jp',
        'softbank\.ne\.jp',
        'c\.vodafone\.ne\.jp',
        'd\.vodafone\.ne\.jp',
        'h\.vodafone\.ne\.jp',
        'k\.vodafone\.ne\.jp',
        'n\.vodafone\.ne\.jp',
        'r\.vodafone\.ne\.jp',
        's\.vodafone\.ne\.jp',
        't\.vodafone\.ne\.jp',
        'q\.vodafone\.ne\.jp',
        'jp-c\.ne\.jp',
        'jp-d\.ne\.jp',
        'jp-h\.ne\.jp',
        'jp-k\.ne\.jp',
        'jp-n\.ne\.jp',
        'jp-r\.ne\.jp',
        'jp-s\.ne\.jp',
        'jp-t\.ne\.jp',
        'jp-q\.ne\.jp',
        'ezweb\.ne\.jp',
        'ez[a-j]\.ido\.ne\.jp',
        'a[2-4]\.ezweb\.ne\.jp',
        'b2\.ezweb\.ne\.jp',
        'c[1-9]\.ezweb\.ne\.jp',
        'e[2-9\.ezweb\.ne\.jp',
        'h[2-4]\.ezweb\.ne\.jp',
        't1\.ezweb\.ne\.jp',
        't[2-9]\.ezweb\.ne\.jp',
        'ido\.ne\.jp',
        'sky\.tu-ka\.ne\.jp',
        'cara\.tu-ka\.ne\.jp',
        'sky\.tkk\.ne\.jp',
        'sky\.tkc\.ne\.jp',
        'docomo\.ne\.jp',
        'em\.nttpnet\.ne\.jp',
        'pipopa\.ne\.jp',
        'phone\.ne\.jp',
        'mozio\.ne\.jp',
        //'zeus\.corp\.iluna\.co\.jp'//debug
    );
    
    static public function safeExit(){
        if(count(self::$error) > 0){
            global $con;
            $con->t->assign('error',self::$error);
            return FALSE;
        }else{
            return TRUE;
        }
    }

    static protected function checkMust($param,$arg){
        if(!isset($param)){
            return FALSE;
        }else{
            if(is_array($param)){
                if(count($param) == 0) return FALSE;
                
            }else{
                if(strlen($param) == 0) return FALSE;
            }
        }
        return TRUE;
    }
    
    //条件付き必須項目
    static protected function checkConditionMust($param,$arg){
/*var_dump($_POST);
die();*/
        //指定パラメータ両方ともない場合は必須
        if( (isset($_POST[$arg['key']]) && strcasecmp($_POST[$arg['key']],$arg['value']) == 0) && !isset($param)){
            return FALSE;
        }
        
        //if(!isset($param) && !isset($_POST[$arg['key']])) return FALSE;
        return TRUE;
    }

    static protected function checkInt($param,$arg){
        if(is_numeric($param)){
            if(!is_null($arg)){
                if($arg['start'] <= $param && $arg['end'] >= $param){
                    return TRUE;
                }
            }else{
                return TRUE;
            }
        }
        return FALSE;
    }

    static protected function checkIntMoveIn($param,$arg){
        //if(is_null($param) || $_POST['move_in_date_timing'] == 1 || $_POST['move_in_date_timing'] == 2) return TRUE;//nullだったらOK
        if(is_numeric($param)){
            if(!is_null($arg)){
                if($arg['start'] <= $param && $arg['end'] >= $param){
                    return TRUE;
                }
            }else{
                return TRUE;
            }
        }
        return FALSE;
    }

    static protected function checkIntMoveOut($param,$arg){
        if(is_null($param) || $_POST['move_out_date_timing'][0] == 1) return TRUE;//nullだったらOK
        if(is_numeric($param)){
            if(!is_null($arg)){
                if($arg['start'] <= $param && $arg['end'] >= $param){
                    return TRUE;
                }
            }else{
                return TRUE;
            }
        }
        return FALSE;
    }

    static protected function checkDate($param,$arg){
        if(is_null($param)) return TRUE;//nullだったらOK
        //現在時間でチェック
        if(is_null($arg)){
            $time = time();
            $date = mktime(date("H",$time), 0, 0, date("m",$time),date("d",$time),date("Y",$time));
        //指定時間でチェック
        }else{
            $date = $arg;
        }
        
        return $param <= $date ? FALSE : TRUE;
    }

    static protected function checkDateMoveIn($param,$arg){
        if(is_null($param) || $_POST['move_in_date_timing'] != 0) return TRUE;//nullだったらOK
        //現在時間でチェック
        if(is_null($arg)){
            $time = time();
            $date = mktime(date("H",$time), 0, 0, date("m",$time),date("d",$time),date("Y",$time));
        //指定時間でチェック
        }else{
            $date = $arg;
        }
        
        return $param <= $date ? FALSE : TRUE;
    }

    //今日の日付のまま、時間等指定せずに登録ボタンを押した場合のエラー
    static protected function checkTodayDateBlank($param,$arg){
        //今日
        if(date("Y",$_POST['from']) == date("Y",time()) && date("n",$_POST['from']) == date("n",time()) && date("j",$_POST['from']) == date("j",time())){
            if(date("H",$_POST['from']) == '00' && date("i",$_POST['from']) == '00' && date("H",$_POST['to']) == '23' && date("i",$_POST['to']) == '59'){
                //画面に0時00分等を表示させない
                unset($_POST['from']);
                unset($_POST['to']);
                return FALSE;
            }
        }
        return TRUE;
    }


    static protected function checkDateInt($param,$arg){
        if(is_null($param)) return TRUE;//時刻のnullには意味がある
        if(is_numeric($param)){
            return TRUE;
        }
        return FALSE;
    }

    static protected function checkDateEnd($param,$arg){
        if((!isset($_POST['from']) || is_null($_POST['from'])) && !is_null($param)) return FALSE;
        if(is_null($param)) return TRUE;//終了がnullだったらOK
        
        return $param <= $_POST['from'] ? FALSE : TRUE;
    }

    static protected function checkDateEndMoveInOut($param,$arg){
        if(is_null($param) || $_POST['move_out_date_timing'][0] == 1 || $_POST['move_in_date_timing'] == 1 || $_POST['move_in_date_timing'] == 2) return TRUE;//nullだったらOK
        
        return $param <= $_POST['move_in_date'] ? FALSE : TRUE;
    }

    static protected function checkLength($param,$arg){
        if(strlen($param) == 0) return TRUE;//必須ではない
        $string = str_replace(array("\r\n","\n","\r"), '', $param);//改行除去.除去しないと正確な文字数が取れない
        global $con;
        //$int = mb_strlen($string);
        $int = strlen($con->base->convertSpecial($string));
        return $int >= $arg['start'] && $int <= $arg['end'] ? TRUE : FALSE;
    }

    static protected function checkPassword($param,$arg){
        if(preg_match("/^[a-zA-Z0-9]+$/", $param)){
            return TRUE;
        } else {
          return FALSE;
        }
    }

    static protected function checkValidatePassword($param,$arg){
        if(strcasecmp($_POST[$arg['key']],$param) == 0) return FALSE;
        $tmp = explode('@',$_POST[$arg['key']]);

        if(strcasecmp($tmp[0],$param) == 0) return FALSE;
        return TRUE;
    }

    static protected function checkMemberLogin($param,$arg){
        global $member_auth;
        require_once('member/logic.php');
        $logic = new memberLogic();
        $member = $logic->getMember($member_auth->mid,ALL);

        require_once('fw/authManager.php');
        $authManager = new authManager();
        $bl = $authManager->validatePassword( $_POST[$arg['old_password']], $member[0]['col_salt'], $member[0]['col_password'] );

        return $bl;
    }

    static protected function checkUserLogin($param,$arg){
        require_once('user/logic.php');
        $logic = new userLogic();
        $user = $logic->getuser($_POST[$arg['uid']],ALL);

        require_once('fw/authManager.php');
        $authManager = new authManager();
        $bl = $authManager->validatePassword( $_POST[$arg['old_password']], $user[0]['col_salt'], $user[0]['col_password'] );

        return $bl;
    }

    static protected function checkMail($mailaddress,$arg)
    {
        if(strlen($mailaddress) == 0) return TRUE;//must処理が通っている前提。必須項目ではない場合、OKとする
        $email_pattern = '([a-zA-Z0-9!#$%&\'*+\\-/=^_`{|}~]+([a-zA-Z0-9!#$%&\'*+\\-/=^_`{|}~\\.]+)*@[a-zA-Z0-9!#$%&\'*+\\-/=^_`{|}~]+(\\.[a-zA-Z0-9!#$%&\'*+\\-/=^_`{|}~]+)*)';
        if (preg_match($email_pattern,$mailaddress)) {
            return TRUE;
        }else{
            return FALSE;
        }
    }

    //逆チェック。携帯のアドレスは@とかが含まれている場合、エラーとする
/*    static protected function checkMailReverse($mailaddress,$arg)
    {
        if(strlen($mailaddress) == 0) return TRUE;//must処理が通っている前提。必須項目ではない場合、OKとする
        $email_pattern = '([a-zA-Z0-9!#$%&\'*+\\-/=^_`{|}~]+(\\.[a-zA-Z0-9!#$%&\'*+\\-/=^_`{|}~]+)*@[a-zA-Z0-9!#$%&\'*+\\-/=^_`{|}~]+(\\.[a-zA-Z0-9!#$%&\'*+\\-/=^_`{|}~]+)*)';
        if (preg_match($email_pattern,$mailaddress)) {
            return FALSE;
        }else{
            return TRUE;
        }
    }*/

    //携帯のアドレスに@を含めることができない。且つ、@が記載される可能性が高いため、独立してチェック
    static protected function checkAtmark($mailaddress,$arg)
    {
        if(strlen($mailaddress) == 0) return TRUE;//must処理が通っている前提。必須項目ではない場合、OKとする
        return strrchr($mailaddress,'@') !== FALSE ? FALSE : TRUE;
    }

    //携帯アドレスの前の文字列だけチェック
    static protected function checkHeadMail($mailaddress,$arg)
    {
        if(strlen($mailaddress) == 0) return TRUE;//must処理が通っている前提。必須項目ではない場合、OKとする
        //@より前をチェックしたいため、キャリアの指定、未指定に依存しないために、dummyを付与する
        $mail = $mailaddress.'@dummy.co.jp';//チェック用にdummyをぶち込む
        return self::checkMail($mail,null);
    }

    static private $iphone = array
    (
        'iPhone'
    );

    // モバイルのメールアドレス
    static public function checkMobileMail($mailaddress,$arg)
    {
        //debug honma,test1だけPCメール扱い
        if(strcasecmp($mailaddress,'honma@zeus.corp.813.co.jp') == 0 || strcasecmp($mailaddress,'test1@zeus.corp.813.co.jp') == 0) return FALSE;
        
        $imp = implode('|',self::$mobile_seiki_domain);
        $email_pattern ='/^.+@('.$imp.')$/';
        if (preg_match($email_pattern,$mailaddress)) {
            //iphone
            $imp2 = implode('|',self::$iphone);
            $iphone_pattern ='/.+('.$imp2.').+/';
            if (preg_match($iphone_pattern,$_SERVER['HTTP_USER_AGENT'])) {
                return TRUE;
            }
            return FALSE;
        }else{
            return TRUE;
        }
    }
    
    //メールテンプレートでドコモ判定が必要
    static public function checkDocomo($mailaddress){
        if (preg_match('/^.+@(docomo\.ne\.jp)$/',$mailaddress)){
            return TRUE;
        }
        return FALSE;
    }

    //異なっていたらFALSE
    static protected function checkMatch($param,$arg){
        return strcasecmp($param,$_POST[$arg['key']]) == 0 ? TRUE : FALSE;
    }

    //携帯アドレス用。ドメイン番号がリスト内に存在するか
    static protected function checkDomain($param,$arg){
        require_once('fw/formManager.php');
        $form = new formManager();
        return $form->getCarrier($param) === FALSE ? FALSE : TRUE;
    }

    //修正画面の携帯メンバー用。PCのアドレスがない状態でPCのアドレスをログイン方法に選択させない
    static protected function checkPcParameter($param,$arg){
        if(strcasecmp($param,LOGIN_PC) == 0 && (strlen($_POST[$arg['mail']]) == 0)) return FALSE;
        return TRUE;
    }

    //修正画面の既存メンバー用。携帯のアドレスがない状態で携帯のアドレスをログイン方法に選択させない
    static protected function checkMobileParameter($param,$arg){
        if(strcasecmp($param,LOGIN_MOBILE) == 0 && (strlen($_POST[$arg['mail_m']]) == 0 || strlen($_POST[$arg['carrier']]) == 0)) return FALSE;
        return TRUE;
    }
    

    static protected function checkMemberDuplication($param,$arg){
        require_once('member/logic.php');
        $logic = new memberLogic();
        if(!is_null($arg)){
            //mobile
            if(isset($arg['mail_m_tmp']) && isset($_POST[$arg['mail_m_tmp']])){
                //現行のアドレスと同じだったらノーチェックでOK
                if(isset($arg['mail_m_current']) && isset($_POST[$arg['mail_m_current']]) && strcasecmp($_POST[$arg['mail_m_tmp']],$_POST[$arg['mail_m_current']]) == 0) return TRUE;
                $member = $logic->isLoginName($_POST[$arg['mail_m_tmp']]);
            //pc
            }else{
                //現行アドレスのチェック
                //現行のアドレスと同じだったらノーチェックでOK
                if(isset($arg['mail_current']) && isset($_POST[$arg['mail_current']]) && strcasecmp($param,$_POST[$arg['mail_current']]) == 0) return TRUE;
                $member = $logic->isLoginName($param);
            }
        }else{
            $member = $logic->isLoginName($param);
        }
        //$member = is_null($arg) && ?  $logic->isLoginName($param) : $logic->isLoginName($_POST[$arg['mail_m_tmp']]);
        return $member === FALSE ? TRUE : FALSE;
    }

    static protected function checkUserDuplication($param,$arg){
        require_once('user/logic.php');
        $logic = new userLogic();
        $user = $logic->getRowLoginName($param);
        return $user === FALSE ? TRUE : FALSE;
    }
    
    static protected function checkAirlineDuplication($param,$arg){
        if(strlen($param) == 0) return TRUE;//must処理が通っている前提。必須項目ではない場合、OKとする
        return strcasecmp($param,$_POST[$arg['key1']]) == 0 || strcasecmp($param,$_POST[$arg['key2']]) == 0 ? FALSE : TRUE;
    }

    static protected function checkAirlineOrder($param,$arg){
        if(strlen($param) == 0 && strlen($_POST[$arg['key2']]) > 0) return FALSE;//airline2が指定されていなくて、airline3が指定されている
        return TRUE;
    }

    //titleへのURL入力禁止
    static protected function checkUrl($param,$arg){
        $url_pattern = '(https?://[a-zA-Z0-9/_.?#&;=$+:@%~,\\-]+)';
        if (preg_match($url_pattern,$param)) {
            return FALSE;
        }else{
            return TRUE;
        }
    }

    //NGワード
    static protected function checkDenyWord($param,$arg){
        global $ng_pattern;
        if(!isset($ng_pattern)){
            require_once('fw/denyWord.php');//なぜか動作しない
        }
        $n = count($ng_pattern);
        for($i = 0; $i < $n ; $i++){
            $deny_flag = preg_match('('.$ng_pattern[$i].')',$param,$match);
            if( $deny_flag != 0){
                self::$deny_word = $match[0];
                self::$extends .= '「'.self::$deny_word.'」';
                //メール送信///////////////////////////
                require_once('fw/mailManager.php');
                $mailManager = new mailManager();
                $mailManager->sendDenyWord(self::$deny_word);
                return FALSE;
            }
        }

        return TRUE;
    }

    /*define('STATUS_STOP',  0);
    define('STATUS_READY',  1);
    define('STATUS_PUBLIC',  2);
    define('STATUS_FINISH',  3);*/
    //チェックがかかるのはSTATUS_STOPかSTATUS_PUBLICだけ
    static protected function checkStatus($param,$arg){
        return is_numeric($param) && $param == STATUS_STOP || $param == STATUS_PUBLIC ? TRUE : FALSE;
    }

    static protected function checkUseful($param,$arg){
        return is_numeric($param) && $param == USEFUL_OFF || $param == USEFUL_ON ? TRUE : FALSE;
    }

    //file系
    static public $file_upload = FALSE;
    static public function checkFileReady($param,$arg){
        if(is_uploaded_file($param['tmp_name'])){
            self::$file_upload = TRUE;
        }
        return TRUE;
    }
    
    static private $file_must = FALSE;
    static public function checkFileMust($param,$arg_fid_key){
        self::$file_must = TRUE;
        if(isset($_POST[$arg_fid_key]) && is_numeric($_POST[$arg_fid_key])) return TRUE;//fidがある場合はファイルが既に存在しているため,エラー扱いしない
        return strcasecmp($param['error'],4) == 0 ? FALSE : TRUE;
    }
    
    //基本チェック。必須チェックがある場合は先にそっちが実行される
    static public function checkFileBase($param,$arg_post_key){
        if(self::$file_upload){
            $end = self::$file_must ? 5 : 4;//mustを飛ばす
            for ($i=1;$i<$end;$i++){
                if(strcasecmp($param['error'],$i) == 0){
                    self::setError($arg_post_key,constant(constant('E_SYSTEM_FILE_'.$i)));
                }else{
                    return TRUE;
                }
            }
        }
        return TRUE;
    }

    //5M
    static public function checkFileSize($param,$arg_size){
        if(self::$file_upload){
            return filesize($param['tmp_name']) > $arg_size ? FALSE : TRUE;
        }
        return TRUE;
    }
    
    /*define('WIDTH_HEIGHT_EQUAL',  0);//横幅、縦幅一致
    define('WIDTH_HEIGHT_WITHIN',  1);//横幅、縦幅以内
    define('WIDTH_EQUAL_HEIGHT_WITHIN',  2);//横幅一致、縦幅以内
    define('WIDTH_WITHIN_HEIGHT_EQUAL',  3);//横幅以内、縦幅一致*/
    static public function checkFileImageSize($param,$arg_image_size = array('type'=>WIDTH_HEIGHT_EQUAL,'width'=>'0','height'=>'0')){
        if(self::$file_upload){
            $ary_image_size = getimagesize($param['tmp_name']);
            switch ($arg_image_size['type']){
                case WIDTH_HEIGHT_EQUAL:
                    return $ary_image_size['0'] == $arg_image_size['width'] && $ary_image_size['1'] == $arg_image_size['height'] ? TRUE : FALSE;
                break;
                case WIDTH_HEIGHT_WITHIN:
                    return $ary_image_size['0'] <= $arg_image_size['width'] && $ary_image_size['1'] <= $arg_image_size['height'] ? TRUE : FALSE;
                break;
                case WIDTH_EQUAL_HEIGHT_WITHIN:
                    return $ary_image_size['0'] == $arg_image_size['width'] && $ary_image_size['1'] <= $arg_image_size['height'] ? TRUE : FALSE;
                break;
                case WIDTH_WITHIN_HEIGHT_EQUAL:
                    return $ary_image_size['0'] <= $arg_image_size['width'] && $ary_image_size['1'] == $arg_image_size['height'] ? TRUE : FALSE;
                break;
                case WIDTH_HEIGHT_OVER:
                    return $ary_image_size['0'] >= $arg_image_size['width'] && $ary_image_size['1'] >= $arg_image_size['height'] ? TRUE : FALSE;
                break;
                default:
                    return FALSE;
                break;
            }
        }
        return TRUE;
    }

    static public function checkFileType($param,$arg_type){
        if(self::$file_upload){
            $ext = strtolower(substr($param['name'],-3));
            if(is_array($arg_type)){
                return in_array($ext,$arg_type) ? TRUE : FALSE;
            }else{
                return strcasecmp($ext,$arg_type) == 0 ? TRUE : FALSE;
            }
        }
        return TRUE;
    }

    //date seminar check special/////////////////////////////////////////////
    //管理の選択
    static protected function checkOwner($param,$arg){
        if(strcasecmp($param,'honbu_radio') == 0){
            return TRUE;
        }elseif(strcasecmp($param,'hub_radio') == 0){
            return TRUE;
        }
        return FALSE;
    }

    //本部管理の必須チェック
    static protected function checkHonbuMust($param,$arg){
        return strcasecmp($_POST['owner'],'honbu_radio') == 0 ? self::checkMust($param,$arg) : TRUE;
    }

    //本部管理の数値チェック
    static protected function checkHonbuInt($param,$arg){
        return strcasecmp($_POST['owner'],'honbu_radio') == 0 ? self::checkInt($param,$arg) : TRUE;
    }

    //開催校管理の必須チェック
    static protected function checkHubMust($param,$arg){
        return strcasecmp($_POST['owner'],'hub_radio') == 0 ? self::checkMust($param,$arg) : TRUE;
    }

    //開催校管理の数値チェック
    static protected function checkHubInt($param,$arg){
        return strcasecmp($_POST['owner'],'hub_radio') == 0 ? self::checkInt($param,$arg) : TRUE;
    }

    //開催校管理、場所が開催校ではない場合の必須チェック
    static protected function checkHubLocationMust($param,$arg){
        return strcasecmp($_POST['owner'],'hub_radio') == 0 && isset($_POST['hub_location_check']) && strcasecmp($_POST['hub_location_check'],'hub_location_check') == 0 ? self::checkMust($param,$arg) : TRUE;
    }

    //開催校管理、場所が開催校ではない場合の数値チェック
    static protected function checkHubLocationInt($param,$arg){
        return strcasecmp($_POST['owner'],'hub_radio') == 0 && isset($_POST['hub_location_check']) && strcasecmp($_POST['hub_location_check'],'hub_location_check') == 0 ? self::checkInt($param,$arg) : TRUE;
    }
    ///////////////////////////////////////////////////////////////////////


    static public function replaceTab($param,$arg){
        $_POST[$arg] = str_replace(array("\t"),' ', $_POST[$arg]);//半角スペースに変換
        return TRUE;
    }

    //重要な関数
    //全ての入力系のものから使用不可とする文字列を除去、又は変換する関数
    /*mb_convert_kana()の一覧

    以下では、オプションの解説をしています。
    なお、オプションを指定しない場合、
    "KV"(「半角ｶﾀｶﾅ」を「全角カタカナ」に変換し、かつ、
    濁点付きの文字を一文字に変換するよう)になっています。

    r : 「全角」英字を「半角(ﾊﾝｶｸ)」に変換
    R : 「半角(ﾊﾝｶｸ)」英字を「全角」に変換
    n : 「全角」数字を「半角(ﾊﾝｶｸ)」に変換
    N : 「半角(ﾊﾝｶｸ)」数字を「全角」に変換
    a : 「全角」英数字を「半角(ﾊﾝｶｸ)」に変換
    A : 「半角(ﾊﾝｶｸ)」英数字を「全角」に変換
    s : 「全角」スペースを「半角(ﾊﾝｶｸ)」に変換
    S : 「半角(ﾊﾝｶｸ)」スペースを「全角」に変換
    k : 「全角片仮名」を「半角(ﾊﾝｶｸ)片仮名」に変換
    K : 「半角(ﾊﾝｶｸ)片仮名」を「全角片仮名」に変換
    h : 「全角ひら仮名」を「半角(ﾊﾝｶｸ)片仮名」に変換
    H : 「半角(ﾊﾝｶｸ)片仮名」を「全角ひら仮名」に変換
    c : 「全角かた仮名」を「全角ひら仮名」に変換
    C : 「全角ひら仮名」を「全角かた仮名」に変換
    V : 濁点付きの文字を一文字に変換。"K","H"と共に使用します。*/

    static public function replaceInput($param,$arg){
        $s = $_POST[$arg];
        $s = mb_convert_kana( $s, "KV" ,'UTF-8');//全角片仮名に
        //タブ文字
        $s = str_replace(array("\t"),' ',$s);//半角スペースに変換
        $_POST[$arg] = $s;
        return TRUE;
    }
    
    static protected $extends = '';
    
    static private function setError($post_key,$message){
        self::$error[$post_key] = $message.self::$extends;
        self::$stop = TRUE;//エラーがある場合は自動的にstop
    }

    static private function setLoopError($post_key,$number,$message){
        self::$error[$post_key][$number] = $message;
        self::$stop = TRUE;//エラーがある場合は自動的にstop
    }

    static protected function checkError($check_list){
        foreach($check_list as $post_key => $check){
            self::$stop = FALSE;
            if(isset($_POST[$post_key]) && !is_array($_POST[$post_key])) $_POST[$post_key] = trim($_POST[$post_key]);//trim
            foreach($check as $array){
                self::$extends  = '';
                if(isset($array['is_file']) && $array['is_file']){

                    if(!self::$stop && !call_user_func(array('checkManager',$array['func']),@$_FILES[$post_key],$array['arg'])){
                        self::setError($post_key,$array['message']);
                    }
                }else{
                    if(!self::$stop && !call_user_func(array('checkManager',$array['func']),@$_POST[$post_key],$array['arg'])){
                    //if(!isset($_POST[$post_key]) || !self::$stop && !call_user_func(array('checkManager',$array['func']),$_POST[$post_key],$array['arg'])){
                        self::setError($post_key,$array['message']);
                    }
                }
            }
        }
    }

    static protected function checkLoopError($check_list){
        foreach($check_list as $post_key => $check){
            if(isset($_POST[$post_key]) && is_array($_POST[$post_key])){
                foreach ($_POST[$post_key] as $number => $value){
                    self::$stop = FALSE;
                    foreach($check as $array){
                        if(!self::$stop && !call_user_func(array('checkManager',$array['func']),$value,$array['arg'])){
                            self::setLoopError($post_key,$number,$array['message']);
                        }
                    }
                }
                
            }
        }
    }
}
?>