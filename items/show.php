<?php
include '../database/config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("
    SELECT items.name, items.quantity, items.price, 
           categories.name AS category_name, 
           suppliers.name AS supplier_name
    FROM items
    JOIN categories ON items.category_id = categories.id
    JOIN suppliers ON items.supplier_id = suppliers.id
    WHERE items.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$item = $result->fetch_assoc();
$stmt->close();

$unitsPerPage = 10;
$totalUnits = $item['quantity'];
$totalPages = ceil($totalUnits / $unitsPerPage);
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$page = max(1, min($page, $totalPages));
$startUnit = ($page - 1) * $unitsPerPage + 1;
$endUnit = min($page * $unitsPerPage, $totalUnits);

$asset_stmt = $conn->prepare("
    SELECT asset_code 
    FROM item_assets 
    WHERE item_id = ? 
    ORDER BY id ASC 
    LIMIT ?, ?
");
$offsetUnits = $startUnit - 1;
$limitUnits = $endUnit - $offsetUnits;
$asset_stmt->bind_param("iii", $id, $offsetUnits, $limitUnits);
$asset_stmt->execute();
$asset_result = $asset_stmt->get_result();
$asset_codes = [];
while($row = $asset_result->fetch_assoc()) {
    $asset_codes[] = $row['asset_code'];
}
$asset_stmt->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($item['name']); ?> - Units</title>
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

.badge-unit {
    font-size: 0.8rem;
    background-color: #0d6efd;
    color: #fff;
}

.back-link {
    text-decoration: none;
    color: #6c757d;
}

.back-link:hover {
    color: #0d6efd;
}

.action-btn {
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}
</style>
</head>
<body>
<div class="container py-5">

    <div class="mb-3">
        <a href="index.php" class="back-link">
            &larr; Back to items list
        </a>
    </div>

    <div class="card shadow-sm page-card mb-4">
        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            
            <div class="d-flex flex-column">
                <h3 class="fw-bold mb-2"><?php echo htmlspecialchars($item['name']); ?></h3>
                <div class="text-muted d-flex flex-wrap gap-3">
                    <span>
                        <i class="bi bi-tags me-1"></i>
                        <?php echo htmlspecialchars($item['category_name']); ?>
                    </span>
                    <span>
                        <i class="bi bi-truck me-1"></i>
                        <?php echo htmlspecialchars($item['supplier_name']); ?>
                    </span>
                    <span>
                        <i class="bi bi-box-seam me-1"></i>
                        Quantity: 
                        <span class="badge bg-success"><?php echo $item['quantity']; ?></span>
                    </span>
                </div>
            </div>

            <div class="mt-3 mt-md-0">
                <a href="edit.php?id=<?php echo $id; ?>" class="btn btn-lg btn-outline-warning me-2 action-btn" title="Update this item.">
                    <i class="bi bi-pencil m-0"></i>
                </a>
                <a href="delete.php?id=<?php echo $id; ?>" class="btn btn-lg btn-outline-danger action-btn" onclick="return confirm('Delete this item?');" title="Delete this item.">
                    <i class="bi bi-trash m-0"></i>
                </a>
            </div>

        </div>
    </div>

    <div class="card shadow-sm page-card">
        <div class="card-body">
            <h5 class="mb-3">Units List</h5>
            <div class="table-responsive">
                <div class="m-1">
                    <input type="text" id="assetSearch" class="form-control ms-auto w-50" placeholder="Search units or asset codes...">
                </div>

                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Asset Code</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody id="unitsTableBody">
                        <?php if ($totalUnits > 0): ?>
                            <?php for ($i = $startUnit; $i <= $endUnit; $i++): ?>
                                <?php
                                    $existingCode = isset($asset_codes[$i - $startUnit]) ? $asset_codes[$i - $startUnit] : '';
                                ?>
                            <tr>
                                <td><span class="text-dark"><?php echo $i; ?></span></td>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td>₱<?php echo number_format($item['price'], 2); ?></td>
                                <td><?php echo htmlspecialchars($existingCode); ?></td>
                                <td class="text-end">
                                    <a href="assign_asset.php?item_id=<?php echo $id; ?>&unit=<?php echo $i; ?>" 
                                    class="btn btn-sm btn-outline-primary">
                                        <?php echo $existingCode ? 'Update' : 'Assign'; ?>
                                    </a>
                                </td>
                            </tr>
                            <?php endfor; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">
                                    No units available
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <script>
                    const searchInput = document.getElementById('assetSearch');
                    const tableBody = document.getElementById('unitsTableBody');
                    const itemId = <?php echo $id; ?>;

                    searchInput.addEventListener('input', function() {
                        const query = this.value;

                        fetch(`search_units.php?item_id=${itemId}&query=${encodeURIComponent(query)}`)
                            .then(response => response.json())
                            .then(data => {
                                let html = '';

                                if (data.length > 0) {
                                    data.forEach((unit, index) => {
                                        html += `
                                            <tr>
                                                <td>${index + 1}</td>
                                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                                <td>₱<?php echo number_format($item['price'], 2); ?></td>
                                                <td>${unit.asset_code}</td>
                                                <td class="text-end">
                                                    <a href="assign_asset.php?item_id=${itemId}&unit=${unit.id}" 
                                                    class="btn btn-sm btn-outline-primary">
                                                        Update
                                                    </a>
                                                </td>
                                            </tr>
                                        `;
                                    });
                                } else {
                                    html = '<tr><td colspan="5" class="text-center text-muted py-3">No units found</td></tr>';
                                }

                                tableBody.innerHTML = html;
                            })
                            .catch(err => console.error(err));
                    });

                    const pagination = document.querySelector('.pagination');

                        searchInput.addEventListener('input', function() {
                            if (this.value.trim() !== '') {
                                pagination.style.display = 'none';
                            } else {
                                pagination.style.display = '';
                            }
                        });

                </script>
            </div>

            <?php if ($totalPages > 1): ?>
            <nav class="mt-3">
                <ul class="pagination justify-content-center mb-0">

                    <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
                        <a class="page-link" href="?id=<?php echo $id; ?>&page=<?php echo $page-1; ?>">Previous</a>
                    </li>

                    <?php for($p = 1; $p <= $totalPages; $p++): ?>
                    <li class="page-item <?php if($p == $page) echo 'active'; ?>">
                        <a class="page-link" href="?id=<?php echo $id; ?>&page=<?php echo $p; ?>"><?php echo $p; ?></a>
                    </li>
                    <?php endfor; ?>

                    <li class="page-item <?php if($page >= $totalPages) echo 'disabled'; ?>">
                        <a class="page-link" href="?id=<?php echo $id; ?>&page=<?php echo $page+1; ?>">Next</a>
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
