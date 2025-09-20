<?php
require_once "../config.php";
$data = json_decode(file_get_contents("php://input"), true);
$subject_id = isset($data['subject_id']) ? (int)$data['subject_id'] : 0;
$subject_name = trim($data['subject_name'] ?? '');
$sem_id = isset($data['sem_id']) ? (int)$data['sem_id'] : 0;

if (!$subject_id || $subject_name === '' || !$sem_id) {
    echo json_encode(["success" => false, "message" => "Missing fields"]);
    exit;
}

$stmt = $pdo->prepare("UPDATE subject_tbl SET subject_name = ?, sem_id = ? WHERE subject_id = ?");
try {
    $stmt->execute([$subject_name, $sem_id, $subject_id]);
    echo json_encode(["success" => true, "message" => "Subject updated"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}