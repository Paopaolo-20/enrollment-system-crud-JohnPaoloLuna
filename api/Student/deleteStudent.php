<?php
header('Content-Type: application/json');
require '../config.php';

// Accept ID from GET, POST, or JSON
$stud_id = $_GET['id'] ?? ($_POST['stud_id'] ?? null);

if (!$stud_id) {
    $data = json_decode(file_get_contents("php://input"), true);
    $stud_id = $data['stud_id'] ?? null;
}

if (!$stud_id) {
    echo json_encode(["success" => false, "message" => "Missing student ID"]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM student_tbl WHERE stud_id = ?");
    $stmt->execute([$stud_id]);

    echo json_encode(["success" => true, "message" => "Student deleted successfully"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
