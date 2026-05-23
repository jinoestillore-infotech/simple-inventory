<?php

include '../database/config.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {

        header("Location: index.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: Could not delete item.</div>";
    }

    $stmt->close();
} else {
    
    header("Location: index.php");
    exit();
}

$conn->close();
?>
