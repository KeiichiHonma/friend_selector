<?php
require_once('fw/error_code.php');
class errorManager
{
    public $error = array();
    
    static public function throwError($error_code){
        global $con;
        
        $err['msg'] = constant($error_code);
        // Smartyへのアサイン
        $con->t->assign('errorlist', $err);

        // display it
        $con->t->display('error.tpl');
        die();
    }
}
?>