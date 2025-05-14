<?php
header('Content-Type: application/json');
include "connect.php";

try {
    $highscores = [];
    foreach ([5, 10, 15] as $total) {
        $result = $conn->query("SELECT player_name, score, total_questions 
                              FROM highscores 
                              WHERE total_questions = $total
                              ORDER BY score DESC 
                              LIMIT 5");
        $highscores[$total] = [];
        while ($row = $result->fetch_assoc()) {
            $highscores[$total][] = $row;
        }
    }
    echo json_encode(['success' => true, 'highscores' => $highscores]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>