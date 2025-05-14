<?php
session_start();
include('connect.php');

if (!isset($_SESSION['teacher_id'])) {
    header("Location: index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = trim($_POST['category_name']);
    
    if (!empty($category_name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $category_name);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Kategoria lisätty!";
        } else {
            $_SESSION['error'] = "Virhe: ".$conn->error;
        }
    } else {
        $_SESSION['error'] = "Anna kategorialle nimi";
    }
    
    header("Location: ../admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Pop-up lomake -->
    <div id="categoryModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">Luo uusi kategoria</div>
        <form method="POST" id="categoryForm" action="admin_category.php">
            <input type="text" name="category_name" required 
                   style="width: 100%; padding: 8px; box-sizing: border-box;"
                   placeholder="Kirjoita kategorian nimi tähän...">
            <div class="modal-actions">
                <button type="submit">Luo</button>
                <button type="button" onclick="window.location.href='admin_dashboard.php'">Peru</button>
            </div>
        </form>
    </div>
</div>

    <script>
        // Avaa pop-up
        function openCategoryModal() {
            document.getElementById('categoryModal').style.display = 'flex';
        }
        
        // Sulje pop-up
        function closeModal() {
            document.getElementById('categoryModal').style.display = 'none';
        }
        
        // Avaa pop-up heti kun sivu latautuu
        window.onload = openCategoryModal;
        
        // Lähetä lomake ja sulje pop-up
        document.getElementById('categoryForm').onsubmit = function() {
            closeModal();
            return true;
        };
    </script>
</body>
</html>