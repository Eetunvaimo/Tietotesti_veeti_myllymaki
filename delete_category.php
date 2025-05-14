<?php
session_start();
include('connect.php');

if (!isset($_SESSION['teacher_id'])) {
    header("Location: index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = filter_var($_POST['category_id'], FILTER_VALIDATE_INT);
    
    if ($category_id !== false) {
        // Poista kaikki kategoriaan liittyvät kysymykset
        $stmt_questions = $conn->prepare("DELETE FROM questions WHERE category_id = ?");
        $stmt_questions->bind_param("i", $category_id);
        $stmt_questions->execute();

        // Poista kategoria
        $stmt_category = $conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt_category->bind_param("i", $category_id);

        if ($stmt_category->execute()) {
            if ($stmt_category->affected_rows > 0) {
                $_SESSION['success'] = "Kategoria ja siihen liittyvät kysymykset poistettu!";
            } else {
                $_SESSION['error'] = "Kategoriaa ei löydy!";
            }
        } else {
            $_SESSION['error'] = "Virhe poistaessa kategoriaa!";
        }
    } else {
        $_SESSION['error'] = "Virheellinen kategoria!";
    }
} else {
    $_SESSION['error'] = "Virheellinen pyyntö!";
}

header("Location: admin_dashboard.php");
exit();
?>