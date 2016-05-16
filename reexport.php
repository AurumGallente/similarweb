<?php

ini_set('max_execution_time', 0);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
define('AC_DIR', dirname(__FILE__));
require_once( AC_DIR . DIRECTORY_SEPARATOR . 'AngryCurl/classes' . DIRECTORY_SEPARATOR . 'Helper.php');
require_once( AC_DIR . DIRECTORY_SEPARATOR . 'AngryCurl/classes' . DIRECTORY_SEPARATOR . 'db.php');
$limit = 1000;
$offset = 0;
$q = <<<SQL
select url, 
(data->'overview'->'WeeklyTrafficNumbers'->'2015-09-01')::text::int as "2015-09-01",
(data->'overview'->'WeeklyTrafficNumbers'->'2015-10-01')::text::int as "2015-10-01",
(data->'overview'->'WeeklyTrafficNumbers'->'2015-11-01')::text::int as "2015-11-01",
(data->'overview'->'WeeklyTrafficNumbers'->'2015-12-01')::text::int as "2015-12-01",
(data->'overview'->'WeeklyTrafficNumbers'->'2016-01-01')::text::int as "2015-01-01",
(data->'overview'->'WeeklyTrafficNumbers'->'2016-02-01')::text::int as "2015-02-01",
(data->'overview'->'WeeklyTrafficNumbers'->'2016-03-01')::text::int as "2015-03-01",
(data->'overview'->'WeeklyTrafficNumbers'->'2016-04-01')::text::int as "2015-04-01"
from data_items where is_parsed order by id asc limit $limit offset $offset          
SQL
;
do {
    var_dump($offset);
    $result = Helper::query($q);
    foreach ($result as $row) {
        $row['2015-09-01'] = isset($row['2015-09-01']) ? $row['2015-09-01'] : 'NULL';
        $row['2015-10-01'] = isset($row['2015-10-01']) ? $row['2015-10-01'] : 'NULL';
        $row['2015-11-01'] = isset($row['2015-11-01']) ? $row['2015-11-01'] : 'NULL';
        $row['2015-12-01'] = isset($row['2015-12-01']) ? $row['2015-12-01'] : 'NULL';
        $row['2016-01-01'] = isset($row['2016-01-01']) ? $row['2016-01-01'] : 'NULL';
        $row['2016-02-01'] = isset($row['2016-02-01']) ? $row['2016-02-01'] : 'NULL';
        $row['2016-03-01'] = isset($row['2016-03-01']) ? $row['2016-03-01'] : 'NULL';
        $row['2016-04-01'] = isset($row['2016-04-01']) ? $row['2016-04-01'] : 'NULL';
        $query = <<<SQL
            update parsed_data set "2015-09-01"={$row['2015-09-01']}, "2015-10-01"={$row['2015-10-01']}, "2015-11-01"={$row['2015-11-01']},
            "2015-12-01"={$row['2015-12-01']}, "2016-01-01"={$row['2016-01-01']}, "2016-02-01"={$row['2016-02-01']}, "2016-03-01"={$row['2016-03-01']},
            "2016-04-01"={$row['2016-04-01']}
            
   where url='{$row['url']}';
SQL;
        Helper::query($query);
        $offset +=$offset;
    }
} while (count($q) > 0);
