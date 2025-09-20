<?php
header('Content-Type: application/json');
require '../config.php';

// Accept GET or POST
$year_id = $_GET['id'] ?? ($_POST['year_id'] ?? null);

if (!$year_id) {
    echo json_encode(["success" => false, "message" => "Missing year ID"]);
    exit;
}

// prevent deleting if semesters exist
$stmt = $pdo->prepare("SELECT COUNT(*) FROM semester_tbl WHERE year_id = ?");
$stmt->execute([$year_id]);
if ($stmt->fetchColumn() > 0) {
    echo json_encode(["success" => false, "message" => "Cannot delete year - semesters exist"]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM year_tbl WHERE year_id = ?");
    $stmt->execute([$year_id]);

    echo json_encode(["success" => true, "message" => "Year deleted successfully"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
