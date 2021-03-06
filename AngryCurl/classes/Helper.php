<?php

/*
 * всякое непотребство / common stuff
 */
require_once('db.php');

class Helper {

    public static $html = false;
    private static $instance = null;

    public static function getInstance($html = null) {
        if (!!$html) {
            self::$html = $html;
        }
        if (!!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public static function setHtml($html) {
        self::$html = $html;
    }

    private static function getHtml(&$html) {
        if (!$html) {
            $html = self::$html;
        }
    }

    private function __construct() {
        
    }

    public static function parseGlobalRank($html = null) {
        self::getHtml($html);
        $pattern = '/<span class="rankingItem-value js-countable" data-value="(.*)">/';
        preg_match_all($pattern, $html, $matches);
        $rank = (isset($matches[1])) ? floatval(str_replace(',', '.', str_replace('.', '', $matches[1][0]))) : null;
        return $rank;
    }

    public static function parseCountryRank($html = null) {
        self::getHtml($html);
        $pattern = '/<span class="rankingItem-value js-countable" data-value="(.*)">/';
        preg_match_all($pattern, $html, $matches);
        $rank = (isset($matches[1])) ? (int) ($matches[1][1]) : null;
        return $rank;
    }

    public static function parseCategoryRank($html = null) {
        self::getHtml($html);
        $pattern = '/<span class="rankingItem-value js-countable" data-value="(.*)">/';
        preg_match_all($pattern, $html, $matches);
        $rank = (isset($matches[1])) ? ($matches[1][2]) : null;
        return $rank;
    }

    public static function parseTotalVisits($html = null) {
        self::getHtml($html);
        $pattern = '/<span class="engagementInfo-value engagementInfo-value--large u-text-ellipsis">(.*)<\/span>/';
        preg_match($pattern, $html, $matches);
        $rank = (isset($matches[1])) ? ($matches[1]) : null;
        return $rank;
    }

    public static $overview;

    public static function insertSimilarSites($output) {
        $pattern = '/data-url="(.*)">/';
        preg_match_all($pattern, $output, $matches);
        $data = isset($matches[1][0]) ? $matches[1] : null;
        if ($data) {
            $values = "";
            $max = count($data);
            for ($i = 0; $i <= $max - 1; $i++) {
                $values.=strtolower("('{$data[$i]}'),");
            }
            $values = rtrim($values, ",");
            $query = "WITH items as (
            SELECT items FROM (VALUES $values)
            s(items)
            ),
            current_month as (
            select date_trunc('month', now()) as time
            )
            INSERT INTO data_items (url)
            SELECT items FROM items WHERE NOT EXISTS 
            (SELECT url FROM data_items, current_month WHERE data_items.url=items.items AND data_items.is_parsed=false AND data_items.is_parsed=false and date_trunc('month', data_items.timestamp)=current_month.time)";
            Database::getInstance()->query($query);
        }
    }

    public static function updateSingleSite($output, $info, $request) {
        $site = strtolower(str_replace('https://www.similarweb.com/website/','',$request->url));
        $pattern = '/Sw.preloadedData = (.*);(\r\n|\r|\n)/';
        preg_match_all($pattern, $output, $matches);
        $data = isset($matches[1][0]) ? $matches[1][0] : null;
        if ($data) {
            $query = "UPDATE data_items SET data= '$data', is_parsed=true WHERE url='$site' AND is_parsed=false";
            echo "#get info about $site \r\n";            
            Database::getInstance()->query($query);
            
        } else {
            $query = "DELETE FROM data_items WHERE is_parsed=false AND url='$site' AND in_priority=false";            
            echo "# no info about $site so it was deleted  <i>(unless it is in top-50)</i>\r\n";
            Database::getInstance()->query($query);
        }
        Helper::insertSimilarSites($output);
    }

    public static function getSitesToParse(&$AC) {
        $db = Database::getInstance();
        $query = "SELECT url FROM data_items WHERE is_parsed=false ORDER BY in_priority DESC LIMIT 100";
        $results = $db->query($query)->result;
        $AC->flush_requests();
        $sites = array();
        if (count($results) > 0) {
            foreach ($results as $k => $site) {                
                array_push($sites, $site['url']);
            }            
        }
        return $sites;
    }
    public static function fetchAll(){
        $db = Database::getInstance();
        $query = "SELECT url, data FROM data_items WHERE is_parsed=true order by id asc";
        $data = $db->query($query)->result;
        return $data;
    }
    public static function close(){
        $db = Database::getInstance();
        $query = "delete from data_items
    where exists (select 1
                  from data_items t2
                  where t2.url = data_items.url and
                        t2.data = data_items.data and
                        t2.is_parsed = data_items.is_parsed
                        
                 );";
        //$db->query($query);
        Database::getInstance()->connectionClose();
    }
    public static function getProxyList(){
        $db = Database::getInstance();
        $query = "SELECT * FROM proxy_list";
        return $db->query($query)->result;
    }
    public static function getProxy($address){
        $db = Database::getInstance();
        $query = "select pl.address as address, pl.type as type, pa.login as login, pa.password as password from proxy_list pl inner join proxy_auth pa ON (pl.auth_id=pa.id)
where pl.address = '$address' LIMIT 1";
        return $db->query($query)->result[0];
    }
    public static function query($query){
        $db = Database::getInstance();
        return $db->query($query)->result;
    }    
}
