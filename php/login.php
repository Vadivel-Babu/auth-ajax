<?php
include "db.php";
include "redis.php";
ini_set('display_errors', 0);
error_reporting(0);
header("Content-Type: application/json");

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $conn->prepare("SELECT id, password FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || !password_verify($password, $user['password'])) {
    echo json_encode(["status" => "login failed"]);
    exit;
}

// generate session token
$token = bin2hex(random_bytes(16));
// // store in Redis (1 hour)
// $redis->set($token, $user['id']);
// $redis->expire($token, 3600);
// $stmt2 = $conn->prepare(
//     "INSERT INTO sessions (user_id, token) VALUES (?, ?)"
// );
// $stmt2->bind_param("is", $user_id, $token);
// $stmt2->execute();

echo json_encode([
    "status" => "success",
    "token" => $token,
    "user" => $user
]);
exit;