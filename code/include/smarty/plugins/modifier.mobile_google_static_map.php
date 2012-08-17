<?php
function smarty_modifier_mobile_google_static_map($address)
{
$queries = array();
$queries['q'] = $address;
$queries['key'] = MAPKEY;
$queries['sensor'] = 'false';
$queries['output'] = 'xml';
$queries['oe'] = 'utf8';
$queries['gl'] = 'jp';
$url = 'http://maps.google.com/maps/geo?' . http_build_query($queries);
$res = simplexml_load_file($url);
if ($res->Response->Status->code != 200) {
    return '123';
}
global $carrier;
$width = 240;
$height = 240;
if($carrier->softbank_vga){
    $width = 240 *2;
    $height = 240 *2;
}
$latLng = $res->Response->Placemark->Point->coordinates;
$ex = explode(',',$latLng);//緯度経度に分ける 緯度は2つめ、経度は1つ目
return '<img src="http://maps.google.com/staticmap?center='.$ex[1].','.$ex[0].'&amp;zoom=16&amp;size='.$width.'x'.$height.'&amp;maptype=mobile&amp;sensor=false&amp;markers='.$ex[1].','.$ex[0].'&amp;key='.MAPKEY.'" />';
}
?>