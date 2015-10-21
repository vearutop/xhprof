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


$path = $_SERVER['argv'][1];
$url = $_SERVER['argv'][2];
$profiler_namespace = isset($_SERVER['argv'][3]) ? $_SERVER['argv'][3] : 'default';

if (empty($path)) {
    die('Profile required');
}

$profiles = array();

if (is_dir($path)) {
    if ($handle = opendir($path)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                $profiles []= $path . '/' . $entry;
            }
        }
        closedir($handle);
    }
}
else {
    $profiles[] = $path;
}


foreach ($profiles as $file) {
    $xhprof_data = unserialize(file_get_contents($file));
    $_SERVER['REQUEST_URI'] = $url;
    $_POST = array();

    $xhprof_runs = new XHProfRuns_Default();
    $run_id = $xhprof_runs->save_run($xhprof_data, $profiler_namespace, null, $_xhprof);
    $profiler_url = sprintf($_xhprof['url'].'/index.php?run=%s&source=%s', $run_id, $profiler_namespace);
    echo $profiler_url, PHP_EOL;
}


