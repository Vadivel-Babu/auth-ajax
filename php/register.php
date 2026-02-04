<?php
include "db.php";

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!$name || !$email || !$password) {
    echo json_encode(["status" => "error", "msg" => "All fields required"]);
    exit;
}

// check if email exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["status" => "error", "msg" => "Email exists"]);
    exit;
}

$hashed = password_hash($password, PASSWORD_BCRYPT);

$stmt = $conn->prepare(
    "INSERT INTO users (name, email, password) VALUES (?, ?, ?)"
);
$stmt->bind_param("sss", $name, $email, $hashed);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error"]);
}