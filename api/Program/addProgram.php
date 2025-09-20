<?php
require '../config.php';

// Get POST data (FormData from frontend)
$name = $_POST['program_name'] ?? '';

if (trim($name) === '') {
    echo json_encode(["success" => false, "message" => "Program name is required"]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO program_tbl (program_name) VALUES (?)");
    $stmt->execute([$name]);

    echo json_encode([
        "success" => true,
        "message" => "Program added successfully",
        "data" => ["program_id" => $pdo->lastInsertId()]
    ]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
