<?php
require_once "../config.php";
$data = json_decode(file_get_contents("php://input"), true);
$sem_id = isset($data['sem_id']) ? (int)$data['sem_id'] : 0;

if (!$sem_id) {
    echo json_encode(["success" => false, "message" => "Missing sem id"]);
    exit;
}

// Check subjects under semester
$stmt = $pdo->prepare("SELECT COUNT(*) FROM subject_tbl WHERE sem_id = ?");
$stmt->execute([$sem_id]);
if ((int)$stmt->fetchColumn() > 0) {
    echo json_encode(["success" => false, "message" => "Cannot delete semester - subjects exist"]);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM semester_tbl WHERE sem_id = ?");
try {
    $stmt->execute([$sem_id]);
    echo json_encode(["success" => true, "message" => "Semester deleted"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}