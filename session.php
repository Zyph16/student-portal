<?php
session_start();

// Define the path to the users.json file
$users_file = 'path_to_your_users_folder/users.json';

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.html");
    exit;
}

// Check if the users.json file exists
if (file_exists($users_file)) {
    // Load the users data from the JSON file
    $users_data = json_decode(file_get_contents($users_file), true);

    // If the user_id is set in the session, find the matching user from the JSON file
    $user_found = false;
    foreach ($users_data as $user) {
        if ($user['id'] == $_SESSION['id']) {
            // User found, session is valid
            $user_found = true;
            break;
        }
    }

    if (!$user_found) {
        // If no matching user is found in the users.json file, redirect to login
        header("Location: index.html");
        exit;
    }
} else {
    // If the users.json file is not found, redirect to login
    header("Location: index.html");
    exit;
}
?>
