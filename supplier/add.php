<?php
include '../database/config.php';

$name = $contact = "";
$name_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter supplier name.";
    } else {
        $name = trim($_POST["name"]);
    }

    $contact = !empty(trim($_POST["contact"])) ? trim($_POST["contact"]) : "";

    if (empty($name_err)) {
        $stmt = $conn->prepare("INSERT INTO suppliers (name, contact) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $contact);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error: Could not add supplier.</div>";
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
<title>Add Supplier</title>
<link rel="stylesheet" href="../assets/bootstrap/bootstrap.min.css">
<link rel="stylesheet" href="../assets/bootstrap/icons/bootstrap-icon/bootstrap-icons.css">
<style>
    body {
        background-color: #f8f9fa;
    }
    .form-card {
        border-radius: 12px;
        max-width: 600px;
        margin: auto;
    }
    .form-label {
        font-weight: 500;
    }
    .form-control {
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
        <a href="../dashboard/index.php" class="text-decoration-none text-muted">← Back to dashboard</a>
    </div>

    <div class="card shadow-sm form-card">
        <div class="card-body">

            <div class="mb-4 d-flex align-items-center gap-3">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
                     style="width:48px;height:48px;">
                    <i class="bi bi-person-plus fs-5"></i>
                </div>
                <div>
                    <h4 class="mb-0">Add Supplier</h4>
                    <small class="text-muted">Enter supplier details</small>
                </div>
            </div>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>

                <div class="mb-4">
                    <div class="section-title">Supplier Information</div>

                    <div class="mb-3">
                        <label class="form-label">Supplier Name</label>
                        <input type="text"
                               name="name"
                               class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>"
                               placeholder="eg. Company Name Inc."
                               value="<?php echo htmlspecialchars($name); ?>">
                        <div class="invalid-feedback">
                            <?php echo $name_err; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contact</label>
                        <input type="text"
                               name="contact"
                               class="form-control"
                               placeholder="eg. 09xx-xxx-xxxx - company@mail.com"
                               value="<?php echo htmlspecialchars($contact); ?>">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="index.php" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save me-1"></i> Add Supplier
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>

