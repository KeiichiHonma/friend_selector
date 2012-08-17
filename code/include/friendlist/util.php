<?php
class util
{
    static public function makeSexText($sex){
        if(!isset($sex) || $sex == ''){
            return '';
        }
        if($sex == 'male'){
            return '男性';
        }elseif($sex == 'female'){
            return '女性';
        }else{
            return '';
        }
    }

    static public function makeAgeText($birth){
        if(!isset($birth) || $birth == ''){
            return '';
        }
        $ty = date("Y");
        $tm = date("m");
        $td = date("d");
        list($bm, $bd, $by) = explode('/', $birth);
        if(!isset($by) || $by == '' || !$by) return '';
        $age = $ty - $by;
        if($tm * 100 + $td < $bm * 100 + $bd) $age--;
        return $age;
    }

    static public function makeRelationshipStatusText($status){
        if(!isset($status) || $status == ''){
            return '';
        }
        switch ($status){
            case 'Single':
                return '独身';
            break;
            case 'In a relationship':
                return '交際中';
            break;

            case 'Engaged':
                return '婚約中';
            break;
            case 'Married':
                return '既婚';
            break;
            case "It's complicated":
                return '複雑な関係';
            break;
            case 'In an open relationship':
                return 'オープンな関係';
            break;
            case 'Widowed':
                return '配偶者と死別';
            break;
            case 'Separated':
                return '別居中';
            break;
            case 'Divorced':
                return '離婚';
            break;

            default:
                return '';
        }
    }
    
    static public function checkUnderScore($flid){
        return is_numeric($flid) ? $flid :false;
    }
}
?>