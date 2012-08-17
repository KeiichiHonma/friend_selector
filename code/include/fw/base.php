<?php
//==========================================================
//  基本クラス
//                                               2007/9/某日
//==========================================================
class base
{
    public $path_info;

    public $path_sp = '';
    public $isSp = FALSE;//検索系
    public $sp_value = FIRSTSP;
    public $index_section_number = 5;

    public $sp_manager = array();
    public $sp_limit = array();//limit使用
    public $bl_next = FALSE;
    private $block_int = 0;

    function __construct(){
        $this->makePathInfo();
        $this->sp_limit = array('from'=>0,'to'=>$this->sp_value);//limit使用
    }
    
    function makePathInfo(){
        if(!isset($_SERVER['PATH_INFO'])) return FALSE;
        $ex_path = explode('/',$_SERVER['PATH_INFO']);
        array_shift($ex_path);//空
        if(count($ex_path) > 1){
            foreach($ex_path as $key => $val){
                if(array_key_exists($key +1,$ex_path)) $this->path_info[$val] = $ex_path[$key +1];
                if($val == 'sp' && is_numeric($ex_path[$key +1])) $this->isSp = TRUE;//spフラグ
            }
        }else{
            return FALSE;
        }
    }
    
    function getPath($key,$throw = TRUE){
        if(isset($this->path_info[$key]) && strlen($this->path_info[$key]) > 0) return $this->path_info[$key];
        if($throw){
            require_once('fw/errorManager.php');
            errorManager::throwError(E_CMMN_URL_WRONG);
        }else{
            return FALSE;
            //$this->redirectPage();
        }
    }



    public function changeSpValue($value = FIRSTSP){
        $this->sp_value = $value;
    }

    public function changeIndexSectionNumber($value = 10){
        $this->index_section_number = $value;
    }

    public function makeLimitTo(){
        $this->sp_manager = array();
        $this->block_int = 0;
        if($this->isSp){
            //def値の倍数に変換
            //不正な数字を入力されるのを防ぐ
            $this->block_int = floor($this->path_info['sp'] / $this->sp_value) * $this->sp_value;//小数点切り捨て
            //if($count < $this->block_int) $this->block_int = 0;
        }
        $this->sp_limit['from'] = $this->block_int;
        $this->sp_limit['to'] = $this->sp_value;
    }

    function assignSp($count,$page_arg){
        
        if(!$count) return FALSE;
        global $con;
        if($count < $this->block_int) $this->block_int = 0;
        if($count <= $this->sp_value){
            $con->t->assign('sp_manager', FALSE);
            return FALSE;
        }
        if($count > 0){
            $index_section_base = $this->index_section_number * $this->sp_value;
            
            //ページリスト生成
            $page_count = ceil($count / $this->sp_value);

            $index = 0;
            $sp = 0;
            for($i=0;$i<$page_count;$i++){
                $index = $index + 1;
                if($this->block_int == $sp){
                    /*
                    0～0.9 セクション1
                    1～1.9 セクション2
                    2～2.9 セクション3
                    小数点1位まで求め、+1して割り切れる場合に対応する。
                    割り切れる場合は次のセクションになるため。
                    また、配列のindexは0スタートのため-1している
                    */
                    $validate_section_number = round($this->block_int / $index_section_base,1);//どのセクションを表示するか
                    $validate_section_index = floor($validate_section_number + 1) - 1;//chunkのindex用に

                    $this->sp_manager['list'][$index] = $this->getSpArray($page_arg,$sp,TRUE);//current
                    $current = $index;
                    $this->sp_manager['current'] = $this->getSpArray($page_arg,$this->sp_manager['list'][$current]['sp']);
                    $sp_string1 = $sp == 0 ? 1 :$sp + 1;
                    $sp_string2_number = $sp + $this->sp_value;
                    $sp_string2 = $count >= $sp_string2_number ? $sp_string2_number : $count;
                    $this->sp_manager['current']['display'] = $sp_string1.'～'.$sp_string2;//件目
                }else{
                    $this->sp_manager['list'][$index] = $this->getSpArray($page_arg,$sp);
                }
                $sp = $sp + $this->sp_value;
            }
            if(array_key_exists($current+1,$this->sp_manager['list'])){//次があるかどうか
                $this->sp_manager['next'] = $this->getSpArray($page_arg,$this->sp_manager['list'][$current+1]['sp']);
            }
            if(array_key_exists($current-1,$this->sp_manager['list'])){//前があるかどうか
                $this->sp_manager['before'] = $this->getSpArray($page_arg,$this->sp_manager['list'][$current-1]['sp']);
            }
            
            $sections = array_chunk($this->sp_manager['list'],$this->index_section_number,TRUE);
            $validate_section = $sections[$validate_section_index];//有効なセクション
            $this->sp_manager['list'] = $validate_section;//再セット
            $con->t->assign('sp_manager', $this->sp_manager);
        }
    }
    
