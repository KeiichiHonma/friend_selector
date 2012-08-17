<?php
class positionManager
{
    static public $position = array();
    static protected $page;
    
    static protected function makeSitePosition($isSystem = FALSE){
        global $con;
        $paths = explode('/',$con->pagepath);

        $array = self::$page;
        $before_array = $array;
        $i = 1;
        $url = '';
        $count = count($paths);
        $name_key = 'name';

        foreach ($paths as $path){
            if($path != ''){
                $array = $array[$path];
                if($path != 'index'){
                    //$url .= $array['ssl'] ? FSURLSSL : FSURL;
                    //並列の場所にindexが存在するか確認する
                    if(array_key_exists('index',$before_array)){
                        $url .= $i == 1 ? '/' : $before_path.'/';
                        //$http = $before_array['index']['ssl'] ? FSURLSSL : FSURL;
                        $http = $before_array['index']['ssl'] ? FSURL : FSURL;
                        self::$position[] = array('url'=>$http.$url,'name'=>$before_array['index'][$name_key]);//1つ前のindex
                    }else{
                        $url .= $before_path.'/';
                    }
                    
                    //階層の途中かどうか確認
                    if(array_key_exists($name_key,$array)){
                        //最終階層だった
                        $url .= $path;
                        $http = $array['ssl'] ? FSURLSSL : FSURL;
                        //定義関数が必要なページか確認
                        if(!is_null($array['func'])){
                            $arg = call_user_func(array('positionManager',$array['func']));
                            self::$position[] = array('url'=>$http.$url.$arg,'name'=>$array[$name_key]);
                        }else{
                            self::$position[] = array('url'=>$http.$url,'name'=>$array[$name_key]);
                        }
                    }else{
                        //階層の途中.パスの保存
                        $before_path = $path;
                    }
                //indexが指定された
                }else{
                    $url .= $i == 1 ? '/' : $before_path.'/';//トップのindex処理
                    //$http = $array['ssl'] ? FSURLSSL : FSURL;
                    $http = $array['ssl'] ? FSURL : FSURL;
                    self::$position[] = array('url'=>$http.$url,'name'=>$array[$name_key]);
                }
                if($count == $i){
                    if(!is_null($array['gnavi'])) self::setGlobalNavi($array['gnavi']);//グローバルナビ
                    if(!is_null($array['snavi'])) self::setSubNavi($array['snavi']);//サブナビ
                }
                $i++;
                $before_array = $array;
            }
        }
    }

    static public function setSitePosition(){
        global $con;
        $con->t->assign('position',self::$position);
    }

    static protected function makePositionPair($url,$name){
        return array('url'=>$url,'name'=>$name);
    }

    static function getCurrentValue($key){
        global $con;
        $pages = explode('/',$con->pagepath);
        $count = count($pages);
        if($count == 1){
            return array_key_exists($key,self::$page) ? self::$page[$key] : FALSE;
        }else{
            $array = self::$page;
            $i = 1;
            foreach ($pages as $page){
                if($count == $i){//最後
                    return $array[$page][$key];
                }
                $array = $array[$page];
                $i++;
            }
        }
        return FALSE;
    }

    static function setGlobalNavi($navi){
        global $con;
        $con->t->assign('gnavi',$navi);
    }

    static function setSubNavi($navi){
        global $con;
        $con->t->assign('snavi',$navi);
    }

    static function positionTrim($string){
        return mb_strimwidth($string,0,160,'…','UTF-8');
    }
}
?>
