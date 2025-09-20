<?php
require_once "../config.php";
$data = json_decode(file_get_contents("php://input"), true);
$sem_id = isset($data['sem_id']) ? (int)$data['sem_id'] : 0;
$sem_name = trim($data['sem_name'] ?? '');
$year_id = isset($data['year_id']) ? (int)$data['year_id'] : 0;

if (!$sem_id || $sem_name === '' || !$year_id) {
    echo json_encode(["success" => false, "message" => "Missing fields"]);
    exit;
}

$stmt = $pdo->prepare("UPDATE semester_tbl SET sem_name = ?, year_id = ? WHERE sem_id = ?");
try {
    $stmt->execute([$sem_name, $year_id, $sem_id]);
    echo json_encode(["success" => true, "message" => "Semester updated"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}