<?php
header("Content-Type: application/json");

require "db.php";   // DB connection



if (!isset($_POST['token'])) {
    echo json_encode($response);
    exit;
}

$token = $_POST['token'];
$userId = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$contact = $_POST['contact'];

$stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, contact = ? WHERE id = ?");

$stmt->bind_param(
    "sssi",
    $name,
    $email,
    $contact,
    $userId
);
$stmt->execute();

echo json_encode(["status" => true, "message" => 'updated']);
$stmt->close();
$conn->close();