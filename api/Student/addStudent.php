<?php
require '../config.php';

$name = $_POST["name"] ?? null;
$program_id = $_POST["program_id"] ?? null;
$allowance = $_POST["allowance"] ?? null;

if (!$name || !$program_id || $allowance === null) {
    echo json_encode(["success" => false, "message" => "Missing required fields"]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO student_tbl (name, program_id, allowance) VALUES (?, ?, ?)");
    $stmt->execute([$name, $program_id, $allowance]);

    echo json_encode([
        "success" => true,
        "message" => "Student added successfully",
        "data" => ["stud_id" => $pdo->lastInsertId()]
    ]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
