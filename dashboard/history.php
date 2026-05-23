<?php
include '../database/config.php';

$item_total = $conn->query("SELECT COUNT(*) as total FROM items")->fetch_assoc()['total'];
$supplier_total = $conn->query("SELECT COUNT(*) as total FROM suppliers")->fetch_assoc()['total'];
$category_total = $conn->query("SELECT COUNT(*) as total FROM categories")->fetch_assoc()['total'];

$categoryTotalsSQL = "
    SELECT 
        categories.id,
        categories.name AS category_name,
        COALESCE(SUM(items.price), 0) AS total_price
    FROM categories
    LEFT JOIN items ON items.category_id = categories.id
    GROUP BY categories.id
";

$categories = $conn->query($categoryTotalsSQL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inventory Dashboard</title>
<link rel="stylesheet" href="../assets/bootstrap/bootstrap.min.css">
<link rel="stylesheet" href="../assets/bootstrap/icons/bootstrap-icon/bootstrap-icons.css">
<style>
    body {
        background-color: #f8f9fa;
    }

    .dashboard-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        background-color: #badafa;
    }
    
    .dashboard-card .card-body i {
        font-size: 2.5rem;
    }

    .activity-card {
        border-left: 4px solid #0d6efd;
        transition: background-color 0.2s ease, transform 0.2s ease;
    }

    .activity-card:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
    }

    .activity-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: #fff;
    }

    .activity-time {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .categoryChart {
        position: relative;
        height: 400px;
        width: 300px;
    }
</style>
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h1 class="mb-3 fw-bold">History</h1>
        <a href="../dashboard/index.php" class="text-decoration-none text-muted">← Back to dashboard</a>
    </div>

    <?php include 'recent_activity.php' ?>

</div>

<script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>
