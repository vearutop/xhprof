XHProf profiles viewer (based on XHProf UI)
===========================================

Create http host pointing to `xhprof_html`.

Create database with one table:
```
 CREATE TABLE `details` (
 `id` char(17) NOT NULL,
 `url` varchar(255) default NULL,
 `c_url` varchar(255) default NULL,
 `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
 `server name` varchar(64) default NULL,
 `perfdata` MEDIUMBLOB,
 `type` tinyint(4) default NULL,
 `cookie` BLOB,
 `post` BLOB,
 `get` BLOB,
 `pmu` int(11) unsigned default NULL,
 `wt` int(11) unsigned default NULL,
 `cpu` int(11) unsigned default NULL,
 `server_id` char(3) NOT NULL default 't11',
 `aggregateCalls_include` varchar(255) DEFAULT NULL,
 PRIMARY KEY  (`id`),
 KEY `url` (`url`),
 KEY `c_url` (`c_url`),
 KEY `cpu` (`cpu`),
 KEY `wt` (`wt`),
 KEY `pmu` (`pmu`),
 KEY `timestamp` (`timestamp`)
 ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
```

Update `xhprof_lib/config.php` with you database credentials and http host url.

Collect profile(s).
```
if (extension_loaded('xhprof')) {
    xhprof_enable(XHPROF_FLAGS_MEMORY + XHPROF_FLAGS_CPU);
}

// do your stuff here

if (extension_loaded('xhprof')) {
    $data = xhprof_disable();
    $xhFilename = '/tmp/xhprof/' . microtime(1) . '.serialized';
    file_put_contents($xhFilename, serialize($data));
}
```


Import profiles to viewer.
```
php /path/to/xhprof_html/import.php /path/to/profiles/ profiling-session-name
```