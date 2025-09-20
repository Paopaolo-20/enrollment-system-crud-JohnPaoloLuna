<?php
header('Content-Type: application/json');
require '../config.php';

$year_from = $_POST['year_from'] ?? null;
$year_to   = $_POST['year_to'] ?? null;

if (!$year_from || !$year_to) {
    echo json_encode(["success" => false, "message" => "Missing year fields"]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO year_tbl (year_from, year_to) VALUES (?, ?)");
    $stmt->execute([$year_from, $year_to]);

    echo json_encode([
        "success" => true,
        "message" => "Year added successfully",
        "data" => ["year_id" => $pdo->lastInsertId()]
    ]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
