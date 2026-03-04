<?php
//header("Content-Type: application/json");
header("Accept: application/json");
require_once __DIR__ . '/../vendor/autoload.php'; 

require "db.php";   // DB connection
require "redis.php";
use MongoDB\Client;

$token = $_GET['token'];

if (empty($token)) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'No token']);
    exit;
}

$sessionJson = $redis->get("session:$token");

if (!$sessionJson) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Invalid/expired token']);
    exit;
}
//$mongoUri = getenv('MONGO_URI') ?: 'mongodb://127.0.0.1:27017';
$mongoUri="mongodb+srv://vadivelbabu31_db_user:2gUyS2XDeG0lrVR7@cluster0.hrfomwk.mongodb.net/guvi_task?retryWrites=true&w=majority";

try {
    $client = new Client($mongoUri);
    $db = $client->guvi_task;
    $collection = $db->users;
    $userId = $_GET['id'];  

    // Fetch one user
    $user = $collection->findOne(['user_id' => $userId]);

    if ($user) {
        echo json_encode([
            'status'  => 'success',
            'user' => $user
        ]);
    } else {
        echo json_encode([
            'status'  => 'not_found',
            'message' => 'No profile found'
        ]);
    }

} catch (Throwable $e) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'MongoDB error: ' . $e->getMessage()
    ]);
}
?>