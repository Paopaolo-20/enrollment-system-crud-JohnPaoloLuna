<?php
require_once "../config.php";
$stmt = $pdo->prepare("SELECT sem_id, sem_name, year_id FROM semester_tbl ORDER BY sem_id ASC");
$stmt->execute();
echo json_encode(["success" => true, "data" => $stmt->fetchAll()]);