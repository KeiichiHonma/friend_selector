<?php
require_once('fw/database.php');
class logicManager extends database
{
    protected $and = null;
    protected $or = null;
    protected $status;
    protected $sign = '>=';
    protected $validate;
    
    protected function getResult($table,$alias = null){
        $this->makeCond();
        $query = $this->select($table,$alias);
        $this->execute($query);
        return $this->intResultRows == 0 ? FALSE : $this->sqlData;
    }

    protected function getQuery($table,$alias = null){
        $this->makeCond();
        $query = $this->select($table,$alias);
        $this->initialize();
        return $query;
    }

    protected function getSubQuery($table,$alias = null){
        $this->_no_limit = TRUE;//limit句はつけれない
        return $this->getQuery($table,$alias);
    }

    protected function setSubQuery($query,$key_column,$sign1 = 'IN',$sign2 = 'OR'){
        $this->addSubQuery($key_column.' '.$sign1.' ('.$query.')'.$sign2);
    }

    protected function getDebug($table,$alias = null){
        $this->makeCond();
        $query = $this->select($table,$alias);
        $this->initialize();
        print $query.';';
        die();
        $this->execute($query);
        var_dump($this->sqlData);
        die();
    }

    //全ての下部クラスからコールされるRow取得メソッド
    protected function getRow($table,$id){
        $this->setCond(DATABASE_OID_NAME,$id);
        return self::getResult($table);//オーバーライドしてるので
    }

    protected function getRows($table,$condition = null,$from = 0,$to = FIRSTSP,$order = null){
        if(!is_null($condition)) $this->setCond($condition['key'],$condition['value'],$condition['sign']);
        if(!is_null($order)) $this->addOrderColumn($order['column'],$order['desc_asc']);
        $this->limit($from,$to);
        return self::getResult($table);//オーバーライドしてるので
    }

    protected function getRowCode($table,$code){
        $this->setCond('col_code',$code);
        return self::getResult($table);//オーバーライドしてるので
    }

    protected function getCount($table,$column_name,$alias = null){
        $result = self::getResult($table,$alias);
        return $result === FALSE ? FALSE : $result[0][$column_name];
    }

    //common
    protected function setCond($key,$value,$sign = '='){
        $this->addCondition($key.' '.$sign.' '.$this->checkValueType($value));
    }
    
    //$keyと$valusが$aliasのテーブルで$signであるとき
    protected function setCondAlias($key,$value,$alias,$sign = '='){
        $this->addCondition($alias.'.'.$key.' '.$sign.' '.$this->checkValueType($value));
    }

    //and
    protected function setAndCond($key,$value,$sign = '='){
        $this->and[] = $key.' '.$sign.' '.$this->checkValueType($value);
    }

    protected function setAndCondAlias($key,$value,$alias,$sign = '='){
        $this->and[] = $alias.'.'.$key.' '.$sign.' '.$this->checkValueType($value);
    }

    //or
    protected function setOrCond($key,$value,$sign = '='){
        $this->or[] = $key.' '.$sign.' '.$this->checkValueType($value);
    }

    protected function setOrCondAlias($key,$value,$alias,$sign = '='){
        $this->or[] = $alias.'.'.$key.' '.$sign.' '.$this->checkValueType($value);
    }

    protected function makeCond(){
        if(!is_null($this->and)){
            $toPutAnd = FALSE;
            $q = '';
            foreach($this->and as $condition){
                if ( $toPutAnd ) {
                    $q .= ' AND ';
                } else {
                    $toPutAnd = TRUE;
                }
                $q .= $condition;
            }
            $this->addCondition($q);
            $this->and = null;//初期化
        }
        if(!is_null($this->or)){
            $toPutOr = FALSE;
            $q = '';
            foreach($this->or as $condition){
                if ( $toPutOr ) {
                    $q .= ' OR ';
                } else {
                    $toPutOr = TRUE;
                }
                $q .= $condition;
            }
            $this->addCondition($q);
            $this->or = null;//初期化
        }
    }

    //共通で使用される条件
    protected function validateCondition($alias = null){
        is_null($alias) ? $this->setCond('col_validate',VALIDATE_ALLOW) : $this->setCondAlias('col_validate',VALIDATE_ALLOW,$alias);
    }
    
    protected function setStatusCondition($alias = null){
        is_null($alias) ? $this->setCond('col_status',$this->status,$this->sign) : $this->setCondAlias('col_status',$this->status,$alias,$this->sign);
        //init
        $this->status = STATUS_PUBLIC;
        $this->sign = '>=';
    }

    protected function makeFileJoin($target,$on_column = 'col_fid',$base_column = DATABASE_OID_NAME,$type = DATABASE_LEFT_JOIN){
        $this->addSelectColumn(filesTable::getAlias());
        $this->addJoin( T_FILES,A_FILES.'.'.$base_column.' = '.constant('A_'.$target).'.'.$on_column,A_FILES,$type);
    }

    //アクセス元
    protected function accessCondition($isMobile,$alias = null){
        if(is_null($alias)){
            $isMobile ? $this->setCond('col_access',ACCESS_TYPE_MOBILE) : $this->setCond('col_access',ACCESS_TYPE_PC);
        }else{
            $isMobile ? $this->setCondAlias('col_access',ACCESS_TYPE_MOBILE,$alias) : $this->setCondAlias('col_access',ACCESS_TYPE_PC,$alias);
        }
        
    }

    protected function setValidateCondition($alias = null){
        is_null($alias) ? $this->setCond('col_validate',VALIDATE_ALLOW,'=') : $this->setCondAlias('col_validate',VALIDATE_ALLOW,$alias,'=');
    }

    protected function setCategoryCond($cid){
        $this->setCondAlias('col_cid',$cid,A_QUESTION);
    }

    protected function setMemberCond($mid){
        $this->setCondAlias('col_mid',$mid,A_QUESTION);
    }

    protected function setUserCond($uid){
        $this->setCondAlias('col_uid',$uid,A_QUESTION);
    }

    protected function setQuestionCond($qid){
        $this->setCondAlias('_id',$qid,A_QUESTION);
    }

    protected function setAnswerCond($aid){
        $this->setCondAlias('col_aid',$aid,A_REPLY);
    }

}
?>