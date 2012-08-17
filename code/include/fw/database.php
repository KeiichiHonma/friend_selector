<?php
//==========================================================
//  DBクラス php5
//                                                2008/12/01
//==========================================================

define('DATABASE_TABLE_PREFIX', 'tab_nihonbashi_');
define('DATABASE_COLUMN_PREFIX', 'col_');
define('DATABASE_OID_NAME', '_id');

// MySQL JOIN types
define('DATABASE_INNER_JOIN','INNER JOIN');
define('DATABASE_LEFT_JOIN','LEFT JOIN');
define('DATABASE_RIGHT_JOIN','RIGHT JOIN');
define('DATABASE_CROSS_JOIN','CROSS JOIN');

//lock
define('DATABASE_NO_LOCK',           0 );
define('DATABASE_SHARED_LOCK',       1 );
define('DATABASE_EXCLUSIVE_LOCK',    2 );
define('DATABASE_DEFAULT_LOCK',      3 );

//special
define('DATABASE_AVG', 'AVG');
define('DATABASE_COUNT', 'COUNT');

//order
define('DATABASE_DESC', TRUE);//降順
define('DATABASE_ASC', FALSE);//昇順

define('ANSWER_BASE',  'ANSWER_BASE');
define('GOOD_BASE',  'GOOD_BASE');
define('PAGE_BASE',  'PAGE_BASE');

class database
{
    //DB
    private $strDBName        = "match";
    private $strHostName      = "localhost";
    private $strPort          = "3306";
    private $strUser          = "db_apollon";
    private $strPass          = "kyeoisihcihoi";
    private $err;
    private $strErrMsg        = "";
    public  $sqlData;
    public  $intResultRows     = 0;
    public  $intResultCols     = 0;
    private $found             = FALSE;
    public  $rows              = 0;
    private $debug             = FALSE;
    
    function __construct(){
        $blnStatus = TRUE;
        $intConn = mysql_connect($this->strHostName,$this->strUser,$this->strPass);
        if( isset($intConn)){
            if(TRUE != mysql_select_db( $this->strDBName, $intConn )){
                $this->err['msg'][] = $this->strDBName;
                $blnStatus = false;
            }
        }else{
            $blnStatus = false;
        }
        //$blnStatus == FALSE ? $this->throwDBError() : mysql_query('SET AUTOCOMMIT=0');
        $blnStatus == FALSE ? die() : mysql_query('SET AUTOCOMMIT=0');
        if($blnStatus == FALSE){
            $ini = parse_ini_file(SETTING_INI, true);
            if($ini['common']['isDebug'] == 0){//本番
                die();
            }else{
                $this->makeDebug();
                var_dump($this->err);
                die();
            }
        }else{
            mysql_query('SET AUTOCOMMIT=0');
        }
    }

    public function setFound(){
        $this->found = TRUE;
    }

    public function setDebug(){
        $this->debug = TRUE;
    }

    private function makeDebug(){
        require_once('fw/error_code_db.php');
        $this->err['debug'] = debug_backtrace();
        $this->err['msg'][] = $db_error[mysql_errno()][0];
        $this->err['msg'][] = mysql_error();
    }

