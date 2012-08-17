<?php
require_once('file/handle.php');
class inputManager
{
    private $files_handle;
    
    protected $section_delete_key = 'delete_section';
    protected $file_delete_key = 'delete_file';
    protected $fid_key = 'fid';
    protected $any_key;
    
    protected $file_key = 'file';
    protected $alt_key = 'alt';

    public $fid = null;
    private $is_commit = FALSE;
    
    //実行可否
    private $section_delete = FALSE;
    
    private $file_upload = FALSE;
    private $file_add = FALSE;
    private $file_update = FALSE;
    private $file_delete = FALSE;
    private $alt_update = FALSE;
    
    private $any_add = FALSE;
    private $any_update_delete = FALSE;
    
    //下部クラスからセット
    protected $class_obj;
    protected $is_any = FALSE;//ファイル更新と情報更新が別ロジックの場合
    protected $model_name;
    
    function __construct($file_upload){
        $this->files_handle = new filesHandle();
        $this->file_upload = $file_upload;
    }
    
    public function addFile(){
        if(!isset($_POST[$this->alt_key])) $_POST[$this->alt_key] = '';
        $this->fid = $this->files_handle->addRow($_FILES[$this->file_key],$_POST[$this->alt_key]);
        $this->is_commit = TRUE;
    }
    
    public function updateFile(){
        if(!isset($_POST[$this->alt_key])) $_POST[$this->alt_key] = '';
        $uploadFile = $this->file_upload ? $_FILES[$this->file_key] : null;
        $this->fid = $this->files_handle->updateRow($_POST[$this->fid_key],$uploadFile,$_POST[$this->alt_key]);
        if($this->file_upload) $this->is_commit = TRUE;
    }

    public function deleteFile(){
        $this->files_handle->deleteRow($_POST[$this->fid_key]);
        call_user_func($this->makeUpdateFileIdModel());//0に戻す
        $this->is_commit = TRUE;
    }

    protected function makeAddModel(){
        return array($this->class_obj,$this->model_name.'AddModel');
    }

    protected function makeUpdateModel(){
        return array($this->class_obj,$this->model_name.'UpdateModel');
    }

    protected function makeUpdateFileIdModel(){
        return array($this->class_obj,$this->model_name.'UpdateFileIdModel');
    }

    protected function makeDeleteModel(){
        return array($this->class_obj,$this->model_name.'DeleteModel');
    }

    public function input(){
        //実行機能選択
        //セクション削除チェック
        if(isset($_POST[$this->section_delete_key])) $this->section_delete = TRUE;
        //ファイル削除可否
        if(isset($_POST[$this->file_delete_key]) && strcasecmp($_POST[$this->file_delete_key],'on') == 0) $this->file_delete = TRUE;
        //ファイル追加可否
        if($this->file_upload && !isset($_POST[$this->fid_key])) $this->file_add = TRUE;
        //ファイル更新可否
        if($this->file_upload && isset($_POST[$this->fid_key]) && is_numeric($_POST[$this->fid_key])) $this->file_update = TRUE;
        
        //alt更新可否
        if(!$this->file_upload && isset($_POST[$this->alt_key]) && isset($_POST[$this->fid_key]) && is_numeric($_POST[$this->fid_key])) $this->alt_update = TRUE;
        
        //any追加可否
        if(!isset($_POST[$this->any_key])) $this->any_add = TRUE;
        //any追加可否
        if(isset($_POST[$this->any_key]) && is_numeric($_POST[$this->any_key])) $this->any_update_delete = TRUE;
        
        //section
        if($this->section_delete){
            if($this->file_delete) $this->deleteFile();
            if($this->any_update_delete) call_user_func($this->makeDeleteModel());
        }else{
            //file
            if($this->file_delete){
                $this->deleteFile();
            }elseif($this->file_add){
                $this->addFile();
            }elseif($this->file_update){
                $this->updateFile();
            }elseif($this->alt_update){
                $this->updateFile();
            }
            
            //any
            if($this->any_add){
                call_user_func($this->makeAddModel());
            }elseif($this->any_update_delete){
                call_user_func($this->makeUpdateModel());
            }
        }
        
        if($this->is_commit) $this->files_handle->file_commit();
    }
}

