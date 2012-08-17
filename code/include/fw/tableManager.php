<?php
//table
//user
define('T_MATCH',                 'match.tab_match_friend');
define('A_MATCH',                 'm');


define('MINIMUM',        0);//最小カラム
define('COMMON',         1);//通常含めるカラム
define('DETAIL',         2);//詳細に含めるカラム
define('ALL',            3);//通常含めないカラム
require_once('fw/utilManager.php');
class tableManager
{
    static private $tmp_column;
    static private $tmp_as;
    static private $tmp_check;
    static private $tmp_alias;
    static protected $columns = null;
    
    static protected function get($table_info,$type){
        self::$columns = null;//init
        array_walk_recursive($table_info,array('tableManager','callbackTypeColumn'),$type);
        return self::$columns;
    }

    static protected function getAlias($table_info,$type_alias){
        self::$columns = null;//init
        array_walk_recursive($table_info,array('tableManager','callbackAliasColumn'),$type_alias);
        return self::$columns;
    }

    static protected function getInput($table_info){
        self::$columns = null;//init
        array_walk_recursive($table_info,array('tableManager','callbackInputColumn'));
        return self::$columns;
    }

    static protected function getGroup($table_info,$group){
        self::$columns = null;//init
        array_walk_recursive($table_info,array('tableManager','callbackGroupColumn'),$group);
        return self::$columns;
    }

    //任意指定
    static protected function getSpecial($table_info,$assign_columns){
        self::$columns = null;//init
        array_walk_recursive($table_info,array('tableManager','callbackSpecialColumn'),$assign_columns);
        return self::$columns;
    }

    static private function callbackTypeColumn($item, $key,$type)
    {
        if($key == 'column') self::$tmp_column = $item;
        //指定type値以下の場合のみ取得カラムとなる
        if($key == 'type' && $item <= $type) self::$columns[] = utilManager::checkPrefix(self::$tmp_column);
    }
    //aliasあり
    static private function callbackAliasColumn($item, $key,$type_alias)
    {
        if($key == 'column') self::$tmp_column = $item;
        if($key == 'as') self::$tmp_as = $item;
        //指定type値以下の場合のみ取得カラムとなる
        if($key == 'type' && $item <= $type_alias['type']) self::$columns[] = utilManager::checkPrefix(self::$tmp_column,self::$tmp_as,$type_alias['alias']);
    }

    static private function callbackInputColumn($item, $key)
    {
        if($key == 'column') self::$tmp_column = $item;
        if($key == 'input' && $item == TRUE) self::$columns[] = self::$tmp_column;//prefixはつけない
    }

    //member edit での個別取得で必要
    static private function callbackGroupColumn($item,$key,$group)
    {
        if($key == 'column') self::$tmp_column = $item;
        if($key == 'group' && $item == $group) self::$columns[] = self::$tmp_column;//prefixはつけない
    }

    static private function callbackSpecialColumn($item, $key,$assign_columns)
    {
        if($key == 'column'){
            self::$tmp_check = null;//チェックリスト初期化
            self::$tmp_column = null;
            //指定したカラム配列と合致する場合のみ取得カラムとなる
            if(array_search($item,$assign_columns) !== FALSE) self::$tmp_column = $item;
        }
        if(is_numeric($key)) self::$tmp_check[] = $item;
        if(!is_null(self::$tmp_column)) self::$columns[self::$tmp_column] = self::$tmp_check;
    }
}
?>