    private function throwDBError(){
        global $con;

        $this->makeDebug();

        if($con->isDebug){
            $con->t->assign('errorlist', $this->err);
            $this->rollback();//ロールバック
            $con->t->display('debug.tpl');
        }else{
            $body = '';
            foreach ($this->err as $key => $value){
                if($key == 'debug'){
                    foreach ($value as $key2 => $value2){
                        $body .= $value2['class']."\r\n";
                        $body .= $value2['function']."\r\n";
                        $body .= $value2['file']."\r\n";
                        $body .= $value2['line']."\r\n";
                    }
                }elseif($key == 'msg'){
                    foreach ($value as $key => $msg){
                        $body .= $msg."\r\n";
                    }
                }else{
                    $body .= $value;
                }
            }
            //if($con->ini['common']['isMail'] == 1 && isset($con->ini['common']['mail'])) $this->sendMail($con->ini['common']['mail']);
            if($con->ini['common']['isMail'] == 1) $this->sendError();
            
            //$data['str'] = "不明なエラーが発生しました。\n時間を置いて再度実行いただきますようお願いいたします。\n\n現象が回避されない場合、\n「教えてCA!」運営事務局宛てに現象をご報告いただきますようお願いいたします。\n\n\nご連絡先\n\ninfo@oshiete-ca.com";
            $con->t->assign('errorlist', $data);
            $this->rollback();//ロールバック
            $con->t->display('error.tpl');
        }

        die();
    }

    function sendError(){
        //メール送信///////////////////////////
        require_once('fw/mailManager.php');
        $mailManager = new mailManager();

        $body = '';
        $body .= "title----------------------------------------------------\n\n";
        $body .= 'URL : '.$_SERVER['SCRIPT_NAME']."\n";
        if(isset($_SERVER['HTTP_REFERER'])) $body .= 'REFERER : '.$_SERVER['HTTP_REFERER']."\n";
        $body .= 'USER_AGENT : '.$_SERVER['HTTP_USER_AGENT']."\n";
        $body .= 'ADDR : '.$_SERVER['REMOTE_ADDR']."\n";
        
        $body .= "\n\nquery----------------------------------------------------\n\n";
        $body .= $this->err['query'];
        $body .= "\n\nmessage----------------------------------------------------\n\n";
        foreach ($this->err['msg'] as $key => $value){
            $body .= $value."\n";
        }
        $body .= "\n\ntrace------------------------------------------------------\n\n";
        foreach ($this->err['debug'] as $key => $value){
            if($value['function'] != 'throwDBError' && $value['function'] != 'execute' && $value['function'] != 'getResult'){
                $body .= $value['class']."\n";
                $body .= $value['function']."\n";
                $body .= $value['file']."\n";
                $body .= $value['line']."行目\n";
            }
        }

        $mailManager->sendHalt($body);
    }

    public function commit(){
        $blnStatus = true;
        $commit_flag = mysql_query("COMMIT");
        //コミット自体がFALSEだった場合。
        if($commit_flag == FALSE) $blnStatus = false;
        if($blnStatus == FALSE) $this->throwDBError();
    }
    
    public function rollback(){
        global $con;
        $con->lastJudge = FALSE;
        mysql_query("ROLLBACK");//ロールバック処理
    }

    private $indexColumn = null;//ユニークである必要があります。
    
    public function setIndexColumn($column){
        $this->indexColumn = $column;
    }
    
    public function execute($query){
        if($this->debug){
            print $query.';';
            $this->debug = FALSE;
            die();
        }
        $blnStatus = true;
        mysql_query('SET NAMES UTF8');
        $intResult = 0;
        $this->sqlData = array();
        $intResult = mysql_query( $query.';' );
        if( $intResult !== FALSE ){
            if($intResult !== TRUE){//NOT UPDATE, DELETE, DROP
                $intRows = mysql_num_rows( $intResult );
                $intCols = mysql_num_fields( $intResult );
                if( $intRows != 0 || $intCols != 0 ){
                    // 結果件数を格納
                    $this->intResultRows = $intRows;
                    $this->intResultCols = $intCols;

                    //データ格納
                    for( $j=0; $j<$intRows; $j++){
                        if(!is_null($this->indexColumn)) $indexKey = mysql_result( $intResult, $j, $this->indexColumn );///keyに入れるカラム値を取得
                        for( $i=0; $i<$intCols; $i++){
                            $strName  = mysql_field_name( $intResult, $i );
                            $strValue = mysql_result( $intResult, $j, $i );
                            if(!is_null($this->indexColumn)){
                                $this->sqlData[$indexKey][$strName] = $strValue;//idをキーに
                            }else{
                                $this->sqlData[$j][$strName] = $strValue;
                            }
                        }
                    }
                }
                if($this->found){
                    $this->rows = mysql_result(mysql_query( 'SELECT FOUND_ROWS()' ),0,0);
                    $this->found = FALSE;
                }
                //メモリ開放
                mysql_free_result( $intResult );
            }

        }else{
            //require_once('fw/error_code_db.php');
            //$this->err['msg'] = $db_error[mysql_errno()][0];
            $this->err['query'] = $query;
            $blnStatus = false;
            //$this->rollback();
        }
        $this->initialize();
        //var_dump($this->_select_columns );
        //print "init";
        if($blnStatus == FALSE) $this->throwDBError();
    }

