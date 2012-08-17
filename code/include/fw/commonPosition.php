<?php
require_once('fw/positionManager.php');
class commonPosition extends positionManager
{
    static protected $page = array
    (
    'index'=>array
    (
        'name'=>'no-fee tokyo apartments rent','func'=>null,'ssl'=>FALSE,'gnavi'=>'index','snavi'=>null
    ),
    'notfound'=>array
    (
        'name'=>'notfound','func'=>null,'ssl'=>FALSE,'gnavi'=>'index','snavi'=>null
    ),
    'area'=>array
    (
        'name'=>'area','func'=>null,'ssl'=>FALSE,'gnavi'=>'index','snavi'=>null
    ),
    'campaign'=>array
    (
        'name'=>'campaign','func'=>null,'ssl'=>FALSE,'gnavi'=>'index','snavi'=>null
    ),
    'view'=>array
    (
        'name'=>'view','func'=>null,'ssl'=>FALSE,'gnavi'=>'index','snavi'=>null
    ),
    'company'=>array
    (
        'name'=>'company profile','func'=>null,'ssl'=>FALSE,'gnavi'=>'index','snavi'=>null
    ),
    'privacy'=>array
    (
        'name'=>'privacy policy of Tokyo Apartments Rent','func'=>null,'ssl'=>FALSE,'gnavi'=>'index','snavi'=>null
    ),
    'site_map'=>array
    (
        'name'=>'site map of Tokyo Apartments Rent','func'=>null,'ssl'=>FALSE,'gnavi'=>'index','snavi'=>null
    ),
    'inquiry'=>array
    (
        'input'=>array
        (
            'name'=>'Contact to the apartments','func'=>null,'ssl'=>FALSE,'gnavi'=>null,'snavi'=>null
        ),
        'finish'=>array
        (
            'name'=>'Contact to the apartments','func'=>null,'ssl'=>FALSE,'gnavi'=>null,'snavi'=>null
        ),
    ),
    );

    static public function makeSitePosition(){
        parent::$page = self::$page;
        parent::makeSitePosition();
    }

    static private $index = 1;

    static public function makeNumberPosition($url,$title,$trim = TRUE){
        parent::$position[self::$index] = parent::makePositionPair($url,$trim ? self::positionTrim($title) : $title);
        self::$index++;
    }

    static public function makeFirstPosition($url,$title,$trim = TRUE){
        parent::$position[1] = parent::makePositionPair($url,$trim ? self::positionTrim($title) : $title);
    }

    static public function makeSecondPosition($url,$title,$trim = TRUE){
        parent::$position[2] = parent::makePositionPair($url,$trim ? self::positionTrim($title) : $title);
    }

    static public function makeThirdPosition($url,$title,$trim = TRUE){
        parent::$position[3] = parent::makePositionPair($url,$trim ? self::positionTrim($title) : $title);
    }

    static public function makeFourPosition($url,$title,$trim = TRUE){
        parent::$position[4] = parent::makePositionPair($url,$trim ? self::positionTrim($title) : $title);
    }

    static public function makeFivePosition($url,$title,$trim = TRUE){
        parent::$position[5] = parent::makePositionPair($url,$trim ? self::positionTrim($title) : $title);
    }

    static public function makeSixPosition($url,$title,$trim = TRUE){
        parent::$position[6] = parent::makePositionPair($url,$trim ? self::positionTrim($title) : $title);
    }
}
?>
