<?php

if (!defined('XHPROF_LIB_ROOT')) {
    define('XHPROF_LIB_ROOT', dirname(dirname(__FILE__)) . '/xhprof_lib');
}

include(dirname(__FILE__) . '/../xhprof_lib/config.php');
include_once dirname(__FILE__) . '/../xhprof_lib/utils/xhprof_lib.php';
include_once dirname(__FILE__) . '/../xhprof_lib/utils/xhprof_runs.php';


$_xhprof['savepost'] = false;
$_xhprof['display'] = true;
$_xhprof['doprofile'] = true;
$_xhprof['type'] = 1;


$xhprof_data = unserialize($_POST['xhprof_data']);
$_SERVER['REQUEST_URI'] = isset($_POST['url']) ? $_POST['url'] : null;
$_POST = isset($_POST['meta']) ? unserialize($_POST['meta']) : array();


$profiler_namespace = isset($_POST['namespace']) ? isset($_POST['namespace']) : 'default';
$xhprof_runs = new XHProfRuns_Default();
$run_id = $xhprof_runs->save_run($xhprof_data, $profiler_namespace, null, $_xhprof);
$profiler_url = sprintf($_xhprof['url'].'/index.php?run=%s&source=%s', $run_id, $profiler_namespace);
echo $profiler_url;