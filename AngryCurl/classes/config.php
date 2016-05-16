<?php

class Config {

    public $proxyLogin = '';
    public $proxyPassword = '';
    public $dbOptions = [
        'host' => 'localhost',
        'port' => '5432',
        'user' => 'postgres',
        'password' => '',
        'dbname' => 'similarweb',
        'persistent' => false        
    ];
    private static $instance = null;

    private function __construct() {
        
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

}
