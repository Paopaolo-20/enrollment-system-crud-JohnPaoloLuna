<?php
require '../config.php';

$stud_id = $_POST["stud_id"] ?? null;
$name = $_POST["name"] ?? null;
$program_id = $_POST["program_id"] ?? null;
$allowance = $_POST["allowance"] ?? null;

if (!$stud_id || !$name || !$program_id || $allowance === null) {
    echo json_encode(["success" => false, "message" => "Missing required fields"]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE student_tbl SET name = ?, program_id = ?, allowance = ? WHERE stud_id = ?");
    $stmt->execute([$name, $program_id, $allowance, $stud_id]);

    echo json_encode(["success" => true, "message" => "Student updated successfully"]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