class loopInputManager
{
    private $files_handle;
    
    private $section_delete_key = 'delete_all';
    private $file_delete_key = 'delete_file';
    private $fids_key = 'fids';
    protected $map_key = 'map';
    private $file_key = 'file';
    private $alt_key = 'alt';
    
    public  $validate_loop_count_key = 'loop_count';
    public  $af_count_key = 'af_count';
    
    public $fid = null;
    private $is_commit = FALSE;
    
    //実行可否
    private $section_delete_go = FALSE;
    private $file_go = FALSE;
    private $any_handle_go = FALSE;
    private $file_delete_go = FALSE;
    private $exists_file_go = FALSE;
    
    //下部クラスからセット
    protected $class_obj;
    protected $is_any = FALSE;//ファイル更新と情報更新が別ロジックの場合
    protected $model_name;
    protected $validate_loop;
    protected $files_loop;
    protected $is_section_delete = TRUE;//セクション削除機能の可否
    
    function __construct(){
        $this->files_handle = new filesHandle();
    }
    
    public function addFile($loop_number){
        $this->fid = $this->files_handle->addRow($_FILES[$this->file_key],$_POST[$this->alt_key],$loop_number);
        $this->is_commit = TRUE;
    }
    
    public function updateFile($loop_number,$isUploadFile = TRUE){
        $uploadFile = $isUploadFile ? $_FILES[$this->file_key] : null;
        $this->fid = $this->files_handle->updateRow($_POST[$this->fids_key],$uploadFile,$_POST[$this->alt_key],$loop_number);
        if($isUploadFile) $this->is_commit = TRUE;
    }

    public function deleteFile($loop_number){
        $this->files_handle->deleteRow($_POST[$this->fids_key]);
        $this->is_commit = TRUE;
    }

    protected function makeAddModel(){
        return array($this->class_obj,$this->model_name.'AddModel');
    }

    protected function makeUpdateModel(){
        return array($this->class_obj,$this->model_name.'UpdateModel');
    }

    protected function makeDeleteModel(){
        return array($this->class_obj,$this->model_name.'DeleteModel');
    }

    private function init(){
        $this->fid = null;
        $this->section_delete_go = FALSE;
        $this->file_go = FALSE;
        $this->any_handle_go = FALSE;
        $this->file_delete_go = FALSE;
        $this->exists_file_go = FALSE;
    }

    public function loopInput(){
        foreach ($this->validate_loop as $loop_number){
            $this->init();
            //実行機能選択
            //セクション削除チェック
            if($this->is_section_delete && isset($_POST[$this->section_delete_key])) $this->section_delete_go = TRUE;
            //ファイル操作準備可否
            if(isset($_POST[$this->fids_key]) && is_numeric($_POST[$this->fids_key])) $this->file_go = TRUE;
            //その他DB更新、削除準備可否
            if(isset($_POST[$this->map_key]) && is_numeric($_POST[$this->map_key])) $this->any_handle_go = TRUE;
            //アップファイルが存在するか？
            if(in_array($loop_number,$this->files_loop)) $this->exists_file_go = TRUE;
            //ファイル削除可否
            if(isset($_POST[$this->file_delete_key]) && strcasecmp($_POST[$this->file_delete_key],'on') == 0) $this->file_delete_go = TRUE;
            
            if($this->section_delete_go){
                if($this->file_go) $this->deleteFile($loop_number);
                if($this->any_handle_go) call_user_func($this->makeDeleteModel(),$loop_number);
            }

            if($this->exists_file_go){
                $this->file_go ? $this->updateFile($loop_number) : $this->addFile($loop_number);
                $this->any_handle_go ? call_user_func($this->makeUpdateModel(),$loop_number) : call_user_func($this->makeAddModel(),$loop_number);
            }else{
                if($this->file_go) $this->file_delete_go ? $this->deleteFile($loop_number) : $this->updateFile($loop_number,FALSE);
                $this->any_handle_go ? call_user_func($this->makeUpdateModel(),$loop_number) : call_user_func($this->makeAddModel(),$loop_number);
            }
        }
        if($this->is_commit) $this->files_handle->file_commit();
    }
}
?>
