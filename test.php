<?php
require_once __DIR__ . '/vendor/autoload.php';

use MongoDB\Client;

echo "<pre>";
echo "MongoDB Client exists? " . (class_exists('MongoDB\Client') ? 'YES' : 'NO') . "\n";

if (class_exists('MongoDB\Client')) {
    echo "SUCCESS - MongoDB library is loaded!\n";
} else {
    echo "FAIL\n";
    echo "Vendor mongodb folder:\n";
    print_r(glob(__DIR__ . '/vendor/mongodb/*'));
}
?>