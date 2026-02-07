<?php
header("Content-Type: application/json");

require "db.php";   // DB connection



if (!isset($_GET['token'])) {
    echo json_encode($response);
    exit;
}

$token = $_GET['token'];
$userId = $_GET['id'];

/*
sessions table example:
id | user_id | token
*/

$stmt = $conn->prepare("
    SELECT *
    FROM users   
    WHERE id = ?
");

$stmt->bind_param("s", $userId);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    echo json_encode([
        "status" => true,
        "user" => $user
    ]);
} else {
    echo json_encode($response);
}

$stmt->close();
$conn->close();