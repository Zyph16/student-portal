<?php
session_start();
header('Content-Type: application/json');

// Read POST data
$data = json_decode(file_get_contents("php://input"), true);

$username = $data['username'];
$password = $data['password'];

// Path to users.json file
$users_file = 'data/users.json';

// Read users from the JSON file
if (file_exists($users_file)) {
    $users_data = file_get_contents($users_file);
    $users = json_decode($users_data, true);
} else {
    echo json_encode(["status" => "error", "message" => "User data not found."]);
    exit;
}

// Check if username exists and validate the password
foreach ($users as $user) {
    if ($user['username'] === $username && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id']; // Store user_id in the session
        echo json_encode(["status" => "success", "message" => "Login successful."]);
        exit; // End the script here, no redirect necessary
    }
}

// If no match is found
echo json_encode(["status" => "error", "message" => "Invalid username or password."]);
?>
