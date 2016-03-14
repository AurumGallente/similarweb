<?php
# Setting time and memory limits
ini_set('max_execution_time',0);
ini_set('memory_limit', '128M');
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

define('AC_DIR', dirname(__FILE__) );
function callback($output, $info, $request)
{
    $helper=  Helper::getInstance($output);    
    $globalRank = Helper::parseGlobalRank();
    $categoryRank = Helper::parseCategoryRank();
    $countryRank = Helper::parseCountryRank();
    $totalVisits = Helper::parseTotalVisits();
    echo '<pre>';
    echo 'global is '.$globalRank.'<br/>';
    echo 'category is '.$categoryRank.'<br/>';
    echo 'country is '.$countryRank.'<br/>';
    echo 'visits is '.$totalVisits.'<br/>';
    echo '</pre>';
    return;
}
# Including classes
require_once( AC_DIR  . DIRECTORY_SEPARATOR . 'AngryCurl/classes' . DIRECTORY_SEPARATOR . 'RollingCurl.class.php');
require_once( AC_DIR  . DIRECTORY_SEPARATOR . 'AngryCurl/classes' . DIRECTORY_SEPARATOR . 'AngryCurl.class.php');
require_once( AC_DIR  . DIRECTORY_SEPARATOR . 'AngryCurl/classes' . DIRECTORY_SEPARATOR . 'Helper.php');
# Initializing AngryCurl instance with callback function named 'callback_function'
$AC = new AngryCurl('callback');

$AC->init_console();
$AC->load_proxy_list(
    AC_DIR  . DIRECTORY_SEPARATOR . 'AngryCurl/import' . DIRECTORY_SEPARATOR . 'topguard.txt',
    # optional: number of threads
    200,
    # optional: proxy type
    'http',
    # optional: target url to check
    'http://www.similarweb.com/'
    
    
);
$AC->load_useragent_list( AC_DIR . DIRECTORY_SEPARATOR . 'AngryCurl/import' . DIRECTORY_SEPARATOR . 'useragent_list.txt');
//for($i=1; $i<=10; $i++){
    $AC->get("https://www.similarweb.com/website/work.ua");    
//}

$AC->execute(1);
//$AC->flush_requests();
