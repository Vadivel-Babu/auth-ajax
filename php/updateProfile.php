<?php
header("Content-Type: application/json");

require "db.php";   // DB connection



if (!isset($_POST['token'])) {
    echo json_encode(['message' => 'token missing']);
    exit;
}

$token = $_POST['token'];
$userId = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$contact = $_POST['contact'];
$age = $_POST['age'];

// $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, contact = ? WHERE id = ?");

// $stmt->bind_param(
//     "sssi",
//     $name,
//     $email,
//     $contact,
//     $userId
// );
// $stmt->execute();
try {
    $manager = new MongoDB\Driver\Manager("mongodb://127.0.0.1:27017");

    $database   = 'guvi_internship';     
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

    $result = $manager->executeBulkWrite("$database.$collection", $bulk);

} catch (Throwable $e) {
    // Log but don't stop login
    error_log("MongoDB profile update failed: " . $e->getMessage());
}

echo json_encode(["status" => true, "message" => 'Profile updated']);
// $stmt->close();
// $conn->close();