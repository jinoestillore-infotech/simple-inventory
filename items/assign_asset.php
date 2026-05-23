<?php
include '../database/config.php';

if (!isset($_GET['item_id'], $_GET['unit']) || empty($_GET['item_id']) || empty($_GET['unit'])) {
    header("Location: show.php?id=" . ($_GET['item_id'] ?? 0));
    exit();
}

$item_id = intval($_GET['item_id']);
$unit_number = intval($_GET['unit']);
$asset_err = '';

$stmt = $conn->prepare("SELECT name FROM items WHERE id = ?");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header("Location: show.php?id=$item_id");
    exit();
}
$item = $result->fetch_assoc();
$stmt->close();

$stmt = $conn->prepare("SELECT id, asset_code FROM item_assets WHERE item_id = ? ORDER BY id ASC LIMIT 1 OFFSET ?");

$offset = $unit_number - 1;
$stmt->bind_param("ii", $item_id, $offset);
$stmt->execute();
$result = $stmt->get_result();
$existingAsset = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $asset_code = trim($_POST['asset_code']);

    if (empty($asset_code)) {
        $asset_err = "Asset code is required.";
    } else {
            $check_stmt = $conn->prepare("SELECT id FROM item_assets WHERE asset_code = ?");
            $check_stmt->bind_param("s", $asset_code);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            if ($check_result->num_rows > 0) {
                $asset_err = "This asset code already exists. Please choose another.";
            }
            $check_stmt->close();

    }

    if (empty($asset_err)) {
        try {
            if ($existingAsset) {
                $stmt = $conn->prepare("UPDATE item_assets SET asset_code = ? WHERE id = ?");
                $stmt->bind_param("si", $asset_code, $existingAsset['id']);
            } else {
                $stmt = $conn->prepare("INSERT INTO item_assets (item_id, asset_code) VALUES (?, ?)");
                $stmt->bind_param("is", $item_id, $asset_code);
            }

            $stmt->execute();
            $stmt->close();

            header("Location: show.php?id=$item_id");
            exit();

        } catch (mysqli_sql_exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $asset_err = "This asset code already exists. Please choose another.";
            } else {
                $asset_err = "Database error: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $existingAsset ? 'Update' : 'Assign'; ?> Asset Code - <?php echo htmlspecialchars($item['name']); ?></title>
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

.section-title {
    font-size: 0.85rem;
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
        <a href="show.php?id=<?php echo $item_id; ?>" class="text-decoration-none text-muted">
            &larr; Back to Units
        </a>
    </div>

    <div class="card shadow-sm form-card">
        <div class="card-body">

            <div class="mb-4 d-flex align-items-center gap-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                     style="width:48px;height:48px;">
                    <i class="bi bi-key fs-5"></i>
                </div>
                <div>
                    <h4 class="mb-0"><?php echo $existingAsset ? 'Update' : 'Assign'; ?> Asset Code</h4>
                    <small class="text-muted">for "<?php echo htmlspecialchars($item['name']); ?>"</small>
                </div>
            </div>

            <form method="post" novalidate>
                <div class="mb-3">
                    <div class="section-title">Unit Information</div>
                    <p>Unit #<?php echo $unit_number; ?></p>
                </div>

                <div class="mb-4">
                    <div class="section-title">Asset Code</div>
                    <input type="text" name="asset_code" class="form-control <?php echo (!empty($asset_err)) ? 'is-invalid' : ''; ?>"
                        placeholder="Enter unique asset code"
                        value="<?php echo isset($existingAsset['asset_code']) ? htmlspecialchars($existingAsset['asset_code']) : ''; ?>"
                        required>
                    <div class="invalid-feedback">
                        <?php echo $asset_err ?? ''; ?>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="show.php?id=<?php echo $item_id; ?>" class="btn btn-light">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>
                        <?php echo $existingAsset ? 'Update' : 'Assign'; ?>
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>

<script src="../assets/bootstrap/bootstrap.bundle.min.js"></script>
<script>
(() => {
  'use strict'
  const forms = document.querySelectorAll('form')
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      event.preventDefault() 
      event.stopPropagation()

      setTimeout(() => {
        if (!form.checkValidity()) {
          form.classList.add('was-validated')
        } else {
          form.submit() 
        }
      }, 100) 
    }, false)
  })
})();
</script>
</body>
</html>

