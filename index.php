<?php

require_once 'Pager_Wrapper.php';
require_once 'DB.php';
require_once 'config/config.php';
require_once 'vendor/autoload.php';

// status on delete reports from database
$status = "";

// get our DB object

$DB = DB::connect($dsn, $dsnOptions);
if (PEAR::isError($DB)) {
    die($DB->getMessage());
}

$action = ( isset($_POST['action']) ) ? $_POST['action'] : NULL;

//var_dump($_POST);

if ( $_POST ) {
    
    if ( $action == "delete-reports") {
        
        if ( is_array($_POST['report']) ) {
            
            // integer sanitize our $_POST['report'] array  
            // remove + and - signs
            $report_serials = str_replace(array('+', '-'), '', filter_var($_POST['report'], FILTER_SANITIZE_NUMBER_INT, array('flags' => FILTER_REQUIRE_ARRAY)));
            
            // filter out empty array values
            $report_serials = array_filter( $report_serials );
            
            // reports records will get automatically removed via mysql CONSTRAINT and ON DELETE CASCADE
            $sql = 'DELETE FROM `' . $db_table_report . '` WHERE report.serial in ('.implode( ',', $report_serials).' )';
            
            $res = $DB->query($sql);
            
            if (PEAR::isError($res)) {
                die($res->getMessage());
            }
            
            $status = "DMARC report(s) successfully removed from database!";
            

        }
    }
}

$perPage = ( isset($_REQUEST['perpage']) ) ? $_REQUEST['perpage'] : $pager_default['perPage'];

$pagerOptions = array(
    'mode'       => 'Sliding',
    'perPage'    => $perPage,
    'delta'      => 2,
    'fileName'  => 'index.php?perpage='. $perPage . '&pageID=%d',
    'append' => false
);

$query = "SELECT report.* , sum(rptrecord.rcount) as rcount FROM `" . $db_table_report . "` LEFT Join `" . $db_table_record . "` on report.serial = rptrecord.serial group by serial order by mindate DESC";

$pagerData = Pager_Wrapper_DB($DB, $query, $pagerOptions);

//var_dump($pagerData);

use SavantPHP\SavantPHP;

$savantConfig = [
    \SavantPHP\SavantPHP::TPL_PATH_LIST => [ dirname(__FILE__) .'/views/'], //as you can see, set all possible places where your template will reside
    // \SavantPHP\SavantPHP::CONTAINER     => $yourContainer //can be anything, e.g a pimple container
];

$tpl = new SavantPHP($savantConfig);
$tpl->title = 'DMARC Report Viewer Extended';
$tpl->heading = 'DMARC Report Viewer Extended';
$tpl->reports = $pagerData['data'];
$tpl->pager = $pagerData['links'];
$tpl->perpage = $perPage;
$tpl->footer_credit = $footer_credit;
$tpl->status = $status;
$tpl->records_total = $pagerData['totalItems'];
$tpl->records_from = $pagerData['from'];
$tpl->records_to = $pagerData['to'];

$tpl->setTemplate('index.tpl.php');
$tpl->display(); //or $response = $tpl->getOutput();
?>