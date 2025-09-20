<?php
require '../config.php';

$program_id = $_POST['program_id'] ?? null;
$name = $_POST['program_name'] ?? '';

if (!$program_id || trim($name) === '') {
    echo json_encode(["success" => false, "message" => "Missing fields"]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE program_tbl SET program_name = ? WHERE program_id = ?");
    $stmt->execute([$name, $program_id]);

    echo json_encode(["success" => true, "message" => "Program updated successfully"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
