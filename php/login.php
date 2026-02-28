<?php
include "db.php";
include "redis.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json");
header("Accept: application/json");



$email = trim($_POST['email']) ?? '';
$password = trim($_POST['password']) ?? '';

if ($email === "" || $password === "") {
   http_response_code(400);
   echo json_encode(["status" => "error", "message" => "all fields are reqired"]);
   exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
   http_response_code(400);
   echo json_encode(["status" => "error", "message" => "invalid email"]);
   exit;
} 

//$mongoUri = getenv('MONGO_URI') ?: 'mongodb://127.0.0.1:27017';
$mongoUri="mongodb+srv://vadivelbabu31_db_user:2gUyS2XDeG0lrVR7@cluster0.hrfomwk.mongodb.net/guvi_task?retryWrites=true&w=majority";

$stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if(!$user){
 http_response_code(404);
 echo json_encode(["status" => "error","message" => "user not found"]);
 exit;
}

if (!password_verify($password, $user['password'])) {
    http_response_code(400);
    echo json_encode(["status" => "error","message"=>"incorrect password"]);
    exit;
}

// generate session token
$token = bin2hex(random_bytes(16));
$sessionData = json_encode([
    'user_id' => $user['id'],
    'name'    => $user['name'],
    'email'   => $user['email'],
    'created' => time(),
]);

$redis->set("session:$token", $sessionData);
$redis->expire("session:$token", 86400); // 24 hours


try {
  
    $manager = new MongoDB\Driver\Manager($mongoUri);

     
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

    $manager->executeBulkWrite("guvi_task.$collection", $bulk);

} catch (Throwable $e) {
    // Log but don't stop login
    error_log("MongoDB profile update failed: " . $e->getMessage());
}

echo json_encode([
    "status" => "success",
    "token" => $token,
    "user" => ['id' => $user['id'],'name' => $user['name']]
]);
exit;