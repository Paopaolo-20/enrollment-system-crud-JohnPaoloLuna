<?php
header('Content-Type: application/json');
require '../config.php';

$year_id   = $_POST['year_id'] ?? null;
$year_from = $_POST['year_from'] ?? null;
$year_to   = $_POST['year_to'] ?? null;

if (!$year_id || !$year_from || !$year_to) {
    echo json_encode(["success" => false, "message" => "Missing fields"]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE year_tbl SET year_from = ?, year_to = ? WHERE year_id = ?");
    $stmt->execute([$year_from, $year_to, $year_id]);

    echo json_encode(["success" => true, "message" => "Year updated successfully"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
