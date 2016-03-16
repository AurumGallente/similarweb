<?php

# Setting time and memory limits
ini_set('max_execution_time', 0);
ini_set('memory_limit', '128M');
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
define('AC_DIR', dirname(__FILE__));
require_once( AC_DIR . DIRECTORY_SEPARATOR . 'AngryCurl/classes' . DIRECTORY_SEPARATOR . 'simple_html_dom.php');
require_once( AC_DIR . DIRECTORY_SEPARATOR . 'AngryCurl/classes' . DIRECTORY_SEPARATOR . 'RollingCurl.class.php');
require_once( AC_DIR . DIRECTORY_SEPARATOR . 'AngryCurl/classes' . DIRECTORY_SEPARATOR . 'AngryCurl.class.php');
require_once( AC_DIR . DIRECTORY_SEPARATOR . 'AngryCurl/classes' . DIRECTORY_SEPARATOR . 'Helper.php');
require_once( AC_DIR . DIRECTORY_SEPARATOR . 'AngryCurl/classes' . DIRECTORY_SEPARATOR . 'db.php');
echo '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />';
$dataToSearch = null;

function callback($output, $info, $request) {
    Helper::updateSingleSite($output, $info, $request);
    return;
}

# Initializing AngryCurl instance with callback function named 'callback_function'
$AC = new AngryCurl('callback');

$AC->init_console();
$AC->load_proxy_list(
        AC_DIR . DIRECTORY_SEPARATOR . 'AngryCurl/import' . DIRECTORY_SEPARATOR . 'topguard.txt',
        # optional: number of threads
        200,
        # optional: proxy type
        'http',
        # optional: target url to check
        'http://www.similarweb.com/'
);
$AC->load_useragent_list(AC_DIR . DIRECTORY_SEPARATOR . 'AngryCurl/import' . DIRECTORY_SEPARATOR . 'useragent_list.txt');

$AC->get("https://www.similarweb.com/website/work.ua");

$sites = Helper::getSitesToParse($AC);
if (count($sites) > 0) {
    foreach ($sites as $site) {
        $AC->get("https://www.similarweb.com/website/$site");
    }
}
$AC->execute(5);
//$AC->flush_requests();

Database::getInstance()->connectionClose();