    // Search
    public $_select_columns = array();
    
    private $_count_columns = array();
    private $_avg_columns = array();
    /**
     * @access private
     */
    private $_join_tables = array();    // for JOIN table
    /**
     * @access private
     */
    private $_conditions = array(); // condition clauses for AND
    /**
     * @access private
     */
    private $_orderby = array();    // for sorting
    /**
     * @access private
     */
    private $_offset = 0;           // for LIMIT
    /**
     * @access private
     */
    private $_limit = -1;           // for LIMIT
    /**
     * @access private
     */
    private $_calcAll = FALSE;      // use SQL_CALC_FOUND_ROWS
    /**
     * @access private
     */
    private $_foundrows = FALSE;        // result of SQL_CALC_FOUND_ROWS
    /**
     * @access private
     */
    private $_lock = DATABASE_DEFAULT_LOCK;
    /**
     * @access private
     */
    private $_select = TRUE;
    /**
     * @access private
     */
    private $_oid_only = FALSE;
    /**
     * @access private
     */
    private $_group_functions = NULL;
    /**
     * @access private
     */
    private $_group_by_columns = array();
    /**
     * @access private
     */
    private $_having = '';
    /**
     * @access private
     */
    private $_alias = NULL;

    private $_union = array();
    
    protected $_no_limit = FALSE;
    
    private $_sub_query = '';

    protected function initialize(){
        $this->_select_columns = array();
        $this->_count_columns = array();
        $this->_avg_columns = array();
        $this->_join_tables = array();
        $this->_conditions = array();
        $this->_orderby = array();
        $this->_group_by_columns = array();
        $this->_offset = 0;
        $this->_limit = -1;
        $this->_lock = DATABASE_DEFAULT_LOCK;
        $this->_alias = NULL;
        $this->_values = array();
        $this->indexColumn = null;
        $this->_no_limit = FALSE;
        $this->_sub_query = '';
        //$this->_union  = array();
    }

    function setLock( $lock = DATABASE_DEFAULT_LOCK )
    {
        $this->_lock = $lock;
    }
    

/*    function addSelectColumn($columns,$alias = NULL){
        $this->_select_columns[$alias] = $columns;
    }*/

    function addSelectColumn($columns){
        $this->_select_columns[] = $columns;
    }

    /**
    * FunctionName
    *
    * @param type $name summary
    * @return type summary
    */
    function addAvgColumn($column,$cname,$alias = NULL){
        $this->_avg_columns[DATABASE_AVG][] = is_null($alias) ? 'AVG('.$column.')'.' AS '.$cname : 'AVG('.$alias.$column.')'.' AS '.$cname;
    }
    
    /**
    * FunctionName
    *
    * @param type $name summary
    * @return type summary
    * $isDistinctは重複行がある場合の対処。groupby ではカウントできない
    */
    
    function addCountColumn($column,$cname,$alias = NULL,$isDistinct = FALSE){
        $this->_count_columns[DATABASE_COUNT][] = is_null($alias) ? 'COUNT('.$column.')'.' AS '.$cname : ($isDistinct ? 'COUNT(DISTINCT '.$alias.'.'.$column.')'.' AS '.$cname : 'COUNT('.$alias.'.'.$column.')'.' AS '.$cname);
    }
    

