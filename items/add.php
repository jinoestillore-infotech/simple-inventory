<?php

include '../database/config.php';

$name = $category_id = $supplier_id = $quantity = $price = "";
$name_err = $category_err = $supplier_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter item name.";
    } else {
        $name = trim($_POST["name"]);
    }

    if (empty($_POST["category_id"])) {
        $category_err = "Please select a category.";
    } else {
        $category_id = $_POST["category_id"];
    }

    if (empty($_POST["supplier_id"])) {
        $supplier_err = "Please select a supplier.";
    } else {
        $supplier_id = $_POST["supplier_id"];
    }

    $quantity = !empty($_POST["quantity"]) ? intval($_POST["quantity"]) : 0;
    $price = !empty($_POST["price"]) ? floatval($_POST["price"]) : 0.00;

    if (empty($name_err) && empty($category_err) && empty($supplier_err)) {
        $stmt = $conn->prepare("INSERT INTO items (name, category_id, supplier_id, quantity, price) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siiid", $name, $category_id, $supplier_id, $quantity, $price);

        if ($stmt->execute()) {

            $log_stmt = $conn->prepare("INSERT INTO activity_log (entity_type, entity_name, action) VALUES (?, ?, ?)");
            $entity_type = "Items";
            $entity_name = $name;
            $action = "Added";
            $log_stmt->bind_param("sss", $entity_type, $entity_name, $action);
            $log_stmt->execute();
            $log_stmt->close();

            header("Location: index.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error: Could not add item.</div>";
        }

        $stmt->close();
    }
}

$categories = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
$suppliers = $conn->query("SELECT id, name FROM suppliers ORDER BY name ASC");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/bootstrap/icons/bootstrap-icon/bootstrap-icons.css">
<style>
    body {
        background-color: #f8f9fa;
    }

    .form-card {
        border-radius: 12px;
        max-width: 800px;
        margin: auto;
    }

    .form-label {
        font-weight: 500;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
    }

    .section-title {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #6c757d;
        margin-bottom: 1rem;
    }
</style>

</head>
<body>
<div class="container py-5">

    <div class="mb-3">
        <a href="../dashboard/index.php" class="text-decoration-none text-muted">
            ← Back to dashboard
        </a>
    </div>

    <div class="card shadow-sm form-card">
        <div class="card-body">

            <div class="mb-4 d-flex align-items-center gap-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                     style="width:48px;height:48px;">
                    <i class="bi bi-box-seam fs-5"></i>
                </div>
                <div>
                    <h4 class="mb-0">Add New Item</h4>
                    <small class="text-muted">Create a new inventory item</small>
                </div>
            </div>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>

                <div class="mb-3">
                    <div class="section-title">Item Information</div>

                    <div class="mb-3">
                        <label class="form-label">Item Name</label>
                        <input type="text"
                               name="name"
                               class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>"
                               placeholder="e.g. Wireless Mouse"
                               value="<?php echo htmlspecialchars($name); ?>">
                        <div class="invalid-feedback">
                            <?php echo $name_err; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id"
                                    class="form-select <?php echo (!empty($category_err)) ? 'is-invalid' : ''; ?>">
                                <option value="">Select category</option>
                                <?php while($row = $categories->fetch_assoc()): ?>
                                    <option value="<?php echo $row['id']; ?>"
                                        <?php echo ($category_id == $row['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($row['name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <div class="invalid-feedback">
                                <?php echo $category_err; ?>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Supplier</label>
                            <select name="supplier_id"
                                    class="form-select <?php echo (!empty($supplier_err)) ? 'is-invalid' : ''; ?>">
                                <option value="">Select supplier</option>
                                <?php while($row = $suppliers->fetch_assoc()): ?>
                                    <option value="<?php echo $row['id']; ?>"
                                        <?php echo ($supplier_id == $row['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($row['name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <div class="invalid-feedback">
                                <?php echo $supplier_err; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="section-title">Stock & Pricing</div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number"
                                   name="quantity"
                                   class="form-control"
                                   min="0"
                                   value="<?php echo htmlspecialchars($quantity); ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price (₱)</label>
                            <input type="number"
                                   step="0.01"
                                   name="price"
                                   class="form-control"
                                   min="0"
                                   value="<?php echo htmlspecialchars($price); ?>">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="index.php" class="btn btn-light">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Save Item
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>
