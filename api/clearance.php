<?php
session_start();


if (!isset($_SESSION['user_id'])) {
 
    header("Location: login.php");
    exit;
}


$clearance_file = '../data/clearance.json';


$clearance = null;


if (file_exists($clearance_file)) {
    $clearance_data = json_decode(file_get_contents($clearance_file), true);

    foreach ($clearance_data as $entry) {
        if ($entry['id'] == $_SESSION['user_id']) {
            $clearance = $entry;
            break;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clearance</title>
    <link rel="stylesheet" href="../styles.css"> 
</head>
<body class="clearance-body">
    <div class="sidebar">
        <h2>Student Portal</h2>
        <ul>
            <li><a href="../dashboard.php">Dashboard</a></li>
            <li><a href="../profile.php">Profile</a></li>
            <li><a href="#">Clearance</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <header>
            <h1>Clearance Status</h1>
        </header>

        <section class="clearance-section">
    
            <p><strong>Status:</strong> 
                <?php
                    if ($clearance) {
                        echo ($clearance['is_cleared'] == 1) ? "Cleared" : "Not Cleared";
                    } else {
                        echo "No clearance data found";
                    }
                ?>
            </p>
            <p><strong>Clearance Date:</strong> 
                <?php 
                    if ($clearance && isset($clearance['clearance_date'])) {
                        echo htmlspecialchars($clearance['clearance_date']);
                    } else {
                        echo "Not Available"; 
                    }
                ?>
            </p>

            <?php if (!$clearance || $clearance['is_cleared'] == 0): ?>
                <p>Please complete all necessary requirements to be cleared.</p>
            <?php endif; ?>


            <h2>Department Clearance Status</h2>
            <table id="clearance-table">
                <thead>
                    <tr>
                        <th>Department</th>
                        <th>Clearance Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($clearance && !empty($clearance['departments'])) {
                        foreach ($clearance['departments'] as $department => $status) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($department) . "</td>";
                            echo "<td>" . ($status == 1 ? 'Signed' : 'Not Signed') . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No departments found or no clearance signatures.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <?php if (!$clearance || $clearance['is_cleared'] == 0): ?>
                <p class="no-clearance-message" style="color: red; margin-top: 20px;">Clearance unavailable</p>
            <?php endif; ?>
        </section>
    </div>

    <script src="script.js"></script> 
</body>
</html>
