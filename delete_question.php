<?php
session_start();
include('connect.php');

if (!isset($_SESSION['teacher_id'])) {
    header("Location: index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question_id = filter_var($_POST['question_id'], FILTER_VALIDATE_INT);
    
    if ($question_id !== false) {
        $stmt = $conn->prepare("DELETE FROM questions WHERE id = ?");
        $stmt->bind_param("i", $question_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['success'] = "Kysymys poistettu onnistuneesti!";
            } else {
                $_SESSION['error'] = "Kysymystä ei löydy!";
            }
        } else {
            $_SESSION['error'] = "Virhe poistaessa kysymystä";
        }
    } else {
        $_SESSION['error'] = "Virheellinen kysymys!";
    }
} else {
    $_SESSION['error'] = "Virheellinen pyyntö!";
}

// Hae category_id paluuta varten
$category_id = filter_var($_POST['category_id'] ?? null, FILTER_VALIDATE_INT);
$redirect = $category_id ? "manage_questions.php?category_id=$category_id" : "admin_dashboard.php";

header("Location: $redirect");
exit();
?>