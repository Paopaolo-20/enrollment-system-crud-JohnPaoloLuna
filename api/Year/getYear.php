<?php
header('Content-Type: application/json');
require '../config.php';

try {
    $stmt = $pdo->query("SELECT year_id, year_from, year_to FROM year_tbl ORDER BY year_id ASC");
    $years = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["success" => true, "data" => $years]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
