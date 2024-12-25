<?php
// Path to the users.json file
define('USER_FILE', 'data/users.json');

// Function to get users from the JSON file
function getUsersFromJson() {
    // Read the contents of the users.json file
    if (file_exists(USER_FILE)) {
        $jsonData = file_get_contents(USER_FILE);
        $users = json_decode($jsonData, true);
        return $users;
    } else {
        // Return an empty array if the file does not exist
        return [];
    }
}
?>
