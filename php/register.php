<?php
header("Content-Type: application/json");
include "db.php";

$name = trim($_POST['name']) ?? '';
$email = trim($_POST['email']) ?? '';
$password = trim($_POST['password']) ?? '';

if ($email === "" || $password === "" || $name === "") {
   http_response_code(400);
   echo json_encode(["status" => "error", "message" => "all fields are reqired"]);
   exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
   http_response_code(400);
   echo json_encode(["status" => "error", "message" => "invalid email"]);
   exit;
} 

// check if email exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    http_response_code(409);
    echo json_encode(["status" => "error", "message" => "Email exists"]);
    exit;
}

$hashed = password_hash($password, PASSWORD_BCRYPT);

$stmt = $conn->prepare(
    "INSERT INTO users (name, email, password) VALUES (?, ?, ?)"
);
$stmt->bind_param("sss", $name, $email, $hashed);

if ($stmt->execute()) {
    echo json_encode(["status" => "success","message" => "successfully registered"]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error","message"=>"somthing went wrong!"]);
}