<?php

/**
 * Usage:
 * $db = Database::getInstance();
 * $results = $db->query("SELECT * FROM users WHERE email = :email",array(":email" => $email));
 *
 */
require_once('config.php');

class Database {

    private static $conn;
    
    private function __construct() {
        $config = Config::getInstance()->dbOptions;
        $host = $config['host'];
        $port = $config['port'];
        $dbname = $config['dbname'];
        $user = $config['user'];
        $password = $config['password'];
        self::$conn = pg_connect("host=$host  port=$port dbname=$dbname user=$user password=$password");
    }

    private static $instance = null;
    public $result;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function query($query) {
        $result = pg_query(self::$conn, $query);       
        $results = array();
        while ($row = pg_fetch_assoc($result)) {
            $results[] = $row;
        }
        $this->result = $results;
        return self::$instance;
    }
    public function getSite($url){
        $url = pg_escape_string($url);
        $query = "select data, to_char(timestamp, 'YYYY-MM-DD') as timestamp from data_items where url='$url' order by timestamp asc";
        return self::query($query)->result;
    }

    public function connectionClose() {
        pg_close(self::$conn);
    }
    public function __destruct() {
        pg_close(self::$conn);
    }

}
