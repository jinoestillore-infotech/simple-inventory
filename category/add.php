<?php
include '../database/config.php';

$name = $description = "";
$name_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter category name.";
    } else {
        $name = trim($_POST["name"]);
    }

    $description = !empty(trim($_POST["description"])) ? trim($_POST["description"]) : "";

    if (empty($name_err)) {
        $stmt = $conn->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);

        if ($stmt->execute()) {

            $log_stmt = $conn->prepare("INSERT INTO activity_log (entity_type, entity_name, action) VALUES (?, ?, ?)");
            $entity_type = "Category";
            $entity_name = $name;
            $action = "Added";
            $log_stmt->bind_param("sss", $entity_type, $entity_name, $action);
            $log_stmt->execute();
            $log_stmt->close();

            header("Location: index.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error: Could not add category.</div>";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/bootstrap/icons/bootstrap-icon/bootstrap-icons.css">
<style>
    body {
        background-color: #f8f9fa;
    }

    .form-card {
        border-radius: 12px;
        max-width: 700px;
        margin: auto;
    }

    .form-label {
        font-weight: 500;
    }

    .form-control {
        border-radius: 8px;
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
                    <i class="bi bi-tags fs-5"></i>
                </div>
                <div>
                    <h4 class="mb-0">Add New Category</h4>
                    <small class="text-muted">Create a new item category</small>
                </div>
            </div>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>

                <div class="mb-4">
                    <label class="form-label">Category Name</label>
                    <input type="text"
                           name="name"
                           class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>"
                           placeholder="e.g. Electronics"
                           value="<?php echo htmlspecialchars($name); ?>">
                    <div class="invalid-feedback">
                        <?php echo $name_err; ?>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Description <span class="text-muted">(optional)</span></label>
                    <textarea name="description"
                              class="form-control"
                              rows="4"
                              placeholder="Short description about this category"><?php echo htmlspecialchars($description); ?></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="index.php" class="btn btn-light">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Save Category
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>
