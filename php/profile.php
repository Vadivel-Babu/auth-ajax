<?php
//header("Content-Type: application/json");
header("Accept: application/json");

require "db.php";   // DB connection
require "redis.php";


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
    $manager = new MongoDB\Driver\Manager($mongoUri);
    
    $collection = 'users';

    $userId = $_GET['id'];  

    $filter = ['user_id' => (int)$userId];   // important: cast to int

    $options = [
        'projection' => ['_id' => 0],        // optional: exclude _id
        'limit'      => 1                    // optional
    ];

    $query = new MongoDB\Driver\Query($filter, $options);

    $cursor = $manager->executeQuery("guvi_task.$collection", $query);

    $user = current($cursor->toArray());  // get first document as object

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