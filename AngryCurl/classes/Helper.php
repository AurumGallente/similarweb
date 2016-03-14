<?php
/*
 * всякое непотребство / common stuff
 */
class Helper {
    public static $html=false;
    private static $instance=null;
    public static function getInstance($html=null){
        if(!!$html){
            self::$html = $html;
        }
        if (!!self::$instance){
            self::$instance = new self;
        }
        return self::$instance;
    }
    public static function setHtml($html){
        self::$html = $html;
    }
    private static function getHtml(&$html){
        if(!$html){
           $html = self::$html; 
        }
    }
    private function __construct(){
        
    }
    public static function parseGlobalRank($html=null){
        self::getHtml($html);
        $pattern = '/<span class="rankingItem-value js-countable" data-value="(.*)">/';
        preg_match_all($pattern, $html, $matches);
        $rank = (isset($matches[1])) ? floatval(str_replace(',', '.', str_replace('.', '', $matches[1][0]))) : null;               
        return $rank;        
    }
    public static function parseCountryRank($html=null){
        self::getHtml($html);
        $pattern = '/<span class="rankingItem-value js-countable" data-value="(.*)">/';
        preg_match_all($pattern, $html, $matches);
        $rank = (isset($matches[1])) ? (int)($matches[1][1]) : null;        
        return $rank;
    } 
    public static function parseCategoryRank($html=null){
        self::getHtml($html);
        $pattern = '/<span class="rankingItem-value js-countable" data-value="(.*)">/';
        preg_match_all($pattern, $html, $matches);
        $rank = (isset($matches[1])) ? ($matches[1][2]) : null;        
        return $rank;
    }
    public static function parseTotalVisits($html=null){
        self::getHtml($html);
        $pattern = '/<span class="engagementInfo-value engagementInfo-value--large u-text-ellipsis">(.*)<\/span>/';
        preg_match($pattern, $html, $matches);        
        $rank = (isset($matches[1])) ? ($matches[1]) : null;        
        return $rank;
    }
}