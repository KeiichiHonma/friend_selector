<?php
class parameterManager
{
    public    $oid;//更新時等で使用される
    public    $parameter = null;
    
    //------------------------------------------------------
    // ハッシュ生成
    //------------------------------------------------------
    private function static_genSalt()
    {
        static $_init = FALSE;
        if( ! $_init )
        {
            mt_srand( time() ^ getmypid() );
            $_init = TRUE;
        }

        $b = array();
        for( $i = 0; $i < 4; $i++ )
        {
            // ASCII 32-126 are printable
            $b[] = mt_rand(32, 126);
        }

        return pack('C4', $b[0], $b[1], $b[2], $b[3]);
    }
    
    protected function static_hashPassword( $password )
    {
        $salt = $this->static_genSalt();
        $hash = sha1( $salt . $password );
        return array( 'salt'=>$salt, 'hash'=>$hash );
    }
    
    public function get($key){
        if(array_key_exists($key,$this->parameter)){
            return $this->parameter[$key];
        }else{
            return FALSE;
        }
        
    }

    public function set($key,$value){
        $this->parameter[$key] = trim($value);
    }

    public function drop($key){
        unset($this->parameter[$key]);
    }

    public function init(){
        $this->parameter = null;
    }

    protected function readyAddParameter($bl = TRUE,$time = null){
        $time = is_null($time) ? time() : $time;
        $this->set(DATABASE_OID_NAME,'');
        if($bl){
            $this->set('ctime',$time);
            $this->set('mtime',$time);
        }
    }

    protected function readyUpdateParameter($oid,$bl = TRUE,$time = null){
        $this->oid = $oid;
        $time = is_null($time) ? time() : $time;
        if($bl) $this->set('mtime',$time);
    }
    
    //special更新の場合。_idの指定なし
    protected function readyUpdateSpecialParameter($bl = TRUE,$time = null){
        $time = is_null($time) ? time() : $time;
        if($bl) $this->set('mtime',$time);
    }

    protected function readyDeleteParameter($oid){
        $this->oid = $oid;
    }

}
?>