    private function getSpArray($page_arg,$sp,$isCurrent = FALSE){
        global $con;
        $url = $sp == 0 ? $page_arg : $page_arg.'/sp/'.$sp;
        if(count($_GET) > 0){
            if($con->pagename == 'search'){
                $url .= '?';
                $toPut = FALSE;
                foreach ($_GET as $key => $value){
                    if($key == 'keyword') $value = rawurlencode($value);
                    if ( $toPut ) {
                        $url .= '&';
                    } else {
                        $toPut = TRUE;
                    }
                    $url .= $key.'='.$value;
                }
            }
        }
        return array('sp'=>$sp,'isCurrent'=>$isCurrent,'url'=>$url);
    }

    /*JISは半角1バイト全角2バイトだが、EUC-JPは半角カナが2バイトなのと一部漢字が3バイト、
    UTF-8は1～6バイトまで（仕様上）の可変長となっている。
    UTF-8は正しく切り詰めることができないので、一旦SJISに変換して処理する*/

    public function mb_strimbyte( $str,$strimbyte = 40, $marker = '…'){
        global $con;
        
        $TMP_ENC = 'SJIS';
        $encoding = 'UTF-8';
        if($con->isMobile){
            mb_substitute_character('none');//存在しない文字コードがある場合（いわゆるゲタ or ?になる場合）の処理を設定をするため = 削除
            $e_str  = mb_convert_kana( mb_convert_encoding($str,$TMP_ENC, $encoding), "akV",$TMP_ENC);
        }else{
            $e_str  = $this->convertSpecial($str);
        }

        $e_marker  = $this->convertSpecial($marker);
        if( strlen( $e_str ) > $strimbyte ){
            $mksize = strlen( $e_marker );
            $result = mb_strcut( $e_str, 0, $strimbyte-$mksize, $TMP_ENC );
            $str = mb_convert_encoding( $result . $e_marker, $encoding, $TMP_ENC );
        }
        return $str;
    }

    public function make_text($string,$isAutoLink = SMARTY_BOOL_ON,$strimbyte = SMARTY_BOOL_OFF){
        require_once('fw/uri.php');
        $uri = new uri();
        if($strimbyte !== SMARTY_BOOL_OFF){
            $string = $this->mb_strimbyte($string,$strimbyte);
        }
        $r = $uri->autolink($string,TRUE,$isAutoLink);
        $exp = explode("\t",$r);//\t
        $r_string = '';
        $bl_before_url = FALSE;
        $bl_before_end_br = FALSE;
        $i = 0;
        foreach ($exp as $key => $value){
            //現在の値の状況を確認
            $bl_current_url = ereg($uri->url_pattern, $value);
            $bl_current_end_br = preg_match('/[\r\n]$/', $value);//文末が改行か
            $bl_current_start_br = preg_match('/^[\r\n]/', $value);//文頭が改行か
            if($isAutoLink == SMARTY_BOOL_ON && $bl_current_url){
                if($bl_before_end_br != 1){
                    if($i == 1 && $exp[0] == ''){
                    }else{
                        $r_string .= "\r\n";//直前の文字列が改行で終わっていなければ
                    }
                    
                }
                $r_string .= $value;
            }else{
                if($bl_before_url && $bl_current_start_br == 0) $r_string .= "\r\n";//現在のの文字列が改行で始まっていた場合
                $r_string .= $value;
            }
            $bl_before_url = $bl_current_url;
            $bl_before_end_br = $bl_current_end_br;
            $i++;
        }
        
        return $isAutoLink == SMARTY_BOOL_ON ? nl2br($r_string) : $r_string;
    }
    public function mb_strim_square( $string,$strimbyte = 180,$brLength = 60,$marker = '…',$isAutoLink = SMARTY_BOOL_OFF){
        require_once('fw/uri.php');
        $uri = new uri();
        $result1 = $uri->autolink($string,TRUE,$isAutoLink);

        //$pa = '[a-zA-Z0-9_\(\)\.\/\~\%\:\#\?=&\;\-\+\,]{'.$brLength.',}';
        $result2 = $this->mb_strimbyte($result1,$strimbyte);
        return $this->checkByteBR($result2,$brLength,$isAutoLink);
    }

    public function mb_square( $string,$brLength = 60,$isAutoLink = SMARTY_BOOL_ON){
        require_once('fw/uri.php');
        $uri = new uri();
        $result1 = $uri->autolink($string,TRUE,$isAutoLink);
        return nl2br($this->checkByteBR($result1,$brLength,$isAutoLink));
    }

