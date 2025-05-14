<?php
header('Content-Type: application/json');
include "connect.php";

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Tallenna uusi tulos
    $player = $conn->real_escape_string($data['player_name']);
    $score = (int)$data['score'];
    $total = (int)$data['total'];
    $teacher_id = (int)$data['teacher_id'];
    $category_id = (int)$data['category_id'];
    
    $stmt = $conn->prepare("
        INSERT INTO highscores (player_name, score, total_questions, teacher_id, category_id)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("siiii", $player, $score, $total, $teacher_id, $category_id);
    $stmt->execute();
    $stmt->close();

    // Hae kyseisen kategorian tulokset
    $highscores = [];
    foreach ([5, 10, 15] as $total_q) {
        $stmt = $conn->prepare("
            SELECT player_name, score, total_questions 
            FROM highscores 
            WHERE total_questions = ? AND category_id = ?
            ORDER BY score DESC, created_at ASC 
            LIMIT 5
        ");
        $stmt->bind_param("ii", $total_q, $category_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $highscores[$total_q] = [];
        while ($row = $result->fetch_assoc()) {
            $highscores[$total_q][] = $row;
        }
        $stmt->close();
    }

    echo json_encode(['success' => true, 'highscores' => $highscores]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>