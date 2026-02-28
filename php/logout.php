<?php
header('Content-Type: application/json');

require_once __DIR__ . '/redis.php';  // your Predis or Redis connection

$token = $_POST['token'] ?? '';

if (!empty($token)) {
    $redisKey = "session:$token";
    $redis->del($redisKey);  // or $redis->del("session:$token") with Predis
}

echo json_encode([
    'status' => 'success',
    'message' => 'Logged out successfully'
]);
?>