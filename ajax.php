<?php

require_once 'DB.php';
require_once 'config/config.php';
require_once 'vendor/autoload.php';

$DB = DB::connect($dsn, $dsnOptions);
if (PEAR::isError($DB)) {
    die($DB->getMessage());
}

$query = "SELECT * FROM `" . $db_table_record . "` where serial = ?";

//$serial = (int) $_GET['report'];

// integer sanitize and filter + and - signs $_GET['report']
$serial = str_replace(array('+', '-'), '', filter_var( $_GET['report'], FILTER_SANITIZE_NUMBER_INT ));

$res = $DB->query($query, $serial);

if (PEAR::isError($res)) {
    die($res->getMessage());
}

while ($res->fetchInto($row, DB_FETCHMODE_ASSOC)) {
    
    $reports[] = $row;
}

use SavantPHP\SavantPHP;

$savantConfig = [
    \SavantPHP\SavantPHP::TPL_PATH_LIST => [ dirname(__FILE__) .'/views/'], //as you can see, set all possible places where your template will reside
    // \SavantPHP\SavantPHP::CONTAINER     => $yourContainer //can be anything, e.g a pimple container
];


$tpl = new SavantPHP($savantConfig);

$tpl->reports = $reports;

$tpl->setTemplate('report.tpl.php');
$tpl->display(); //or $response = $tpl->getOutput();
?>