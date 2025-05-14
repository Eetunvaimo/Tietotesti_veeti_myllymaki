<?php
session_start();
include('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password_hash FROM teachers WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $teacher = $result->fetch_assoc();
        if (password_verify($password, $teacher['password_hash'])) {
            $_SESSION['teacher_id'] = $teacher['id'];
            header("Location: admin_dashboard.php");
            exit();
        }
    }
    
    $_SESSION['error'] = "Väärä käyttäjätunnus tai salasana";
    header("Location: kirjaudu.php"); // Muutettu .php -päätteeseen
    exit();
}
?>