    private function checkByteBR($string,$brLength,$isAutoLink){
        $exp = explode("\t",$string);//\t

        require_once('fw/uri.php');
        $uri = new uri();

        $pa = '[a-zA-Z0-9_\(\)\.\/\~\%\:\#\?=&\;\-\+\,]{'.$brLength.',}';
        $r_string = '';
        $bl_before_url = FALSE;
        $bl_before_end_br = FALSE;

        foreach ($exp as $key => $value){
            //現在の値の状況を確認
            $bl_current_url = ereg($uri->url_pattern, $value);
            $bl_current_end_br = preg_match('/[\r\n]$/', $value);//文末が改行か
            $bl_current_start_br = preg_match('/^[\r\n]/', $value);//文頭が改行か
            if($isAutoLink == SMARTY_BOOL_ON && $bl_current_url){
                if($bl_before_end_br != 1) $r_string .= "\r\n";//直前の文字列が改行で終わっていなければ
                $r_string .= $value;
            }else{
                if($bl_before_url && $bl_current_start_br == 0) $r_string .= "\r\n";//現在のの文字列が改行で始まっていた場合
                $bl = ereg($pa, $value);
                if($bl !== FALSE){
                    $result1 = $this->convertSpecial($value);
                    $result2='';
                    $int = floor(strlen($result1)/$brLength);
                    for($i=0;$i<strlen($result1)/$brLength;$i++){
                        //$result2.=substr($result1,$i*$brLength,$brLength)."\r\n";
                        if($i < $int){
                            $result2.=substr($result1,$i*$brLength,$brLength)."\r\n";
                        }else{
                            $result2.=substr($result1,$i*$brLength,$brLength);
                        }
                        
                    }
                    $r_string .= mb_convert_encoding( $result2,'UTF-8','SJIS');
                }else{
                    $r_string .= $value;
                }
            }
            $bl_before_url = $bl_current_url;
            $bl_before_end_br = $bl_current_end_br;
        }
        return $r_string;
    }

    public function setSEO($array){
        global $con;
        $con->t->assign('title',$array['title']);
        $con->t->assign('meta_keyword',$array['keyword']);
        $con->t->assign('meta_desc',str_replace(array("\r\n","\n","\r"), '', $array['desc']));
    }

    public function convertSpecial($str){
        return mb_convert_encoding( $str,'SJIS','UTF-8');
    }

    //結果セットがFALSE
    public function handleFALSE($result,$page = ''){
        if(!$result) $this->redirectPage($page);
        return $result;
    }

    public function redirectPage($page = ''){
        header("Location: /".$page);
        die();
    }

    public function redirectMobilePage($page = 'index'){
        global $con;
        $guid = $con->isDocomo ? '?guid=ON' : '';
        header("Location: ".CAURL."/".$page.$guid);
        die();
    }

    public function redirect301($page = ''){
        header( "HTTP/1.1 301 Moved Permanently" );
        header("Location: /".$page);
        die();
    }

    public function redirectForIndex($page = '',$isSSL = FALSE){
        header( "HTTP/1.1 301 Moved Permanently" );
        $isSSL ? header("Location: https://".$_SERVER['SERVER_NAME']."/".$page) : header("Location: http://".$_SERVER['SERVER_NAME']."/".$page);
        die();
    }

    public function redirect302($page = ''){
        header( "HTTP/1.1 302 Moved Permanently" );
        header("Location: /".$page);
        die();
    }

    //画像へのパス取得
    public function getFilePath($fid,$ext,$isMobile = FALSE){
        global $con;
        $this->dir_file = '';//初期化．しないと前のパスを記憶してまう
        //ディレクトリとファイル名
        $dir1 = '0';
        $dir2 = '0';
        $file = '';
        
        $len = strlen( $fid );
        if ( $len > 6 ) {
            $limit = $len - 6;
            $dir1 = substr( $fid, 0, $limit );
            $dir2 = substr( $fid, $limit, 3 );
            $file = substr( $fid, $limit + 3, 3 );
        } else if ( $len > 3 ) {
            $limit = $len - 3;
            $dir2 = substr( $fid, 0, $limit );
            $file = substr( $fid, $limit, 3 );
        } else {
            $file = $fid;
        }
        //$t = FILES_PATH;
        $t = !$isMobile ? FILES_PATH : $con->absolute_path.FILES_DIR;
        if($isMobile) $this->dir_file = '';
        if ( ! is_dir( $t ) )
        {
            require_once('fw/errorManager.php');
            errorManager::throwError(E_CMMN_DIR_NO_EXIST);
        }
        $t .= "/${dir1}";
        if ( ! is_dir( $t ) )
        {
            require_once('fw/errorManager.php');
            errorManager::throwError(E_CMMN_DIR_NO_EXIST);
        }
        $t .= "/${dir2}";
        if ( ! is_dir( $t ) )
        {
            require_once('fw/errorManager.php');
            errorManager::throwError(E_CMMN_DIR_NO_EXIST);
        }
        //is_readable -- ファイルが読み込み可能かどうかを知る
        if ( is_file( $t . "/${file}".'.'.$ext ) && is_readable( $t . "/${file}".'.'.$ext ) )
        {
            $this->dir_file = "/${dir1}/${dir2}/${file}".'.'.$ext;
        }
        //return FILES_DIR.$this->dir_file.'?'.$con->tail_number;
        return !$isMobile ? FILES_DIR.$this->dir_file.'?'.$con->tail_number : FILES_DIR.$this->dir_file;
        //return FILES_DIR.$this->dir_file.$isTailNumber ? '?'.$con->tail_number : '';
    }
}
?>
