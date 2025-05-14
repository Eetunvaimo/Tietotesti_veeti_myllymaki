<?php
session_start();
include('connect.php');

if (!isset($_SESSION['teacher_id'])) {
    header("Location: index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = filter_var($_POST['category_id'], FILTER_VALIDATE_INT);
    $teacher_id = filter_var($_SESSION['teacher_id'], FILTER_VALIDATE_INT);
    $question = trim($_POST['question']);
    $option_a = trim($_POST['option_a']);
    $option_b = trim($_POST['option_b']);
    $option_c = trim($_POST['option_c']);
    $option_d = trim($_POST['option_d']);
    $correct_option = trim($_POST['correct_option']);

    // Tarkista, että kaikki kentät on täytetty ja correct_option on validi
    if ($category_id !== false && $teacher_id !== false &&
        !empty($question) && !empty($option_a) && !empty($option_b) && 
        !empty($option_c) && !empty($option_d) && 
        in_array($correct_option, ['a', 'b', 'c', 'd'])) {
        
        $stmt = $conn->prepare("INSERT INTO questions 
            (category_id, teacher_id, question, option_a, option_b, option_c, option_d, correct_option) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("iissssss", 
            $category_id,
            $teacher_id,
            $question,
            $option_a,
            $option_b,
            $option_c,
            $option_d,
            $correct_option
        );

        if ($stmt->execute()) {
            $_SESSION['success'] = "Kysymys lisätty!";
        } else {
            $_SESSION['error'] = "Virhe lisättäessä kysymystä";
        }
    } else {
        $_SESSION['error'] = "Täytä kaikki kentät oikein!";
    }
    
    header("Location: admin_dashboard.php");
    exit();
}
?>