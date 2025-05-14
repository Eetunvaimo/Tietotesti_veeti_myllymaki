<?php
session_start();
include('connect.php');

if (!isset($_SESSION['teacher_id'])) {
    header("Location: index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = $_POST['category_id'];
    $new_name = trim($_POST['new_name']);

    $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $new_name, $category_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Kategoria päivitetty!";
    } else {
        $_SESSION['error'] = "Päivitys epäonnistui";
    }
    
    header("Location: admin_dashboard.php");
    exit();
}
?>