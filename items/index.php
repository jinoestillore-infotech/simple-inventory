<?php
include '../database/config.php';

$limit = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$page = max(1, $page);
$offset = ($page - 1) * $limit;

$total_rows = $conn->query("SELECT COUNT(*) AS total FROM items")->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

$sql = "
    SELECT items.id, items.name, items.quantity, items.price,
           categories.name AS category_name,
           suppliers.name AS supplier_name
    FROM items
    JOIN categories ON items.category_id = categories.id
    JOIN suppliers ON items.supplier_id = suppliers.id
    ORDER BY items.id DESC
    LIMIT $limit OFFSET $offset
";
$result = $conn->query($sql);
$counter = $offset + 1; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Items List</title>
<link rel="stylesheet" href="../assets/bootstrap/bootstrap.min.css">
<link rel="stylesheet" href="../assets/bootstrap/icons/bootstrap-icon/bootstrap-icons.css">
<style>
body { 
    background-color: #f8f9fa; 
}

.page-card { 
    border-radius: 12px; 
}

.table thead th { 
    font-size: 0.85rem; 
    text-transform: uppercase; 
    letter-spacing: .05em; 
    color: #6c757d; 
    border-bottom: none; 
}

.table tbody tr:hover { 
    background-color: #f8f9fa; 
}

.action-btn { 
    width: 34px; 
    height: 34px; 
    display: inline-flex; 
    align-items: center; 
    justify-content: center; 
    border-radius: 50%; 
}

.qty-badge { 
    font-size: 0.8rem; 
    }
</style>
</head>
<body>
<div class="container py-5">

    <div class="mb-3">
        <a href="../dashboard/index.php" class="text-decoration-none text-muted">← Back to dashboard</a>
    </div>

    <div class="card shadow-sm page-card">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-0">Items</h3>
                    <small class="text-muted">Manage inventory items</small>
                </div>
                <a href="add.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Add Item
                </a>
            </div>

            <div class="table-responsive">
                <div class="mb-3 mt-1 me-1">
                    <input type="text" id="searchInput" class="form-control w-50 ms-auto" placeholder="Search items...">
                </div>

                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th class="text-center">Qty</th>
                            <th>Price</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="text-muted"><?php echo $counter++; ?></td>
                                <td class="fw-semibold"><?php echo htmlspecialchars($row['name']); ?></td>
                                <td class="text-muted"><?php echo htmlspecialchars($row['category_name']); ?></td>
                                <td class="text-muted"><?php echo htmlspecialchars($row['supplier_name']); ?></td>
                                <td class="text-center">
                                    <?php $qtyClass = $row['quantity'] <= 5 ? 'bg-danger' : 'bg-success'; ?>
                                    <span class="badge <?php echo $qtyClass; ?> qty-badge"><?php echo $row['quantity']; ?></span>
                                </td>
                                <td>₱<?php echo number_format($row['price'], 2); ?></td>
                                <td class="text-end">
                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-warning action-btn" title="Edit Item">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger action-btn" title="Delete Item" onclick="return confirm('Delete this item?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                    <a href="show.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-info action-btn" title="Show Items">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-box-seam fs-1 text-muted"></i>
                                    <p class="mt-3 text-muted">No items found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <script>
                    const searchInput = document.getElementById('searchInput');
                    const tableBody = document.querySelector('table tbody');

                    searchInput.addEventListener('input', function() {
                        const query = this.value;

                        fetch(`search_items.php?q=${encodeURIComponent(query)}`)
                            .then(response => response.text())
                            .then(data => {
                                tableBody.innerHTML = data;
                            });
                    });
                    </script>

            </div>

            <?php if($total_pages > 1): ?>
            <nav class="mt-3">
                <ul class="pagination justify-content-center mb-0">
                    <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo $page-1; ?>">Previous</a>
                    </li>
                    <?php for($p=1; $p<=$total_pages; $p++): ?>
                        <li class="page-item <?php if($p==$page) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $p; ?>"><?php echo $p; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php if($page >= $total_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo $page+1; ?>">Next</a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>

        </div>
    </div>

</div>
<script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>
