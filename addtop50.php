<?php
ini_set('max_execution_time', 0);
//ini_set('memory_limit', '512M');
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
echo 'script start<br/>';
define('AC_DIR', dirname(__FILE__));
require_once( AC_DIR . DIRECTORY_SEPARATOR . 'AngryCurl/classes' . DIRECTORY_SEPARATOR . 'simple_html_dom.php');
require_once( AC_DIR . DIRECTORY_SEPARATOR . 'AngryCurl/classes' . DIRECTORY_SEPARATOR . 'RollingCurl.class.php');
require_once( AC_DIR . DIRECTORY_SEPARATOR . 'AngryCurl/classes' . DIRECTORY_SEPARATOR . 'AngryCurl.class.php');
require_once( AC_DIR . DIRECTORY_SEPARATOR . 'AngryCurl/classes' . DIRECTORY_SEPARATOR . 'Helper.php');
require_once( AC_DIR . DIRECTORY_SEPARATOR . 'AngryCurl/classes' . DIRECTORY_SEPARATOR . 'db.php');
require_once( AC_DIR . DIRECTORY_SEPARATOR . 'AngryCurl/classes' . DIRECTORY_SEPARATOR . 'simple_html_dom.php');
$db = Database::getInstance();
echo '# got includes<br/>';
$context = stream_context_create(array('http' => array('header'=>'Connection: close\r\n')));
$hrefs = array();
$url = "https://www.similarweb.com/category";
echo "url is $url<br/>";
$html = file_get_html($url, false, $context);
echo '#get category page<br/>';
$categories = $html->find('.all-categories-list li a');
foreach($categories as $category){
    $url = 'http:'.$category->href;
    var_dump($category->href);
    $categoryHtml = file_get_html($url, false, $context);
    echo "#got category page $url<br/>";
    $hrefElements = $categoryHtml->find('.website-name');   
    foreach($hrefElements as $element){        
        array_push($hrefs, $element->plaintext);
        var_dump($element->plaintext);
    }
}

foreach($hrefs as $href){
    $query = "INSERT INTO data_items (url, in_priority) VALUES('$href', true)";
    var_dump($query);
    echo '<hr/>';
    $db->query($query);
}

