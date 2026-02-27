<?php

// $redis = new Redis();
if (class_exists('Redis')) {
    echo "Redis extension is LOADED!";
} else {
    echo "Redis extension NOT loaded.";
}

echo "<br><br>";
phpinfo();   // check for "redis" section

// try {
//     $redis->connect('127.0.0.1', 6379);
//     $redis->set('test_key', 'Hello from Redis!');
//     echo $redis->get('test_key');  // should print Hello from Redis!
// } catch (Exception $e) {
//     echo "Connection failed: " . $e->getMessage();
// }
?>