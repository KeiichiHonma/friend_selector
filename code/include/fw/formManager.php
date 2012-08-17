<?php
class formManager
{
    protected $sall = array(YES=>'YES',NO=>'NO');

    protected function getYesNo(){
        return $this->sall;
    }

    protected function getForm($form,$class_obj){
        foreach ($form as $group => $form_data){
            foreach ($form_data as $key => $array){
                foreach($array as $module => $value){
                    if(is_array($value)){
                        foreach($value as $module2 => $value2){
                            if($module2 == 'func' && !is_null($value2)){
                                $result = call_user_func(array($class_obj,$value2));
                                $form[$group][$key][$module]['value'] = $result;
                            }
                        }
                    }else{
                        if($module == 'func' && !is_null($value)){
                            $result = call_user_func(array($class_obj,$value));
                            $form[$group][$key]['value'] = $result;
                        }
                    }
                }
            }
        }
/*var_dump($form);
die();*/
        return $form;
    }
    
    public function assignForm($form,$class_obj){
        global $con;
        $con->t->assign('form',$this->getForm($form,$class_obj));
    }

    public function getCarrier($key = null){
        $list = array
            (
                '0'=>'docomo.ne.jp',
                '1'=>'ezweb.ne.jp',
                '2'=>'softbank.ne.jp',
                '3'=>'d.vodafone.ne.jp',
                '4'=>'h.vodafone.ne.jp',
                '5'=>'t.vodafone.ne.jp',
                '6'=>'c.vodafone.ne.jp',
                '7'=>'k.vodafone.ne.jp',
                '8'=>'r.vodafone.ne.jp',
                '9'=>'n.vodafone.ne.jp',
                '10'=>'s.vodafone.ne.jp',
                '11'=>'q.vodafone.ne.jp',
                '12'=>'disney.ne.jp',
/*              ウィルコムは非対応とする
                '13'=>'willcom.com',
                '14'=>'pdx.ne.jp',
                '15'=>'wm.pdx.ne.jp',
                '16'=>'dj.pdx.ne.jp',
                '17'=>'di.pdx.ne.jp',
                '18'=>'dk.pdx.ne.jp',*/
            );
        global $con;
        if($con->isDebug){
            $list['19'] = 'zeus.corp.813.co.jp';
        }
        if(!is_null($key)){
            if(is_numeric($key)){
                return array_key_exists($key,$list) ? $list[$key] : FALSE;
            }else{
                //逆
                return array_search($key,$list);//値から検索
            }
            
        }else{
            return $list;
        }

    }

    public function getLoginType($key = null){
        $list = array(LOGIN_PC=>'パソコンのメールアドレスでログインする',LOGIN_MOBILE=>'携帯のメールアドレスでログインする');
        if(!is_null($key)){
            if(is_numeric($key)){
                return array_key_exists($key,$list) ? $list[$key] : FALSE;
            }else{
                //逆
                return array_search($key,$list);//値から検索
            }
            
        }else{
            return $list;
        }
    }
    
    //アクセス端末で戻り値を変更
    public function getNotificationMailForAccess($key = null){
        global $con;
        if($con->isMobile){
            $list = $this->notification_mobile_list;
        }else{
            $list = $this->notification_list;
        }
        
        if(!is_null($key)){
            if(is_numeric($key)){
                return array_key_exists($key,$list) ? $list[$key] : FALSE;
            }else{
                //逆
                return array_search($key,$list);//値から検索
            }
            
        }else{
            return $list;
        }
    }

    public function getNotificationMail($key = null){
        $list = $this->notification_list;
        if(!is_null($key)){
            if(is_numeric($key)){
                return array_key_exists($key,$list) ? $list[$key] : FALSE;
            }else{
                //逆
                return array_search($key,$list);//値から検索
            }
            
        }else{
            return $list;
        }
    }



}
?>