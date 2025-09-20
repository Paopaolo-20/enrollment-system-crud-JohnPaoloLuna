<?php
require '../config.php';

try {
    $sql = "SELECT s.stud_id, s.name, s.program_id, s.allowance, p.program_name
            FROM student_tbl s
            LEFT JOIN program_tbl p ON s.program_id = p.program_id
            ORDER BY s.stud_id ASC";
    $stmt = $pdo->query($sql);
    $students = $stmt->fetchAll();

    echo json_encode(["success" => true, "data" => $students]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
