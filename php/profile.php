<?php
header("Content-Type: application/json");

require "db.php";   // DB connection



if (!isset($_GET['token'])) {
    echo json_encode(['message' => 'token missing']);
    exit;
}

$token = $_GET['token'];


try {
    $manager = new MongoDB\Driver\Manager("mongodb://127.0.0.1:27017");

    $database   = 'guvi_internship';           // your database name
    $collection = 'users';

    $userId = $_GET['id'];  

    $filter = ['user_id' => (int)$userId];   // important: cast to int

    $options = [
        'projection' => ['_id' => 0],        // optional: exclude _id
        'limit'      => 1                    // optional
    ];

    $query = new MongoDB\Driver\Query($filter, $options);

    $cursor = $manager->executeQuery("$database.$collection", $query);

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