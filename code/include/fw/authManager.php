<?php
define('SECRET_KEY',                 'ILUNAKEY');

//member
define('SESSION_M_LOGIN',            'TARMLOGIN');
define('SESSION_M_HASH',             'TARMHASH');
define('SESSION_M_MID',              'TARMMID');
define('SESSION_M_NAME',             'TARMNAME');
define('SESSION_M_AID',              'TARMAID');
define('SESSION_M_SAID',             'TARMSAID');

//user
define('SESSION_U_LOGIN',            'TARULOGIN');
define('SESSION_U_HASH',             'TARUHASH');
define('SESSION_U_UID',              'TARUUID');
define('SESSION_U_NAME',             'TARUNAME');
define('SESSION_U_TYPE',             'TARUTYPE');
define('SESSION_U_SCID',             'CASCID');

class authManager
{
    //private $tar_secret      = 'ILUNAKEY';
    public $tar_login;
    public $tar_hash;
    public $tar_oid;//mid or uid
    public $tar_aid;
    public $tar_said;
    
    
    //以下認証後のみセットされる
    //member
    public $mid;
    public $member;
    public $member_aid;
    public $member_said;
    
    //user
    public $uid;
    public $user;
    
    function __construct($isMember = TRUE){
        if($isMember){
            $this->tar_login       = SESSION_M_LOGIN;
            $this->tar_hash        = SESSION_M_HASH;
            $this->tar_oid         = SESSION_M_MID;
            $this->tar_name        = SESSION_M_NAME;
            $this->tar_aid         = SESSION_M_AID;
            $this->tar_said        = SESSION_M_SAID;
        }else{
            $this->tar_login       = SESSION_U_LOGIN;
            $this->tar_hash        = SESSION_U_HASH;
            $this->tar_oid         = SESSION_U_UID;
            $this->tar_name        = SESSION_U_NAME;
            $this->tar_scid        = SESSION_U_SCID;
        }
    }

    public function makeHash($login_name){
        return md5($login_name.SECRET_KEY);
    }

    public function validatePassword( $password, $salt, $hash )
    {
        return (strcasecmp(sha1($salt.$password), $hash) === 0);
    }

    //------------------------------------------------------
    // セッションベースログインチェック
    //------------------------------------------------------
    protected function isLogin() {
/*var_dump($_SESSION);
die();*/
        global $con;
        if($con->session->get($this->tar_login) !== FALSE && $con->session->get($this->tar_hash) !== FALSE){
            return strcasecmp($con->session->get($this->tar_hash),self::makeHash($con->session->get($this->tar_login))) == 0 ? TRUE : FALSE;
        }else{
            return FALSE;
        }
    }

    public function validateLogin(){
        if(!auth::isLogin()){
            global $con;
            $con->base->redirectPage('login');
        }
    }
    
    //------------------------------------------------------
    // ログイン情報セット
    //------------------------------------------------------

    public function setLogin($login_object){
        global $con;
        $con->session->set($this->tar_login,$login_object[0]['col_mail']);
        $con->session->set($this->tar_hash,self::makeHash($login_object[0]['col_mail']));
        $con->session->set($this->tar_oid,$login_object[0]['_id']);
        $con->session->set($this->tar_name,$login_object[0]['col_name']);
    }
    public function unsetLogin(){
        if (isset($_COOKIE[CA_SESSION_NAME])) {
            setcookie(CA_SESSION_NAME, '', time() - 1800, '/');
        }
        $_SESSION = array();
        session_destroy();
    }

    //------------------------------------------------------
    // ログイン変数セット
    //------------------------------------------------------

    public function readyMember()
    {
        global $con;
        $this->mid = $con->session->get(SESSION_M_MID);
        $this->member = $con->session->get(SESSION_M_NAME);
        $this->member_aid = $con->session->get(SESSION_M_AID);
        $this->member_said = $con->session->get(SESSION_M_SAID);
        $con->t->assign('login_mid',$this->mid);
        $con->t->assign('login_member',$this->member);
        $con->t->assign('login_aid',$this->member_aid);
        $con->t->assign('login_said',$this->member_said);
        return !$this->mid || !$this->member ? FALSE : TRUE;
    }

    public function readyUser()
    {
        global $con;
        $this->uid = $con->session->get(SESSION_U_UID);
        $this->user = $con->session->get(SESSION_U_NAME);
        $con->t->assign('login_uid',$this->uid);
        $con->t->assign('login_user',$this->user);
        return !$this->uid || !$this->user ? FALSE : TRUE;
    }

}
?>