<?php
header("Content-Type: application/json");
header("Accept: application/json");
require_once __DIR__ . '/../vendor/autoload.php';
require "db.php";   // DB connection
use MongoDB\Client;


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
    $client = new Client($mongoUri);

    $collection = $client->guvi_task->users;  

    $updateData = [
        'user_id'    => $userId,
        'name'       => $user['name'] ?? '',
        'email'      => $user['email'] ?? '',
        'age'        => $user['age'] ?? null,
        'contact'    => $user['contact'] ?? null,
    ];

   $result = $collection->updateOne(
        ['user_id' => $userId],               // find by user_id
        ['$set' => $updateData],              // update these fields
        ['upsert' => true]                    // create if not exists
    );

} catch (Throwable $e) {
    // Log but don't stop login
    error_log("MongoDB profile update failed: " . $e->getMessage());
}

echo json_encode(["status" => true, "message" => 'Profile updated']);