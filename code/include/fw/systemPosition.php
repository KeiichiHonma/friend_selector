<?php
require_once('fw/positionManager.php');
class systemPosition extends positionManager
{
    static protected $page = array
    (
    'index'=>array('name'=>'TARホーム','func'=>null,'ssl'=>FALSE,'gnavi'=>'index','snavi'=>null),
    'system'=>array
        (
        'index'=>array('name'=>'管理画面トップ','func'=>null,'access'=>TYPE_M_ADMIN,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'index'),
        'login'=>array('name'=>'管理画面トップ','func'=>null,'access'=>TYPE_M_MANAGER,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'index'),
        'logout'=>array('name'=>'管理画面トップ','func'=>null,'access'=>TYPE_M_MANAGER,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'index'),
        'manager'=>array
            (
            'index'=>array('name'=>'マネージャー管理','func'=>null,'access'=>TYPE_M_ADMIN,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'manager'),
            'view'=>array('name'=>'マネージャー詳細','func'=>null,'access'=>TYPE_M_ADMIN,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'manager'),
            'entry'=>array
                (
                'input'=>array('name'=>'マネージャー追加','func'=>null,'access'=>TYPE_M_ADMIN,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'manager')
                ),
            'edit'=>array
                (
                'input'=>array('name'=>'マネージャー変更','func'=>null,'access'=>TYPE_M_ADMIN,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'manager'),
                'password'=>array
                    (
                    'input'=>array('name'=>'パスワード変更','func'=>null,'access'=>TYPE_M_ADMIN,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'manager')
                    )
                ),
            'drop'=>array
                (
                'input'=>array('name'=>'マネージャー削除','func'=>null,'access'=>TYPE_M_ADMIN,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'manager')
                )
            ),
        'property'=>array
            (
            'index'=>array('name'=>'物件管理','func'=>null,'access'=>TYPE_M_ADMIN,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'property'),
            'view'=>array('name'=>'物件詳細','func'=>null,'access'=>TYPE_M_ADMIN,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'property'),
            'entry'=>array
            (
            'input'=>array('name'=>'物件追加','func'=>null,'access'=>TYPE_M_ADMIN,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'property'),
            ),
            'edit'=>array
            (
            'input'=>array('name'=>'物件変更','func'=>null,'access'=>TYPE_M_ADMIN,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'property'),
            'test'=>array('name'=>'物件変更','func'=>null,'access'=>TYPE_M_ADMIN,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'property'),
            ),
            'gallery'=>array
                (
                'index'=>array('name'=>'物件ギャラリー','func'=>null,'access'=>TYPE_M_ADMIN,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'property'),
                'entry'=>array
                    (
                    'input'=>array('name'=>'物件ギャラリー追加','func'=>null,'access'=>TYPE_M_ADMIN,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'property'),
                    ),
                'edit'=>array
                    (
                    'input'=>array('name'=>'物件ギャラリー変更','func'=>null,'access'=>TYPE_M_ADMIN,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'property'),
                    ),
                ),
            'room'=>array
                (
                'index'=>array('name'=>'部屋管理','func'=>null,'access'=>TYPE_M_ADMIN,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'property'),
                'view'=>array('name'=>'部屋詳細','func'=>null,'access'=>TYPE_M_ADMIN,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'property'),
                'entry'=>array
                (
                'input'=>array('name'=>'部屋追加','func'=>null,'access'=>TYPE_M_ADMIN,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'property'),
                ),
                'edit'=>array
                (
                'input'=>array('name'=>'部屋変更','func'=>null,'access'=>TYPE_M_ADMIN,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'property'),
                ),
                'gallery'=>array
                    (
                    'index'=>array('name'=>'部屋ギャラリー','func'=>null,'access'=>TYPE_M_ADMIN,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'property'),
                    'entry'=>array
                        (
                        'input'=>array('name'=>'部屋ギャラリー追加','func'=>null,'access'=>TYPE_M_ADMIN,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'property'),
                        ),
                    'edit'=>array
                        (
                        'input'=>array('name'=>'部屋ギャラリー変更','func'=>null,'access'=>TYPE_M_ADMIN,'ssl'=>TRUE,'gnavi'=>null,'snavi'=>'property'),
                        ),
                    ),
                )
            )
        )
    );

    static public function makeSitePosition(){
        parent::$page = self::$page;
        parent::makeSitePosition(TRUE);
    }

    static private $index = 1;

    static public function makeNumberPosition($url,$title,$trim = TRUE){
        parent::$position[self::$index] = parent::makePositionPair($url,$trim ? self::positionTrim($title) : $title);
        self::$index++;
    }

    static public function updatePosition($index,$url,$title,$trim = TRUE){
        parent::$position[$index] = parent::makePositionPair($url,$trim ? self::positionTrim($title) : $title);
    }

    static public function insertPosition($index,$url,$title,$trim = TRUE){
        $array = array(parent::makePositionPair($url,$trim ? self::positionTrim($title) : $title));
        // 第２引数は挿入する位置、第３引数は削除する数を表す。
        array_splice(parent::$position, $index, 0, $array);
    }

    //アクセス権////////////////////////////////////////
    static function getAccess(){
        parent::$page = self::$page;
        return self::getCurrentValue('access');
    }

    static function getName(){
        parent::$page = self::$page;
        return self::getCurrentValue('name');
    }
}
?>
