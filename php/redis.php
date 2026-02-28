<?php

require_once __DIR__ . '/../vendor/autoload.php';  

$redis = new Predis\Client([
    'scheme'   => 'rediss',
    'host'     => 'romantic-caribou-42148.upstash.io',
    'port'     => 6379,
    'password' => 'AaSkAAIncDE2OWU3ZTlmNzU5ODk0ODU4ODFkZTRiMWMxOTY0ZTUwMXAxNDIxNDg',
    'database' => 0,
]);

?>