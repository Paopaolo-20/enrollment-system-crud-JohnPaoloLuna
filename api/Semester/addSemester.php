<?php
require_once "../config.php";

header("Content-Type: application/json");

// Read POST body
$data = json_decode(file_get_contents("php://input"), true);

$sem_name = trim($data['sem_name'] ?? '');
$year_id = isset($data['year_id']) ? (int)$data['year_id'] : 0;

if ($sem_name === '' || !$year_id) {
    echo json_encode([
        "success" => false,
        "message" => "Missing fields: semester name and year are required"
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO semester_tbl (sem_name, year_id) VALUES (?, ?)");
    $stmt->execute([$sem_name, $year_id]);

    echo json_encode([
        "success" => true,
        "message" => "Semester added",
        "data" => [
            "sem_id" => $pdo->lastInsertId(),
            "sem_name" => $sem_name,
            "year_id" => $year_id
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
