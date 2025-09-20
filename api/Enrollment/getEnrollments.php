<?php
require_once("../config.php"); // adjust path to your config

header("Content-Type: application/json");

try {
    $sql = "SELECT 
                e.enrollment_id,
                e.student_id,
                e.subject_id,
                s.name AS student_name,
                sub.subject_name
            FROM enrollments e
            LEFT JOIN student_tbl s ON e.student_id = s.stud_id
            LEFT JOIN subject_tbl sub ON e.subject_id = sub.subject_id
            ORDER BY e.enrollment_id DESC";

    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "data" => $rows
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
