<?php
include '../database/config.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM suppliers WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: Could not delete supplier. Check if items are linked to this supplier.</div>";
    }

    $stmt->close();
} else {
    header("Location: index.php");
    exit();
}

$conn->close();
?>
