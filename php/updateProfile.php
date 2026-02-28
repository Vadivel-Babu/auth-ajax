<?php
header("Content-Type: application/json");
header("Accept: application/json");

require "db.php";   // DB connection



if (!isset($_POST['token'])) {
    echo json_encode(['message' => 'token missing']);
    exit;
}

$token = $_POST['token'];
$userId = $_POST['id'];
$name = trim($_POST['name']) ?? '';
$email = $_POST['email'];
$contact = $_POST['contact'];
$age = $_POST['age'];

if ($name === "") {
   http_response_code(400);
   echo json_encode(["status" => "error", "message" => "name reqired"]);
   exit;
}
//$mongoUri = getenv('MONGO_URI') ?: 'mongodb://127.0.0.1:27017';
$mongoUri="mongodb+srv://vadivelbabu31_db_user:2gUyS2XDeG0lrVR7@cluster0.hrfomwk.mongodb.net/guvi_task?retryWrites=true&w=majority";
$stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");

$stmt->bind_param(
    "ssi",
    $name,
    $email,
    $userId
);
$stmt->execute();
$stmt->close();
$conn->close();
try {   
    $manager = new MongoDB\Driver\Manager( $mongoUri);
 
    $collection = 'users';

  $filter = [
        'user_id' => (int)$userId   // usually from MySQL id
    ];

    // What to change
    $update = [
        '$set' => [
            'name' => $name,
            'contact' => $contact,
            'age' => (int)$age
        ]
    ];

    $options = [
        'upsert' => false   // false = only update if exists, true = insert if not found
    ];

    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->update($filter, $update, $options);

    $result = $manager->executeBulkWrite("guvi_task.$collection", $bulk);

} catch (Throwable $e) {
    // Log but don't stop login
    error_log("MongoDB profile update failed: " . $e->getMessage());
}

echo json_encode(["status" => true, "message" => 'Profile updated']);