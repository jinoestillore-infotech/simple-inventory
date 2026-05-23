<?php
include '../database/config.php';

$search = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';
$limit = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$where = '';
if (!empty($search)) {
    $where = "WHERE items.name LIKE '%$search%' 
              OR categories.name LIKE '%$search%' 
              OR suppliers.name LIKE '%$search%'";
}

$total_rows = $conn->query("
    SELECT COUNT(*) AS total 
    FROM items
    JOIN categories ON items.category_id = categories.id
    JOIN suppliers ON items.supplier_id = suppliers.id
    $where
")->fetch_assoc()['total'];

$total_pages = ceil($total_rows / $limit);

$sql = "
    SELECT items.id, items.name, items.quantity, items.price,
           categories.name AS category_name,
           suppliers.name AS supplier_name
    FROM items
    JOIN categories ON items.category_id = categories.id
    JOIN suppliers ON items.supplier_id = suppliers.id
    $where
    ORDER BY items.id DESC
    LIMIT $limit OFFSET $offset
";
$result = $conn->query($sql);
$counter = $offset + 1;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                <td class="text-muted">'.($counter++).'</td>
                <td class="fw-semibold">'.htmlspecialchars($row['name']).'</td>
                <td class="text-muted">'.htmlspecialchars($row['category_name']).'</td>
                <td class="text-muted">'.htmlspecialchars($row['supplier_name']).'</td>
                <td class="text-center">
                    <span class="badge '.($row['quantity'] <= 5 ? 'bg-danger' : 'bg-success').' qty-badge">'.$row['quantity'].'</span>
                </td>
                <td>₱'.number_format($row['price'], 2).'</td>
                <td class="text-end">
                    <a href="edit.php?id='.$row['id'].'" class="btn btn-sm btn-outline-warning action-btn" title="Edit Item"><i class="bi bi-pencil"></i></a>
                    <a href="delete.php?id='.$row['id'].'" class="btn btn-sm btn-outline-danger action-btn" title="Delete Item" onclick="return confirm(\'Delete this item?\');"><i class="bi bi-trash"></i></a>
                    <a href="show.php?id='.$row['id'].'" class="btn btn-sm btn-outline-info action-btn" title="Show Items"><i class="bi bi-eye"></i></a>
                </td>
            </tr>';
    }
} else {
    echo '<tr>
            <td colspan="7" class="text-center py-5">
                <i class="bi bi-box-seam fs-1 text-muted"></i>
                <p class="mt-3 text-muted">No items found</p>
            </td>
        </tr>';
}
?>
