<?php
ini_set('max_execution_time', 0);
ini_set('memory_limit', '128M');
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
foreach($result as $k=>$v){
    $json = json_decode($v['data'], true);
    $data[$v['url']] = array();
    //var_dump($json['overview']['WeeklyTrafficNumbers']);
    foreach($json['overview']['WeeklyTrafficNumbers'] as $date=>$visits){        
        $data[$v['url']][$date] = $visits;
    }
}
var_dump($data);