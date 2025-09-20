<?php
require_once "../config.php";
$sql = "SELECT sub.subject_id, sub.subject_name, sub.sem_id, sem.sem_name
        FROM subject_tbl sub
        LEFT JOIN semester_tbl sem ON sub.sem_id = sem.sem_id
        ORDER BY sub.subject_id ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
echo json_encode(["success" => true, "data" => $stmt->fetchAll()]);