    /**
     * Adds the table specified by <var>tableinfo</var>,
     * and joins it on the condition specified by <var>$conditions</var>,
     * using the join type specified by <var>$type</var>
     * @param object TableInfo $tableinfo
     *  The table which are joined.
     * @param string $conditions
     *  Condition on which the table are joined.If NULL no condition.
     *  Default value is NULL.
     * @param string $type
     *  A join type which are used.
     *  Default value is 'LEFT JOIN'.
     * @param string $alias
     *  The table's alias name. If NULL alias isn't used.
     *  Default value is NULL.
     */
    function addJoin( $table_info, $conditions = NULL,$alias = NULL,$type = DATABASE_INNER_JOIN )
    {
        $this->_join_tables[] = array(
                                      'table' => $table_info,
                                      'on' => $conditions,
                                      'type' => $type,
                                      'alias' => $alias
                                      );
    }
    
    /**
     * @param string $condition
     *  An SQL expression that may appear in WHERE clause.
     */
    function addCondition( $condition )
    {
        $this->_conditions[] = $condition;
    }

    /**
     * Specifies the ORDER BY column.
     *
     * @param string $col_name
     *  The column's internal name such as 'tab_user.col_name' that
     *  which will be embedded in the SQL directly.
     * @param bool $reverse
     *  If not <var>FALSE</var>, the result will be returned in reverse order.
     */
    function addOrderColumn( $col_name, $reverse = FALSE )
    {
        $this->_orderby[] = array( $col_name, $reverse );
    }

    function addGroupByColumn($col_name)
    {
        $this->_group_by_columns[] = $col_name;
    }
    
    function addUnionQuery($query)
    {
        $this->_union[] = $query;
    }
    
    function getUnionQuery($all = '')
    {
        $query = '('.$this->_union[0].') UNION '.$all.'('.$this->_union[1].')';
        $this->_union = array();
        return $query;
    }

    function addSubQuery($query)
    {
        $this->_sub_query = $query;
    }

/*    function addGroupByColumn( $col_name, $reverse = FALSE )
    {
        $this->_group_by_columns[] = array( $col_name, $reverse );
    }*/
    
    /**
     * Limits the number of rows to return.
     *
     * @param int $offset
     *  The offset of the first row to return.  The offset of the
     *  initial row is 0 (not 1).
     * @param int $limit
     *  The number of rows to be retrieved.  Specify '-1' to retrieve
     *  all rows after <var>offset</var>.
     */
    function limit( $offset = 0, $limit = 21 )
    {
        $this->_offset = $offset;
        $this->_limit = $limit;
    }
    
