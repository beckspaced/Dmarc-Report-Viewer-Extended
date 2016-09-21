<?php

/**
 * Main Config File
 */

// general database settings
$db_host = "localhost";
$db_name = "dmarc";
$db_user = "dmarc";
$db_pass = "secretpassword";

// PEAR DB DSN Data Source Name
// needed for stats

$dsn = array(
    'phptype'  => 'mysql',
    'username' => $db_user,
    'password' => $db_pass,
    'hostspec' => $db_host,
    'database' => $db_name
);

$dsnOptions = array(
    'debug'       => 2,
    'portability' => DB_PORTABILITY_ALL
);

$db_table_report = "report";
$db_table_record = "rptrecord";

$pager_default = array(
    'perPage' => 10
);

$footer_credit = <<<EOT
DMARC Report Viewer Extended based on <a href="https://github.com/techsneeze/dmarcts-report-viewer" target="_blank">techsneeze/dmarcts-report-viewer</a> and <a href="https://github.com/techsneeze/dmarcts-report-parser" target="_blank">techsneeze/dmarcts-report-parser</a> - thank you!
EOT;


?>