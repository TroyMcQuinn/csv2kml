<?php

// This script converts a CSV of cell tower data to a KML file for use in Google Earth.

ini_set('memory_limit', '64M');

$d = Array();
$csv = file($_FILES['that_file']['tmp_name']);
$fn = explode(',',array_shift($csv));

foreach($csv as $rn => $row){
  str_replace(Array("\r","\n"),'',rtrim($row,','));
  $row = explode(',',trim($row));
  foreach($row as $n => $v){
    if(isset($fn[$n])){
      $d[$rn][trim($fn[$n])] = str_replace(',','',trim($v));
    }
  }
}

// print_r($d);

$o  = '<?xml version="1.0" encoding="UTF-8"?>
<kml xmlns="http://www.opengis.net/kml/2.2">
<Document>
';

foreach($d as $r){
  $o .= '<Placemark>'."\n";
  $o .= '<name>'.$r['owner_entity_name'].'</name>'."\n";
  $o .= '<description>';
  foreach($r as $n => $v){
    $o .= $n.': '.$v."\n";
  }
  $o .= '</description>';
  $o .= '<Point>'."\n";
  $o .= '<coordinates>'.$r['longitude'].','.$r['latitude'].',0</coordinates>'."\n";
  $o .= '</Point>'."\n";
  $o .= '</Placemark>'."\n";
}

$o .= '</Document>
</kml>';

header('Content-Disposition: attachment; filename="towers.kml"');
echo $o;


?>