<?php
include '../database/config.php';

if (!isset($_GET['item_id'], $_GET['query'])) {
    echo json_encode([]);
    exit();
}

$item_id = intval($_GET['item_id']);
$query = trim($_GET['query']);

$stmt = $conn->prepare("
    SELECT id, asset_code 
    FROM item_assets 
    WHERE item_id = ? AND asset_code LIKE ?
    ORDER BY id ASC
");
$likeQuery = "%$query%";
$stmt->bind_param("is", $item_id, $likeQuery);
$stmt->execute();
$result = $stmt->get_result();

$units = [];
while($row = $result->fetch_assoc()) {
    $units[] = $row;
}
$stmt->close();

echo json_encode($units);
