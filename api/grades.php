<?php
include '../db.php';

if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];

    $stmt = $pdo->prepare("SELECT subject, grade, semester FROM grades WHERE student_id = ?");
    $stmt->execute([$student_id]);

    $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($grades);
} else {
    echo json_encode(['error' => 'Student ID not provided']);
}
?>
