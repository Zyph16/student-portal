<?php
header('Content-Type: application/json');

// Read POST data
$data = json_decode(file_get_contents("php://input"), true);

$first_name = $data['first_name'];
$last_name = $data['last_name'];
$email = $data['email'];
$username = $data['username'];
$password = $data['password'];

// Validate inputs
if (empty($first_name) || empty($last_name) || empty($email) || empty($username) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "All fields are required!"]);
    exit;
}

// Define the path to the users.json file
$file_path = 'data/users.json';

// Read the existing users data from the file
if (file_exists($file_path)) {
    $json_data = file_get_contents($file_path);
    $users = json_decode($json_data, true);
} else {
    $users = [];
}

// Check if username already exists
foreach ($users as $user) {
    if ($user['username'] === $username) {
        echo json_encode(["status" => "error", "message" => "Username already exists!"]);
        exit;
    }
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Create the new user data array with a generated ID
$new_user = [
    'id' => count($users) + 1, // Generate a unique ID based on the current user count
    'first_name' => $first_name,
    'last_name' => $last_name,
    'email' => $email,
    'username' => $username,
    'password' => $hashed_password,
];

// Add the new user to the array
$users[] = $new_user;

// Save the updated users data to the JSON file
if (file_put_contents($file_path, json_encode($users, JSON_PRETTY_PRINT))) {
    echo json_encode(["status" => "success", "message" => "Registration successful!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error occurred during registration."]);
}
?>
