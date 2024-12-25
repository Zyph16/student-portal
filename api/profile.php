<?php
session_start();


if (!isset($_SESSION['student_id'])) {
    $_SESSION['student_id'] = 1; 
}


$students_file = '../data/students.json';


if (!file_exists($students_file)) {
    die("Error: students.json file not found.");
}


$students_data = json_decode(file_get_contents($students_file), true);

if ($students_data === null) {
    die("Error: Failed to decode students.json. JSON Error: " . json_last_error_msg());
}


$student = null;
foreach ($students_data as $entry) {
    if ($entry['id'] == $_SESSION['student_id']) {
        $student = $entry;
        break;
    }
}


if ($student === null) {
    die("Error: No student found with ID " . $_SESSION['student_id']);
}

// Set profile image
$profile_image = isset($student['profile_image']) && !empty($student['profile_image']) 
    ? htmlspecialchars($student['profile_image']) 
    : 'default-image.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
</head>
<body>
    <h1>Profile</h1>
    <section>
        <h2>Your Profile</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['middle_name'] . ' ' . $student['last_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($student['phone']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($student['address']); ?></p>
        <p><strong>Profile Image:</strong></p>
        <img src="<?php echo $profile_image; ?>" alt="Profile Image" width="100">
    </section>

    <script src="../script.js">
</body>
</html>
