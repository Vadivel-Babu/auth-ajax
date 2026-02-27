<?php
include "db.php";
include "redis.php";
// include "mongodb.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json");


$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
if (!$user || !password_verify($password, $user['password'])) {
    echo json_encode(["status" => "login failed"]);
    exit;
}

// generate session token
$token = bin2hex(random_bytes(16));
// // store in Redis (1 hour)
// $redis->set($token, $user['id']);
// $redis->expire($token, 3600);


try {
    $manager = new MongoDB\Driver\Manager("mongodb://127.0.0.1:27017");

    $database   = 'guvi_internship';     
    $collection = 'users';

    $profileData = [
        'user_id'    => (int)$user['id'],
        'name'       => $user['name'],
        'email'      => $user['email'],
    ];

    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->update(
        ['user_id' => (int)$user['id']],
        ['$set' => $profileData],
        ['upsert' => true]
    );

    $manager->executeBulkWrite("$database.$collection", $bulk);

} catch (Throwable $e) {
    // Log but don't stop login
    error_log("MongoDB profile update failed: " . $e->getMessage());
}

echo json_encode([
    "status" => "success",
    "token" => $token,
    "user" => $user
]);
exit;