<?php
header('Content-Type: application/json');
require '../config.php';

try {
    $stmt = $pdo->query("SELECT program_id, program_name FROM program_tbl ORDER BY program_id ASC");
    $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "data" => $programs
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>
