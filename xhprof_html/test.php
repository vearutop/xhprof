<?php

xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);

for ($i = 0; $i < 1000; ++$i) {
    echo md5(microtime(1));
}

// the stuff hiare
$reportUrl = file_get_contents('http://xhprof.phph.tk/report.php', null, stream_context_create(array('http' =>
    array('method' => 'POST', 'header' => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query(array(
            'xhprof_data' => serialize(xhprof_disable()),
            'namespace' => 'test',
            'url' => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['SCRIPT_NAME'],
            'post' => $_POST,
        ))))));
