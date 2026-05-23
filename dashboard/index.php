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
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
        <h1 class="mb-2 mb-md-0 fw-bold">Inventory Management</h1>

        <a class="text-decoration-none fs-6 fs-md-5" href="history.php">
            <i class="bi bi-clock-history me-2"></i>Show History
        </a>
    </div>
    <?php include 'cards.php' ?>

    <p class="mt-5 fw-bold fs-4">Inventory Value by Category</p>
    <div class="row g-4 mb-5">

        <?php while ($cat = $categories->fetch_assoc()): ?>
            <div class="col-12 col-md-3">
                <div class="card dashboard-card text-center shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-tags text-warning mb-3 fs-1"></i>
                        <h5 class="card-title"><?= htmlspecialchars($cat['category_name']) ?></h5>
                        <p class="fs-4 fw-bold mb-0">
                            ₱<?= number_format($cat['total_price'], 2) ?>
                        </p>
                        <small class="text-muted">Total item value</small>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>

    </div>

</div>

<script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>
