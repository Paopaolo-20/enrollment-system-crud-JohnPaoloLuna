<?php
require_once("../config.php");

header("Content-Type: application/json");

$enrollment_id = $_GET['id'] ?? null;

if (!$enrollment_id) {
    echo json_encode(["success" => false, "message" => "Missing enrollment ID"]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM enrollments WHERE enrollment_id=?");
    $stmt->execute([$enrollment_id]);

    echo json_encode(["success" => true, "message" => "Enrollment removed"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
