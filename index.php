<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
require 'vendor/autoload.php';
require 'AngryCurl/classes/db.php';
$db = Database::getInstance();
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
$app = new \Slim\App;

$app->get('/', function($request, $response, $args) use($db){
    return $response->write(json_encode(array()));
});

$app->get('/site/{url}', function($request, $response, $args) use($db){
    $url = $request->getAttribute('url');
    $data = $db->getSite($url);
    return $response->write(json_encode($data));
});
$app->run();