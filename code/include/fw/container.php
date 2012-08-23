<?php
require_once('fw/define.php');
require_once('fw/database.php');//db
require_once('fw/template.php');//template
require_once('fw/base.php');//template
//require_once('area/logic.php');
//require_once('type/logic.php');
require_once('fw/sessionManager.php');
require_once('fw/positionManager.php');
require_once('fw/csrf.php');//csrf処理
class container
{
    public $db = null;
    public $t = null;
    public $base = null;
    
    public $area = null;
    public $type = null;
    public $isPost = FALSE;
    public $ini;
    public $isDebug = FALSE;
    public $isStage = FALSE;
    public $isAlipay = TRUE;
    public $isMaintenance = FALSE;
    public $isSystem = FALSE;
    public $isInquiry = FALSE;
    public $lastJudge = TRUE;//ロールバックかコミットの判断
    public $session;
    public $csrf;

    public $pagepath;
    public $pagename;
    public $tail_number;
    public $isDocomo = FALSE;
    
    public $isIluna = FALSE;//メンテナンス中の表示
    
    //携帯でfiles配下の画像を表示するための設定。ステージングと本番で設定が異なるため、init処理で設定しています。
    public $url;
    public $m_url;
    public $absolute_path;//相対パスだとm.を見てしまうため、絶対パスとして持っている必要があるため
    
    public $isSumitomoMode = FALSE;
    public $doc_root = FALSE;

    //permissions facebook側からの戻り値と合わせるため形が特殊
    public $permissions = array(array('read_friendlists'=>'1','manage_friendlists'=>'1','user_birthday'=>'1','friends_birthday'=>'1','user_groups'=>'1','friends_groups'=>'1','user_relationships'=>'1','friends_relationships'=>'1'));
    public $permissions_comma = '';

    function __construct($isSimple = FALSE){
        $this->t = new template();
        
        //page set メンテナンスモード除去ページ判定で先に必要
        preg_match('/\/([\D]+)\./i', $_SERVER['SCRIPT_NAME'], $matches);
        if(isset($matches[1])){
            $this->pagepath = $matches[1];
            //is system ?
            $this->isSystem = ereg("^system", $matches[1]);
            
            //is inquiry ?
            if(ereg("^inquiry", $matches[1])){
                $this->t->assign('is_inquiry',0);
            }
        }
        $this->pagename = basename($_SERVER['SCRIPT_NAME'],'.php');

        $cache = $this->pagename == 'input' || $this->pagename == 'login' ? TRUE : FALSE;
        
        
        if(!$isSimple)$this->checkIni();
        $this->t->readyTemplate($this->isDebug);
        
        //position include.ロケール変数が必要
        $this->isSystem ? require_once('fw/systemPosition.php') : require_once('fw/commonPosition.php');

        //以下はシンプルモードでは呼ばない
        if(!$isSimple){
            $this->session = new sessionManager($cache);//セッション開始
            $this->base = new base();
            $this->tail_number = time();
            $this->t->assign('tail_number',$this->tail_number);//末尾の数字
        }
        
        //権限
        $this->permissions_comma = implode(',',array_keys($this->permissions[0]));
    }

