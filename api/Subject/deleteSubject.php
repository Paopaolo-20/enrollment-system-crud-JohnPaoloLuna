<?php
require_once "../config.php";
$data = json_decode(file_get_contents("php://input"), true);
$subject_id = isset($data['subject_id']) ? (int)$data['subject_id'] : 0;

if (!$subject_id) {
    echo json_encode(["success" => false, "message" => "Missing subject id"]);
    exit;
}

// Check enrollments
$stmt = $pdo->prepare("SELECT COUNT(*) FROM student_load WHERE subject_id = ?");
$stmt->execute([$subject_id]);
if ((int)$stmt->fetchColumn() > 0) {
    echo json_encode(["success" => false, "message" => "Cannot delete subject - students enrolled"]);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM subject_tbl WHERE subject_id = ?");
try {
    $stmt->execute([$subject_id]);
    echo json_encode(["success" => true, "message" => "Subject deleted"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}