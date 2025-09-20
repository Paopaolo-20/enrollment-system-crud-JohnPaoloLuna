<?php
require_once("../config.php"); // adjust path

header("Content-Type: application/json");

$student_id = $_POST['student_id'] ?? null;
$subject_id = $_POST['subject_id'] ?? null;

if (!$student_id || !$subject_id) {
    echo json_encode(["success" => false, "message" => "Missing fields"]);
    exit;
}

try {
    // prevent duplicate enrollment
    $check = $pdo->prepare("SELECT 1 FROM enrollments WHERE student_id=? AND subject_id=?");
    $check->execute([$student_id, $subject_id]);
    if ($check->fetch()) {
        echo json_encode(["success" => false, "message" => "Student already enrolled in this subject"]);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO enrollments (student_id, subject_id) VALUES (?, ?)");
    $stmt->execute([$student_id, $subject_id]);

    echo json_encode(["success" => true, "message" => "Enrollment saved"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
