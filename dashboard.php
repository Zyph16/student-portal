<?php
session_start(); // Start the session

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}

// Path to the grades JSON file and students JSON file
$grades_file = 'data/grades.json';
$students_file = 'data/students.json';

// Fetch the user's ID from the session
$user_id = $_SESSION['user_id'];

// Read the grades data from the JSON file
$grades = [];
if (file_exists($grades_file)) {
    $grades_data = file_get_contents($grades_file);
    $grades = json_decode($grades_data, true); // Decode the JSON data
}

// Determine default values for year and semester
$selected_year = isset($_GET['year_level']) ? $_GET['year_level'] : null;
$selected_semester = isset($_GET['semester']) ? $_GET['semester'] : null;

// Use the first matching grade entry to set defaults if no GET parameters are provided
if (!$selected_year || !$selected_semester) {
    foreach ($grades as $grade) {
        if ($grade['id'] == $user_id) {
            $selected_year = $selected_year ?? $grade['year_level'];
            $selected_semester = $selected_semester ?? $grade['semester'];
            break;
        }
    }
}

// Filter grades for the logged-in user
$filtered_grades = array_filter($grades, function ($grade) use ($user_id, $selected_year, $selected_semester) {
    return $grade['id'] == $user_id &&
           $grade['year_level'] == $selected_year &&
           $grade['semester'] == $selected_semester;
});

// Debugging output to verify filtering
if (empty($filtered_grades)) {
    error_log("No grades found for User ID: $user_id, Year: $selected_year, Semester: $selected_semester");
}

// Read students data to get the user's details
$students = [];
if (file_exists($students_file)) {
    $students_data = file_get_contents($students_file);
    $students = json_decode($students_data, true); // Decode the JSON data
}

// Find the student details
$student = array_filter($students, fn($entry) => $entry['id'] == $user_id);
$student = reset($student); // Get the first matching student entry
$first_name = $student['first_name'] ?? "Unknown";
$middle_name = $student['middle_name'] ?? "Unknown";
$last_name = $student['last_name'] ?? "Unknown";

// Get distinct year levels and semesters from the grades data
$year_levels = array_unique(array_column($grades, 'year_level'));
$semesters = array_unique(array_column($grades, 'semester'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="dashboard-body">
    <div class="sidebar">
        <h2>Student Portal</h2>
        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="api/clearance.php">Clearance</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <header>
            <h1>Welcome, <?php echo htmlspecialchars("$first_name $middle_name $last_name"); ?>!</h1>
        </header>
        
        <section>
            <h2>Your Grades</h2>
            <form method="get" action="dashboard.php">
                <label for="year_level">Year Level:</label>
                <select name="year_level" id="year_level" onchange="this.form.submit()">
                    <?php foreach ($year_levels as $year): ?>
                        <option value="<?php echo htmlspecialchars($year); ?>" <?php echo $year == $selected_year ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($year); ?> Year
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="semester">Semester:</label>
                <select name="semester" id="semester" onchange="this.form.submit()">
                    <?php foreach ($semesters as $semester): ?>
                        <option value="<?php echo htmlspecialchars($semester); ?>" <?php echo $semester == $selected_semester ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars(ucfirst($semester)); ?> Semester
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>


            <table id="grades-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Grade</th>
                        <th>Semester</th>
                        <th>Year Level</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($filtered_grades)): ?>
                        <?php foreach ($filtered_grades as $grade): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($grade['subject']); ?></td>
                                <td><?php echo htmlspecialchars($grade['grade']); ?></td>
                                <td><?php echo htmlspecialchars($grade['semester']); ?></td>
                                <td><?php echo htmlspecialchars($grade['year_level']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="no-data-message">No grades available for the selected year and semester.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
    <script src="script.js"></script>
</body>
</html>
