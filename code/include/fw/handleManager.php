<?php
require_once('fw/database.php');
//require_once('fw/check.php');
class handleManager extends database
{
    protected $error = array();

    public function getId($table,$code){
        $this->addSelectColumn(array('_id'));
        $this->addCondition('col_code = '.$this->checkValueType($code));
        $query = $this->select($table);
        $this->execute($query);
        return $this->intResultRows == 0 ? FALSE : $this->sqlData['0']['_id'];
    }

    public function getCode($table,$id){
        $this->addSelectColumn(array('code'));
        $this->addCondition('_id = '.$this->checkValueType($id));
        $query = $this->select($table);
        $this->execute($query);
        return $this->intResultRows == 0 ? FALSE : $this->sqlData['0']['col_code'];
    }

    public function getCodeList($table){
        $this->addSelectColumn(array('code'));
        $query = $this->select($table);
        $this->execute($query);
        return $this->intResultRows == 0 ? FALSE : $this->sqlData;
    }

    public function addRow($table,$parameter){
        $this->addValue($parameter->parameter);
        $query = $this->insert($table);
        //print $query;
        //die();
        $this->execute($query);
        $id = mysql_insert_id();
        if(strcasecmp($id,0) == 0){
            $this->err['query'] = $query;
            $this->throwDBError();
        }
        return $id;
    }

    public function updateRow($table,$parameter){
        $this->addValue($parameter->parameter);
        $this->addCondition(DATABASE_OID_NAME.' = '.$this->checkValueType($parameter->oid));
        $query = $this->update($table);
        //print $query;
        //die();
        $this->execute($query);
        return $parameter->oid;
    }

    //_id以外の指定による更新
    public function updateSpecialRow($table,$parameter,$pair){
        $this->addValue($parameter->parameter);
        $this->addCondition($pair['key'].' = '.$this->checkValueType($pair['value']));
        $query = $this->update($table);
        $this->execute($query);
        return $parameter->oid;
    }

    protected function addDebug($table,$parameter){
        $this->addValue($parameter->parameter);
        $query = $this->insert($table);
        print $query;
        die();
    }

    protected function updateDebug($table,$parameter){
        $this->addValue($parameter->parameter);
        $this->addCondition(DATABASE_OID_NAME.' = '.$this->checkValueType($parameter->oid));
        $query = $this->update($table);
        print $query;
        die();
        var_dump($this->sqlData);
        die();
    }

    public function deleteRow($table,$parameter){
        $this->addCondition(DATABASE_OID_NAME.' = '.$this->checkValueType($parameter->oid));
        $query = $this->delete($table);
        $this->execute($query);
        return $parameter->oid;
    }
}
?>
