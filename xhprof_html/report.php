<?php

if (!defined('XHPROF_LIB_ROOT')) {
    define('XHPROF_LIB_ROOT', dirname(dirname(__FILE__)) . '/xhprof_lib');
}

include(dirname(__FILE__) . '/../xhprof_lib/config.php');
include_once dirname(__FILE__) . '/../xhprof_lib/utils/xhprof_lib.php';
include_once dirname(__FILE__) . '/../xhprof_lib/utils/xhprof_runs.php';


$_xhprof['savepost'] = true;
$_xhprof['display'] = true;
$_xhprof['doprofile'] = true;
$_xhprof['type'] = 1;

$postBody = file_get_contents('php://input');

$xhprof_data = unserialize($postBody);
$_SERVER['REQUEST_URI'] = isset($_GET['url']) ? $_GET['url'] : null;
$timestamp = isset($_GET['timestamp']) ? $_GET['timestamp'] : null;
$profiler_namespace = isset($_GET['namespace']) ? isset($_GET['namespace']) : 'default';
$_SERVER['SERVER_NAME'] = isset($_GET['server']) ? $_GET['server'] : '';

$_GET = array();

$xhprof_runs = new XHProfRuns_Default();
$run_id = $xhprof_runs->save_run($xhprof_data, $profiler_namespace, null, $_xhprof, $timestamp);
$profiler_url = sprintf($_xhprof['url'].'/index.php?run=%s&source=%s', $run_id, $profiler_namespace);
echo $profiler_url, PHP_EOL;