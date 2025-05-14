<?php
session_start();
include('connect.php');

if (!isset($_SESSION['teacher_id'])) {
    header("Location: index.html");
    exit();
}

// Suojaa SQL-injektiolta
$category_id = filter_var($_GET['category_id'], FILTER_VALIDATE_INT);
if ($category_id === false) {
    $_SESSION['error'] = "Virheellinen kategoria!";
    header("Location: adminDashboard.php");
    exit();
}

$category = $conn->query("SELECT * FROM categories WHERE id = $category_id")->fetch_assoc();
$questions = $conn->query("SELECT * FROM questions WHERE category_id = $category_id ORDER BY id DESC");

// Hae highscore-data
$highscores = [];
$totals = [5, 10, 15]; // Lyhyt, keskipitkä, pitkä
foreach ($totals as $total) {
    $stmt = $conn->prepare("
        SELECT player_name, score, total_questions 
        FROM highscores 
        WHERE category_id = ? AND total_questions = ? 
        ORDER BY score DESC, created_at ASC 
        LIMIT 5
    ");
    $stmt->bind_param("ii", $category_id, $total);
    $stmt->execute();
    $result = $stmt->get_result();
    $highscores[$total] = [];
    while ($row = $result->fetch_assoc()) {
        $highscores[$total][] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <title>Hallitse kysymyksiä</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .question-list {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .content-wrapper {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .left-side, .right-side {
            width: 100%;
        }

        .question-items {
            color: #333;
        }

        .question-item {
            padding: 15px;
            margin: 10px 0;
            color: white;
        }

        .question-item h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .options {
            display: flex;
            flex-direction: row;
        }

        .options p {
            font-size: 14px;
            margin: 5px 0;
        }

        .question-actions {
            margin: 0;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .edit-btn, .delete-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 15px;
            color: #007bff;
        }

        .edit-btn:hover, .delete-btn:hover {
            color: #0056b3;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 10px;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .add-question-form {
            padding: 0;
            border-radius: 0;
            width: 100%;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
            margin: 0 auto;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 50%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
            outline: none;
        }

        .form-group textarea {
            width: 97%; /* Kysymyskentän leveys 90% */
            height: 20px; /* Kiinteä korkeus */
            resize: none; /* Estää koon muuttamisen */
        }

        .form-group select {
            appearance: none;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="6"><path fill="%23333" d="M0 0h12L6 6z"/></svg>') no-repeat right 10px center;
            background-size: 12px;
        }

        .options-row {
            display: flex;
            flex-direction: row;
            gap: 10px;
            justify-content: space-between;
        }

        .options-row input {
            width: 48%; /* Vastausvaihtoehdot säilyvät 48% leveinä */
        }

        .button5 {
            padding: 5px 10px;
            background: black;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
            margin-top: 10px;
            width: 10vw;
            height: 3vw;
        }

        .button5:hover {
            background: #0056b3;
        }

        .right-side {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin: 0 auto;
        }

        .right-top {
            display: flex;
            flex-direction: row;
            gap: 10px;
            width: 150px;
        }

        .highscore-sections {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 2rem;
            margin-top: 0;
            width: auto;
            box-sizing: border-box;
        }

        .highscore-category {
            padding: 1rem;
            width: 100%;
            min-width: 200px;
            box-sizing: border-box;
            text-align: center;
            border-top: 1px solid #ccc;
        }

        .highscore-list {
            list-style: none;
            padding: 0;
        }

        .highscore-list li {
            padding: 0.5rem;
        }

        /* Popup and Overlay Styles */
        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        #popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            width: 65%;
            height: 55%;
        }

        #popup h3 {
            margin-top: 0;
            font-size: 20px;
            color: #333;
        }

        .button-container {
            display: flex;
            justify-content: left;
            margin-top: 20px;
        }

        .cancel-btn {
            padding: 10px 20px;
            background: #ccc;
            color: #333;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .cancel-btn:hover {
            background: #bbb;
        }

        @media (min-width: 768px) {
            .content-wrapper {
                flex-direction: row;
                justify-content: space-between;
            }
            .left-side {
                width: 60%;
            }
            .right-side {
                width: 35%;
            }
            .highscore-sections {
                flex-direction: column;
                align-items: flex-start;
            }
            .highscore-category {
                width: 100%;
            }
        }

        @media (max-width: 767px) {
            .question-list {
                padding: 0 10px;
            }
            .left-side, .right-side {
                width: 100%;
            }
            .right-side {
                flex-direction: column;
            }
            .right-top {
                width: 100%;
            }
            .highscore-sections {
                width: 100%;
            }
        }

        /* Uusi tyyli kysymyksen otsikolle ja toiminnoille */
        .question-header {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 20px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="admin_dashboard.php" class="header-h1">Takaisin kategorioihin</a>
        <a href="logout.php" class="log">Kirjaudu ulos</a>
    </div>

    <div class="question-list">
        <div class="content-wrapper">
            <div class="left-side">
                <h2><?= htmlspecialchars($category['name']) ?></h2>
                <a href="admin_dashboard.php" class="back-link">Takaisin</a>
                <h2>Kysymykset</h2>
                <div class="question-items">
                    <?php
                    if ($questions->num_rows > 0) {
                        while ($q = $questions->fetch_assoc()) {
                            echo '<div class="question-item">';
                            echo '<div class="question-header">';
                            echo '<div class="question-actions">';
                            echo '<a href="edit_question.php?id=' . $q['id'] . '" class="edit-btn"><i class="fas fa-pencil-alt"></i></a>';
                            echo '<form action="delete_question.php" method="POST" style="display:inline-block; margin-left:10px;">';
                            echo '<input type="hidden" name="question_id" value="' . $q['id'] . '">';
                            echo '<input type="hidden" name="category_id" value="' . $category_id . '">';
                            echo '<button type="submit" class="delete-btn"><i class="fas fa-trash-alt"></i></button>';
                            echo '</form>';
                            echo '</div>';
                            echo '<h3>' . htmlspecialchars($q['question']) . '</h3>';
                            echo '</div>';
                            echo '<div class="options">';
                            $correct = $q['correct_option'];
                            echo '<p>' . htmlspecialchars($q['option_a']) . ($correct === 'a' ? ' (oikea)' : '') . '</p>  |  ';
                            echo '<p>' . htmlspecialchars($q['option_b']) . ($correct === 'b' ? ' (oikea)' : '') . '</p>  |  ';
                            echo '<p>' . htmlspecialchars($q['option_c']) . ($correct === 'c' ? ' (oikea)' : '') . '</p>  |  ';
                            echo '<p>' . htmlspecialchars($q['option_d']) . ($correct === 'd' ? ' (oikea)' : '') . '</p>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo "<p>Ei kysymyksiä tässä kategoriassa</p>";
                    }
                    ?>
                </div>
            </div>
            <div class="right-side">
                <div class="right-top">
                    <div class="add-question-form">
                        <button id="add-question-btn" class="button5">Lisää uusi kysymys</button>
                    </div>
                    <form action="delete_category.php" method="POST" onsubmit="return confirm('Haluatko varmasti poistaa kategorian ja kaikki sen kysymykset?');">
                        <input type="hidden" name="category_id" value="<?= $category_id ?>">
                        <button type="submit" class="button5">Poista kategoria</button>
                    </form>
                </div>
                <div class="highscore-sections">
                    <?php
                    $highscore_categories = [
                        ['total' => 5, 'label' => 'Lyhyt'],
                        ['total' => 10, 'label' => 'Keskipitkä'],
                        ['total' => 15, 'label' => 'Pitkä']
                    ];
                    foreach ($highscore_categories as $hcat) {
                        $scores = $highscores[$hcat['total']] ?? [];
                        echo '<div class="highscore-category">';
                        echo '<h3>' . htmlspecialchars($hcat['label']) . '</h3>';
                        echo '<ol class="highscore-list">';
                        if (!empty($scores)) {
                            foreach ($scores as $item) {
                                echo '<li>' . htmlspecialchars($item['player_name']) . ': ' . $item['score'] . '/' . $item['total_questions'] . '</li>';
                            }
                        } else {
                            echo '<li>Ei tuloksia</li>';
                        }
                        echo '</ol>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay -->
    <div id="overlay"></div>

    <!-- Popup form -->
    <div id="popup">
        <h3>Lisää uusi kysymys</h3>
        <form action="add_question.php" method="POST">
            <input type="hidden" name="category_id" value="<?= $category_id ?>">
            <input type="hidden" name="teacher_id" value="<?= $_SESSION['teacher_id'] ?>">
            <div class="form-group">
                <textarea id="question" name="question" placeholder="Kysymys" required></textarea> <br>
            </div>
            
            <!-- Vastausvaihtoehdot kahdella rivillä -->
            <div class="form-group options-row">
                <input type="text" id="option_a" name="option_a" placeholder="Vaihtoehto A" required>
                <input type="text" id="option_b" name="option_b" placeholder="Vaihtoehto B" required>
            </div>
            <br>
            <div class="form-group options-row">
                <input type="text" id="option_c" name="option_c" placeholder="Vaihtoehto C" required>
                <input type="text" id="option_d" name="option_d" placeholder="Vaihtoehto D" required>
            </div>
            <br>

            <div class="form-group">
                <label for="correct_option">Oikea vastaus</label>
                <select id="correct_option" name="correct_option" required>
                    <option value="a">A</option>
                    <option value="b">B</option>
                    <option value="c">C</option>
                    <option value="d">D</option>
                </select>
            </div>

            <!-- Painikkeet vastausvaihtoehtojen jälkeen -->
            <div class="button-container">
                <button type="submit" class="button5">Lisää kysymys</button>
                <button type="button" class="button5" id="cancel-btn">Peruuta</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('add-question-btn').addEventListener('click', function() {
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('popup').style.display = 'block';
        });

        document.getElementById('cancel-btn').addEventListener('click', function() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('popup').style.display = 'none';
        });

        document.getElementById('overlay').addEventListener('click', function() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('popup').style.display = 'none';
        });
    </script>
</body>
</html>