    function select($table,$alias = null){
        $query = "SELECT ";
        if($this->found) $query .= 'SQL_CALC_FOUND_ROWS ';
        $toPutComma = FALSE;

/*        foreach( $this->_select_columns as $table_alias => $columns )
        {
            foreach( $columns as $column )
            {
                if ( $toPutComma ) {
                    $query .= ', ';
                } else {
                    $toPutComma = TRUE;
                }
                $column = ereg("^_id", $column) == TRUE ? $column : DATABASE_COLUMN_PREFIX.$column;

                if($table_alias != '') $column = $table_alias.'.'.$column;
                $query .= $column;
            }
        }*/
        //aliasはtableManagerで
        foreach( $this->_select_columns as $columns )
        {
            foreach( $columns as $column )
            {
                if ( $toPutComma ) {
                    $query .= ', ';
                } else {
                    $toPutComma = TRUE;
                }
                //$column = ereg("^_id", $column) == TRUE ? $column : DATABASE_COLUMN_PREFIX.$column;

                //if($table_alias != '') $column = $table_alias.'.'.$column;
                $query .= $column;
            }
        }
        //count
        if(count($this->_count_columns) != 0){
            if(count($this->_select_columns) > 0) $query .= ', ';
            $toPutComma = FALSE;
            foreach( $this->_count_columns as $columns )
            {
                foreach( $columns as $column )
                {
                    if ( $toPutComma ) {
                        $query .= ', ';
                    } else {
                        $toPutComma = TRUE;
                    }
                    $query .= $column;
                }
            }
        }

        //$alias = $this->_alias;
        $table =$table . (is_null($alias) ? '' : " AS $alias");
        
        $query .= " FROM ";
        $from = $table;
        $keys = array_keys($this->_join_tables);
        $begin = '';
        $end = '';
        foreach( $keys as $key )
        {
            $from = $begin. $from .$end;
            $begin = '(';
            $end = ')';
            
            $info =& $this->_join_tables[$key];
            //$table_info =& $info['table'];
            $table_name = $info['table'];
            //$table_name = $db->escape( $table_info->getTableName() );
            $conditions = $info['on'];
            $type = $info['type'];
            $join_alias = $info['alias'];
            
            $from .= " $type " . $table_name .
                     (is_null($join_alias) ? '' : " AS $join_alias") .
                     (is_null($conditions) ? '' : " ON $conditions");
        }
        $query .= $from;

        if ( count( $this->_conditions ) > 0 )
        {
            $query .= ' WHERE ';
            //サブクエリ
            if(strlen($this->_sub_query) > 0){
                $query .= $this->_sub_query;
            }
            
            $toPutAnd = FALSE;
            foreach( $this->_conditions as $condition )
            {
                if($condition != ''){//怪しい挙動なので一応チェック
                    if ( $toPutAnd ) {
                        $query .= 'AND ';
                    } else {
                        $toPutAnd = TRUE;
                    }
                    $query .= "(${condition})";
                }
            }
        }

        //group by
        if ( count( $this->_group_by_columns ) > 0 )
        {
            $query .= ' GROUP BY ';
            $toPutComma = FALSE;
            foreach($this->_group_by_columns as $column) {
                if ( $toPutComma ) {
                    $query .= ', ';
                } else {
                    $toPutComma = TRUE;
                }
                $query .= $column;
            }
        }

        if ( count( $this->_orderby ) > 0 )
        {
            $query .= ' ORDER BY ';
            $toPutComma = FALSE;
            foreach( $this->_orderby as $ob )
            {
                if ( $toPutComma ) {
                    $query .= ', ';
                } else {
                    $toPutComma = TRUE;
                }
                
                //if ( is_null($ob[0]) )
                //{
                    //$ob[0] = '_id';
                //}
                
                $query .= $ob[0];
                //$query .= ereg("^_id", $ob[0]) == TRUE ? $ob[0] : DATABASE_COLUMN_PREFIX.$ob[0];
                if ( $ob[1] )//デフォルトはFALSE
                {
                    $query .= ' DESC';//大きい順
                }
            }
        }else{
            $query .= is_null($alias) ? ' ORDER BY _id' : ' ORDER BY '.$alias.'._id';//指定がなくて，aliasがあればつけてorder
        }
        
        if ( $this->_limit != -1 )
        {
            $query .= ' LIMIT '.$this->_offset.','.$this->_limit;
        }elseif(!$this->_no_limit){
            $query .= ' LIMIT 0,18446744073709551615';
        }
        //lock
        switch( $this->_lock )
        {
            case DATABASE_EXCLUSIVE_LOCK:
                $query .= ' FOR UPDATE';
                break;
            case DATABASE_SHARED_LOCK:
                $query .= ' LOCK IN SHARE MODE';
                break;
            case DATABASE_NO_LOCK:
                $query .= '';
                break;
            default:
                $query .= '';
        }

        return $query;

    }

    var $_values = array();

    function addValue($values){
        $this->_values = $values;
    }

    // Insert
    
