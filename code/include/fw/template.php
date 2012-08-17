<?php
require_once('smarty/Smarty.class.php');
class template extends Smarty{
    /**
    * FunctionName
    *
    * @param type $name summary
    * @return type summary
    */
    function __construct(){
        /*
        $cache_lifetimeによって生存時間が限られています。デフォルト値は3600秒です。
        */
        $this->caching = false;//デバッグ：false,通常：true
        /*
        $compile_checkが有効の時、キャッシュファイルに入り組んだすべてのテンプレートファイルと設定ファイルは修正されたかどうかをチェックされます。
        もしキャッシュが生成されてからいくつかのファイルが修正されていた場合、キャッシュは即座に再生成されます。
        これは最適なパフォーマンスのためには僅かなオーバーヘッドになるので、$compile_checkはfalseにして下さい。
        */
        $this->compile_check = true;//デバッグ：true,通常：false
        $this->template_dir = $_SERVER['DOCUMENT_ROOT'].'/smarty/templates/';
        $this->compile_dir  = $_SERVER['DOCUMENT_ROOT'].'/smarty/templates_c/';
        $this->config_dir   = $_SERVER['DOCUMENT_ROOT'].'/smarty/configs/';
        $this->cache_dir    = $_SERVER['DOCUMENT_ROOT'].'/smarty/cache/';
        //$this->default_modifiers = array('escape:"html"');//デフォルトでエスケープ
    }

    function setCaching($isDebug){
        $bl = $isDebug ? FALSE : TRUE;//デバッグ：false,通常：true
        $this->caching = $bl;
    }

    function setCompileCheck($isDebug){
        $this->compile_check = $isDebug;//デバッグ：true,通常：false
    }

    function readyTemplate($isDebug){
        //不明な動きが多いためキャッシュは使用しない
        //$this->setCaching($isDebug);
        //$this->setCompileCheck($isDebug);
    }

}
?>
