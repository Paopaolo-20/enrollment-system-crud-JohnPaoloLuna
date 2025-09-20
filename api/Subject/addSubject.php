<?php
require_once "config.php";
$data = json_decode(file_get_contents("php://input"), true);
$subject_name = trim($data['subject_name'] ?? '');
$sem_id = isset($data['sem_id']) ? (int)$data['sem_id'] : 0;

if ($subject_name === '' || !$sem_id) {
    echo json_encode(["success" => false, "message" => "Missing fields"]);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO subject_tbl (subject_name, sem_id) VALUES (?, ?)");
try {
    $stmt->execute([$subject_name, $sem_id]);
    echo json_encode(["success" => true, "message" => "Subject added", "data" => ["subject_id" => $pdo->lastInsertId()]]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}