    function insert($table){
        $query = 'INSERT INTO ';
        $query .= $table.' ';
        $toPutComma = FALSE;
        $left = '';
        $right = '';
        foreach( $this->_values as $key => $val )
        {
            //if(!is_numeric($val)) $val = mysql_escape_string($val);
            if ( $toPutComma ) {
                $left .= ', ';
                $right .= ', ';
            } else {
                $toPutComma = TRUE;
            }
            $left .= ereg("^_id", $key) == TRUE ? $key : DATABASE_COLUMN_PREFIX.$key;
            $right .= $this->checkValueType($val);//escape or null
        }
        $query .= '(';
        $query .= $left;
        $query .= ')';
        $query .= ' VALUES ';
        $query .= '(';
        $query .= $right;
        $query .= ')';
/*        print $query;
        die();*/
        return $query;
    }
    
    //Update
    function update($table){
        $query = 'UPDATE ';
        $query .= $table;
        $query .= ' SET ';
        $toPutComma = FALSE;
        foreach( $this->_values as $key => $val )
        {
            //if(!is_numeric($val)) $val = mysql_escape_string($val);
            if ( $toPutComma ) {
                $query .= ', ';
            } else {
                $toPutComma = TRUE;
            }
            //$query .= ' '.ereg("^_id", $key) == TRUE ? $key : DATABASE_COLUMN_PREFIX.$key.' = \''.$val.'\'';
            $query .= ereg("^_id", $key) == TRUE ? $key : DATABASE_COLUMN_PREFIX.$key.' = '.$this->checkValueType($val);
        }
        if ( count( $this->_conditions ) > 0 )
        {
            $query .= ' WHERE ';
            $toPutAnd = FALSE;
            foreach( $this->_conditions as $condition )
            {
                if ( $toPutAnd ) {
                    $query .= 'AND ';
                } else {
                    $toPutAnd = TRUE;
                }
                $query .= "(${condition})";
            }
        }
        return $query;
    }
    
    //Delete
    function delete($table){
        $query = 'DELETE ';
        $query .= 'FROM ';
        $query .= $table.' ';
        if ( count( $this->_conditions ) > 0 )
        {
            $query .= ' WHERE ';
            $toPutAnd = FALSE;
            foreach( $this->_conditions as $condition )
            {
                if ( $toPutAnd ) {
                    $query .= 'AND ';
                } else {
                    $toPutAnd = TRUE;
                }
                $query .= "(${condition})";
            }
        }
        return $query;
    }
    
    function checkValueType($value){
        if(is_null($value)){
            return 'NULL';
        }else{
            if(!is_numeric($value)){
                //+1更新フォーマット
                if(is_array($value) && isset($value['increment'])){
                    return $value['increment'];
                }else{
                    return '\''.mysql_real_escape_string($value).'\'';
                }
/*                $str = substr($value,-2);
                if($str == '+1'){//数値の加算処理
                    return $value;
                }else{
                    return '\''.mysql_real_escape_string($value).'\'';
                }*/
            }else{
                return '\''.$value.'\'';
            }
        }
    }



    protected function escapeForLike($str){
        //mysql_real_escape_string実行する前に特殊エスケープが必要
        mb_regex_encoding('UTF-8');
        // LIKEで使われるワイルドカード(%)をエスケープ処理
        $str = mb_ereg_replace('%','\%',$str);

        // LIKEで使われるワイルドカード(_)をエスケープ処理
        $str = mb_ereg_replace('_','\_',$str);

        // 円マーク(バックスラッシュ)をLIKE用に2重化
        $str = mb_ereg_replace('\\\\','\\\\',$str);
        
        // 値をMySQL用のものでエスケープ処理
        $str = mysql_real_escape_string($str);
        return $str;
    }

/*    protected function checkValue($value){
        return is_null($value) ? 'NULL' : '\''.addslashes($value).'\'';
    }*/

}
?>
