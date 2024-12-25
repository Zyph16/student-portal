<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="profile-body">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Student Portal</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="api/clearance.php">Clearance</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content (Profile Section) -->
    <div class="main-content">
        <header>
            <h1>Profile</h1>
        </header>

        <!-- Success message after update -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Profile Data Display -->
        <section>
            <!-- Profile Image (Circle container if no image uploaded) -->
            <div class="profile-image-upload">
                <label for="profile_image" class="profile-image-label">
                    <div class="profile-image-container">
                        <?php if (!empty($student['profile_image']) && file_exists($student['profile_image'])): ?>
                            <img src="<?php echo htmlspecialchars($student['profile_image']); ?>" alt="Profile Image" class="profile-image">
                        <?php else: ?>
                            <div class="no-image-placeholder">No Image</div>
                        <?php endif; ?>
                    </div>
                    <input type="file" id="profile_image" name="profile_image" accept="image/*" style="display: none;">
                    <p>Click to Change Profile Picture</p>
                </label>
            </div>

            <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name'] ?? ''); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email'] ?? ''); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($student['phone'] ?? ''); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($student['address'] ?? ''); ?></p>

            <!-- Button to edit profile -->
            <button onclick="document.getElementById('editForm').style.display='block'">Edit Profile</button>
        </section>

        <!-- Profile Edit Form -->
        <section id="editForm" style="display:none;">
            <h2>Edit Profile</h2>
            <form method="POST" action="api/profile.php" enctype="multipart/form-data" class="edit-profile-form">
                <!-- Hidden field for current profile image -->
                <input type="hidden" name="current_profile_image" value="<?php echo htmlspecialchars($student['profile_image']); ?>">

                <!-- Profile Image Section -->
                <div class="profile-image-upload">
                    <label for="profile_image" class="profile-image-label">
                        <div class="profile-image-container">
                            <?php if (!empty($student['profile_image']) && file_exists($student['profile_image'])): ?>
                                <img src="<?php echo htmlspecialchars($student['profile_image']); ?>" alt="Profile Image" class="profile-image">
                            <?php else: ?>
                                <div class="no-image-placeholder">No Image</div>
                            <?php endif; ?>
                        </div>
                        <input type="file" id="profile_image" name="profile_image" accept="image/*">
                        <p>Click to Change Profile Picture</p>
                    </label>
                </div>

                <!-- Full Name -->
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($student['name'] ?? ''); ?>" required>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['email'] ?? ''); ?>" required>
                </div>

                <!-- Phone Number -->
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($student['phone'] ?? ''); ?>">
                </div>

                <!-- Address -->
                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address"><?php echo htmlspecialchars($student['address'] ?? ''); ?></textarea>
                </div>

                <!-- Update Button -->
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
            <button onclick="document.getElementById('editForm').style.display='none'">Cancel</button>
        </section>
    </div>

    <script src="script.js"></script>
</body>
</html>
