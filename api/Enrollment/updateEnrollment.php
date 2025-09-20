<?php
require_once("../config.php");

header("Content-Type: application/json");

$enrollment_id = $_POST['enrollment_id'] ?? null;
$student_id    = $_POST['student_id'] ?? null;
$subject_id    = $_POST['subject_id'] ?? null;

if (!$enrollment_id || !$student_id || !$subject_id) {
    echo json_encode(["success" => false, "message" => "Missing fields"]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE enrollments 
                           SET student_id=?, subject_id=? 
                           WHERE enrollment_id=?");
    $stmt->execute([$student_id, $subject_id, $enrollment_id]);

    echo json_encode(["success" => true, "message" => "Enrollment updated"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
