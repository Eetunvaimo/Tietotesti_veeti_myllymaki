<?php
session_start();
include('connect.php');

if (!isset($_SESSION['teacher_id'])) {
    header("Location: index.html");
    exit();
}

// Suojaa SQL-injektiolta
$question_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if ($question_id === false) {
    $_SESSION['error'] = "Virheellinen kysymys!";
    header("Location: admin_dashboard.php");
    exit();
}

// Hae kysymys tietokannasta
$stmt = $conn->prepare("SELECT * FROM questions WHERE id = ?");
$stmt->bind_param("i", $question_id);
$stmt->execute();
$result = $stmt->get_result();
$question = $result->fetch_assoc();

if (!$question) {
    $_SESSION['error'] = "Kysymystä ei löydy!";
    header("Location: admin_dashboard.php");
    exit();
}

// Käsittele lomakkeen lähetys
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question_text = trim($_POST['question']);
    $option_a = trim($_POST['option_a']);
    $option_b = trim($_POST['option_b']);
    $option_c = trim($_POST['option_c']);
    $option_d = trim($_POST['option_d']);
    $correct_option = trim($_POST['correct_option']);

    // Tarkista, että kaikki kentät on täytetty ja correct_option on validi
    if (!empty($question_text) && !empty($option_a) && !empty($option_b) && 
        !empty($option_c) && !empty($option_d) && 
        in_array($correct_option, ['a', 'b', 'c', 'd'])) {
        
        $stmt = $conn->prepare("UPDATE questions 
            SET question = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_option = ?
            WHERE id = ?");
        
        $stmt->bind_param("ssssssi", 
            $question_text,
            $option_a,
            $option_b,
            $option_c,
            $option_d,
            $correct_option,
            $question_id
        );

        if ($stmt->execute()) {
            $_SESSION['success'] = "Kysymys muokattu onnistuneesti!";
        } else {
            $_SESSION['error'] = "Virhe muokatessa kysymystä";
        }
    } else {
        $_SESSION['error'] = "Täytä kaikki kentät oikein!";
    }
    
    header("Location: manage_questions.php?category_id=" . $question['category_id']);
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Muokkaa kysymystä</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .question-form {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .button5 {
            padding: 10px 20px;
            background: black;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .button5:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="manage_questions.php?category_id=<?= $question['category_id'] ?>" class="header-h1">Takaisin kysymyksiin</a>
        <a href="logout.php" class="log">Kirjaudu ulos</a>
    </div>

    <div class="question-form">
        <h2>Muokkaa kysymystä</h2>
        <form action="edit_question.php?id=<?= $question_id ?>" method="POST">
            <div class="form-group">
                <label>Kysymys</label>
                <textarea name="question" required><?= htmlspecialchars($question['question']) ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Vaihtoehto A</label>
                <input type="text" name="option_a" value="<?= htmlspecialchars($question['option_a']) ?>" required>
            </div>
            
            <div class="form-group">
                <label>Vaihtoehto B</label>
                <input type="text" name="option_b" value="<?= htmlspecialchars($question['option_b']) ?>" required>
            </div>
            
            <div class="form-group">
                <label>Vaihtoehto C</label>
                <input type="text" name="option_c" value="<?= htmlspecialchars($question['option_c']) ?>" required>
            </div>
            
            <div class="form-group">
                <label>Vaihtoehto D</label>
                <input type="text" name="option_d" value="<?= htmlspecialchars($question['option_d']) ?>" required>
            </div>
            
            <div class="form-group">
                <label>Oikea vastaus</label>
                <select name="correct_option" required>
                    <option value="a" <?= $question['correct_option'] == 'a' ? 'selected' : '' ?>>A</option>
                    <option value="b" <?= $question['correct_option'] == 'b' ? 'selected' : '' ?>>B</option>
                    <option value="c" <?= $question['correct_option'] == 'c' ? 'selected' : '' ?>>C</option>
                    <option value="d" <?= $question['correct_option'] == 'd' ? 'selected' : '' ?>>D</option>
                </select>
            </div>
            
            <button type="submit" class="button5">Tallenna muutokset</button>
        </form>
    </div>
</body>
</html>