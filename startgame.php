<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "connect.php";

// Validate and sanitize inputs
$teacher_name = $conn->real_escape_string($_POST['opettaja']);
$category_name = $conn->real_escape_string($_POST['kategoria']);
$number_of_questions = intval($_POST['kysymykset']);

// Hae opettaja ja kategoria ID:t
$teacher_id = $conn->query("SELECT id FROM teachers WHERE username = '$teacher_name'")->fetch_assoc()['id'];
$category_id = $conn->query("SELECT id FROM categories WHERE name = '$category_name'")->fetch_assoc()['id'];

// Hae kysymykset
$stmt = $conn->prepare("SELECT * FROM questions WHERE category_id = ? ORDER BY RAND() LIMIT ?");
$stmt->bind_param("ii", $category_id, $number_of_questions);
$stmt->execute();
$result = $stmt->get_result();
$questions = [];
while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taitaja TietoTesti</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
    <style>
        .peli {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            width: 100%;
        }

        .highscore-sections {
            display: flex;
            flex-direction: column;
            gap: 2rem;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 20px;
            width: 100%;
            max-width: 1200px;
            box-sizing: border-box;
        }

        .highscore-category {
            padding: 1rem;
            width: 30%;
            min-width: 250px;
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

        .highscore-form {
            margin: 20px 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .highscore-form input {
            padding: 8px;
            margin-bottom: 10px;
        }

        .play-again {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .incorrect {
            color: red;
            font-weight: bold;
            margin-left: 5px;
        }

        .correct-mark {
            color: rgb(170, 255, 0);
            font-weight: bold;
            margin-left: 5px;
        }

        .option-cell {
            position: relative;
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="index.html" class="header-h1">Taitaja Tietotesti</a>
        <a href="" class="log">Kirjaudu sisään</a>
    </header>
    <div class="peli">
        <div class="par">
            <p id="kysymykset">Kysymys <span id="currentQuestion">1</span>/<?php echo $number_of_questions;?></p>    
            <p>Pisteet: <span id="score">0</span></p>
        </div>
        <div class="ask1">
            <div id="otsikko-container" class="otsikko-container"></div>
            <div id="question-container" class="question-container"></div>
            <button class="button4" id="next-button" onclick="nextQuestion()">Seuraava kysymys</button>
            <input hidden type="text" name="highscore" id="highscore" placeholder="Kirjoita nimesi tuloslistaan">
        </div>

        <script>
            const questions = <?php echo json_encode($questions); ?>;
            let currentQuestion = 0;
            let score = 0;
            let highscores = {};
            let showingFeedback = false;

            function showQuestion(index) {
                const question = questions[index];
                document.getElementById('question-container').innerHTML = `
                    <h1 class="pelih1">${question.question}</h1>
                    <table class="table1">
                        <tr>
                            <td><input type="radio" name="answer" value="a"></td>
                            <td class="option-cell"><p id="option-a">${question.option_a}</p></td>
                            <td><input type="radio" name="answer" value="b"></td>
                            <td class="option-cell"><p id="option-b">${question.option_b}</p></td>
                        </tr>
                        <tr>
                            <td><input type="radio" name="answer" value="c"></td>
                            <td class="option-cell"><p id="option-c">${question.option_c}</p></td>
                            <td><input type="radio" name="answer" value="d"></td>
                            <td class="option-cell"><p id="option-d">${question.option_d}</p></td>
                        </tr>
                    </table>
                `;
                showingFeedback = false;
                document.getElementById('next-button').textContent = 'Seuraava kysymys';
            }

            function nextQuestion() {
                // Jos näytetään palautetta, siirrytään seuraavaan kysymykseen
                if (showingFeedback) {
                    currentQuestion++;
                    document.getElementById('currentQuestion').textContent = currentQuestion + 1;
                    
                    if (currentQuestion < questions.length) {
                        showQuestion(currentQuestion);
                    } else {
                        // Visa päättynyt
                        document.getElementById('otsikko-container').innerHTML = '<h2>Tuloksesi</h2>';
                        document.getElementById('question-container').innerHTML = `
                            <p>Pisteet: ${score}/${questions.length}</p>
                            <div class="highscore-form">
                                <input type="text" name="player_name" id="player_name" placeholder="Kirjoita nimesi" required>
                                <button class="button4" onclick="saveScore()">Tallenna tulos</button>
                            </div>
                            <div id="highscores-container" style="display: none;"></div>
                        `;
                        document.getElementById('next-button').style.display = 'none';
                        document.getElementById('kysymykset').style.display = 'none';
                    }
                    return;
                }

                // Tarkista onko vastaus valittu
                const selectedAnswer = document.querySelector('input[name="answer"]:checked');
                if (!selectedAnswer) {
                    alert('Valitse yksi vaihtoehto ennen jatkamista!');
                    return;
                }

                const correctAnswer = questions[currentQuestion].correct_option.toLowerCase().trim();
                const selectedValue = selectedAnswer.value.toLowerCase().trim();

                // Merkitään valittu vastaus ja oikea vastaus
                showingFeedback = true;
                document.getElementById('next-button').textContent = currentQuestion < questions.length - 1 ? 'Jatka' : 'Näytä tulokset';

                // Lisätään merkinnät valittuun ja oikeaan vastaukseen
                const selectedOptionId = `option-${selectedValue}`;
                const selectedOptionElement = document.getElementById(selectedOptionId);
                
                const correctOptionId = `option-${correctAnswer}`;
                const correctOptionElement = document.getElementById(correctOptionId);

                if (selectedValue === correctAnswer) {
                    score++;
                    document.getElementById('score').textContent = score;
                    correctOptionElement.innerHTML += '<span class="correct-mark"> ✔ Oikein!</span>';
                } else {
                    selectedOptionElement.innerHTML += '<span class="incorrect"> X Väärin</span>';
                    correctOptionElement.innerHTML += '<span class="correct-mark"> ✔ Oikea vastaus</span>';
                }

                // Estetään uudet valinnat
                document.querySelectorAll('input[name="answer"]').forEach(input => {
                    input.disabled = true;
                });
            }

            function endQuiz() {
                document.getElementById('otsikko-container').innerHTML = '<h2>Tuloksesi</h2>';
                document.getElementById('question-container').innerHTML = `
                    <p>Pisteet: ${score}/${questions.length}</p>
                    <div class="highscore-form">
                        <input type="text" name="player_name" id="player_name" placeholder="Kirjoita nimesi" required>
                        <button class="button4" onclick="saveScore()">Tallenna tulos</button>
                    </div>
                    <div id="highscores-container" style="display: none;"></div>
                `;
                document.getElementById('next-button').style.display = 'none';
                document.getElementById('kysymykset').style.display = 'none';
            }

            function saveScore() {
                const playerName = document.getElementById('player_name').value.trim();
                if (!playerName) {
                    alert('Kirjoita nimesi tallentaaksesi tuloksesi');
                    return;
                }

                fetch('save_score.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        player_name: playerName,
                        score: score,
                        total: questions.length,
                        teacher_id: <?php echo $teacher_id; ?>,
                        category_id: <?php echo $category_id; ?>
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        highscores = data.highscores;
                        showHighscores(highscores);
                        document.getElementById('highscores-container').style.display = 'block';
                        document.querySelector('.highscore-form').style.display = 'none';
                    } else {
                        alert('Tallennus epäonnistui: ' + (data.message || 'Tuntematon virhe'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Tallennus epäonnistui');
                });
            }

            function showHighscores(highscoresData) {
                let html = '<div class="highscore-sections">';
                const categories = [
                    { total: 5, label: 'Lyhyt' },
                    { total: 10, label: 'Keskipitkä' },
                    { total: 15, label: 'Pitkä' }
                ];
                
                categories.forEach(cat => {
                    const scores = highscoresData[cat.total] || [];
                    html += `
                        <div class="highscore-category">
                            <h3>${cat.label}</h3>
                            <ol class="highscore-list">${
                                scores.length > 0 
                                    ? scores.map(item => `<li>${item.player_name}: ${item.score}/${item.total_questions}</li>`).join('')
                                    : '<li>Ei tuloksia</li>'
                            }</ol>
                        </div>`;
                });
                html += '</div>';
                html += '<button class="play-again" onclick="window.location.href=\'game.php\'">Pelaa uudestaan</button>';
                document.getElementById('highscores-container').innerHTML = html;
            }

            showQuestion(currentQuestion);
        </script>
    </div>
    
    <footer class="footer">
        <h2>Taitaja2025 -semifinaali</h2>
        <p>Veeti Myllymäki | Kpedu</p>
    </footer>
</body>
</html>