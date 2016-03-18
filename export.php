<?php
ini_set('max_execution_time', 0);
//ini_set('memory_limit', '128M');
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
define('AC_DIR', dirname(__FILE__));
require_once( AC_DIR . DIRECTORY_SEPARATOR . 'AngryCurl/classes' . DIRECTORY_SEPARATOR . 'Helper.php');
require_once( AC_DIR . DIRECTORY_SEPARATOR . 'AngryCurl/classes' . DIRECTORY_SEPARATOR . 'db.php');
//header('Content-Type: text/csv; charset=utf-8');
//header('Content-Disposition: attachment; filename=data.csv');
//$output = fopen('php://output', 'w');
$result = Helper::fetchAll();
$data  = array();
$dates = array();
$header = array('url');
foreach($result as $k=>$v){
    $json = json_decode($v['data'], true);
    $data[$v['url']] = array();
    //var_dump($json['overview']['WeeklyTrafficNumbers']);
    foreach($json['overview']['WeeklyTrafficNumbers'] as $date=>$visits){        
        $data[$v['url']][$date] = $visits;
        in_array($date,$header) ? null : array_push($header, $date);
    }
}

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=file.csv");
header("Pragma: no-cache");
header("Expires: 0");
$res = array();
array_push($res, $header);
foreach($header as $h){
    $tmp = array();    
    foreach ($data as $url=>$visits){
        if($h!='url'){                 
            $tmp[] = $visits[$h];            
        } 
        $tmp = array();
    }
}
array_shift($header);
function outputCSV($data, $header) {
    $output = fopen("php://output", "w");
    array_unshift($header, 'url');
    fputcsv($output, $header);
    foreach ($data as $k=>$row) {
        array_unshift($row, $k);
        fputcsv($output, $row); 
    }
    fclose($output);
}
outputCSV($data, $header);