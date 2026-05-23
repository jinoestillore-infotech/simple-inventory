<?php
include '../database/config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    $id = intval($_GET['id']);

    $get_stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
    $get_stmt->bind_param("i", $id);
    $get_stmt->execute();
    $result = $get_stmt->get_result();

    if ($result->num_rows === 0) {
        header("Location: index.php");
        exit();
    }

    $category = $result->fetch_assoc();
    $category_name = $category['name'];
    $get_stmt->close();

    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {

        $log_stmt = $conn->prepare("
            INSERT INTO activity_log (entity_type, entity_name, action)
            VALUES (?, ?, ?)
        ");

        $entity_type = "Category";
        $action = "Deleted";

        $log_stmt->bind_param(
            "sss",
            $entity_type,
            $category_name,
            $action
        );

        $log_stmt->execute();
        $log_stmt->close();

        header("Location: index.php");
        exit();

    } else {
        echo "<div class='alert alert-danger'>
                Error: Could not delete category. Make sure no items are using it.
              </div>";
    }

    $stmt->close();
}

$conn->close();
