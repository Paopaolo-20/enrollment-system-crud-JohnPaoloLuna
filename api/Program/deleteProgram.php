<?php
header('Content-Type: application/json');
require '../config.php';

// Accept id from GET (?id=3), POST (program_id), or JSON body
$program_id = $_GET['id'] ?? ($_POST['program_id'] ?? null);

if (!$program_id) {
    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);
    $program_id = $data['program_id'] ?? null;
}

if (!$program_id) {
    echo json_encode(["success" => false, "message" => "Missing program ID"]);
    exit;
}

$program_id = (int)$program_id;

try {
    // Prevent deleting a program with students enrolled
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM student_tbl WHERE program_id = ?");
    $stmt->execute([$program_id]);
    $count = (int)$stmt->fetchColumn();

    if ($count > 0) {
        echo json_encode(["success" => false, "message" => "Cannot delete program - students are enrolled"]);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM program_tbl WHERE program_id = ?");
    $stmt->execute([$program_id]);

    echo json_encode(["success" => true, "message" => "Program deleted successfully"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