    public function checkPostCsrf(){
        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0 && strcasecmp($_POST['fs_method'],'POST') === 0){
            $this->isPost = TRUE;
            //check
            $this->csrf->validateToken(@$_POST['csrf_ticket']);
        }
    }

    public function readyPostCsrf(){
        $this->csrf = new csrf();
        $this->csrf->getToken();
    }

    private function checkIni(){
        $this->ini = parse_ini_file(SETTING_INI, true);
        
        if($this->ini['common']['isDebug'] == 0){//本番
            $this->isDebug = FALSE;

            if($this->ini['common']['isStage'] == 1){//ステージングサーバモード
                $this->isStage = TRUE;

                define('SERVER_NAME',      'tar.813.co.jp');
                $this->t->assign('stage',$this->isStage);
            }else{
                define('SERVER_NAME',      'www.tar.com');
            }
        }elseif($this->ini['common']['isDebug'] == 1){//デバッグモード
            $this->isDebug = TRUE;

            if($this->ini['common']['isStage'] == 1){//ステージングサーバモード
                $this->isStage = TRUE;

                define('SERVER_NAME',      'tar.813.co.jp');
                $this->t->assign('stage',$this->isStage);
            }else{
                define('SERVER_NAME',      'tar.hades.corp.813.co.jp');
            }
        }
        if(isset($_SERVER['SERVER_NAME'])){
            define('FSURL',            'http://'.$_SERVER['SERVER_NAME']);
            define('FSURLSSL',         'https://'.$_SERVER['SERVER_NAME']);
        }


        //メンテナンスモード
        if($this->ini['common']['isMaintenance'] == 1){
            //ilunaはOK
            if($this->ini['common']['isStage'] == 1){//ステージングサーバモード
                $ip = '192.168.0.52';
            }
            
            if($this->ini['common']['isDebug'] == 0){//本番
                $ip = '210.189.109.177';
            }
            
            if(!isset($_SERVER['REMOTE_ADDR']) || !isset($ip) || strcasecmp($_SERVER['REMOTE_ADDR'],$ip) != 0){
                if(strstr($this->pagepath,'payment') !== FALSE && ($this->pagename == 'return_url' || $this->pagename == 'return_url_test' || $this->pagename == 'notify_url' || $this->pagename == 'finish' || $this->pagename == 'error' || $this->pagename == 'alipay')){
                    //このページは処理を続ける
                    $this->isMaintenance = TRUE;
                    $this->t->assign('maintenance',$this->isMaintenance);
                }else{
                    header( "HTTP/1.1 302 Moved Temporarily" );
                    header("Location: ".FSURL.'/maintenance');
                    die();
                }
            }
        }
        $this->t->assign('debug',$this->isDebug);
    }

    public function safeExitRedraw(){
        //if($this->lastJudge) $this->db->commit();
        header("Location: ".$_SERVER['REQUEST_URI']);
        die();
    }

    public function safeExitRedirect($page,$isSSL = FALSE){
        //if($this->lastJudge) $this->db->commit();
        $isSSL ? header("Location: ".FSURLSSL.'/'.$page) : header("Location: ".FSURL.'/'.$page);
        //header("Location: ".FSURL.'/'.$page);
        die();
    }

    public function safeExit(){
        //if($this->lastJudge) $this->db->commit();
    }

    //no commit
    public function errorExitRedirect($page,$isSSL = FALSE){
        //header( "HTTP/1.1 301 Moved Permanently" );
        $isSSL ? header("Location: ".FSURLSSL."/".$page) : header("Location: ".FSURL."/".$page);
        die();
    }

    public function append($page = null){
        positionManager::setSitePosition();
        // display it
        is_null($page) ? $this->t->display($this->pagepath.'.tpl') : $this->t->display($page.'.tpl');
    }

    //リダイレクト用
    public function redraw($url = FSURL,$isPCURL = FALSE){
        if($this->lastJudge) $this->db->commit();
        header("Location: ".$url.$_SERVER['REQUEST_URI']);
        die();
    }
    
    //save系//////////////////////////////////////
    
    //save用再描画
    public function saveRedraw($key){
        $array = explode('/'.$key,$_SERVER['REQUEST_URI']);
        if(strstr($array[0],'/sx/0/sy/0') !== FALSE){
            $array = explode('/sx/0/sy/0',$array[0]);//無意味なので除去
        }
        
        
        $r = ereg("index$", $array[0]);
        
        if($r !== FALSE){
            $len = strlen($array[0]);
            header("Location: ".substr($array[0],0,$len-5));
        }else{
            header("Location: ".$array[0]);
        }
        die();
    }
    
    public function getSave(){
        return $this->session->get('save');
    }
    
    public function setSave($rid){
        $save = $this->getSave();
        $save[$rid] = $rid;
        $this->session->set('save',$save);
    }
    
    public function deleteSave($rid){
        unset($_SESSION['save'][$rid]);
    }

    public function allDeleteSave(){
        $_SESSION['save'] = FALSE;
    }

    public function getRandomString($nLengthRequired = 8){
        $sCharList = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        mt_srand();
        $sRes = '';
        for($i = 0; $i < $nLengthRequired; $i++)
            $sRes .= $sCharList{mt_rand(0, strlen($sCharList) - 1)};
        return $sRes;
    }
    
    public function isImg($img_path='')
    {
        if (!(file_exists($img_path) and $type=exif_imagetype($img_path))) return false;
        if (IMAGETYPE_GIF == $type) return 'gif';
        else if (IMAGETYPE_JPEG == $type) return 'jpg';
        else if (IMAGETYPE_PNG == $type) return 'png';
        return false;
    }

    //facebook権限チェック
    public function diffPermissions($fql_permissions){
        $result_array = array_intersect_assoc($this->permissions[0], $fql_permissions[0]);
        return count($this->permissions[0]) == count($result_array) ? true : false;
    }

    public function checkPermissions($fql_permissions){
        if( !$this->diffPermissions($fql_permissions) ){
            require_once('fw/errorManager.php');
            errorManager::throwError(E_CMMN_PERMISSIONS_ERROR);
        }
    }


}
?>