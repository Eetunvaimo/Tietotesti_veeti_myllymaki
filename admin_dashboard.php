<?php
session_start();
include('connect.php');

if (!isset($_SESSION['teacher_id'])) {
    header("Location: index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['category_name'])) {
    $category_name = trim($_POST['category_name']);
    
    if (!empty($category_name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $category_name);
        $stmt->execute();
    }
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taitaja Hallintapaneeli</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
    <style>
        /* Vanha pop-up-tyyli */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        .modal-content {
            position: relative;
            background: white;
            padding: 20px;
            margin: 15% auto;
            width: 400px; /* Lyhennetty modaalin leveyttä */
            border-radius: 5px;
        }

        .modal-header {
            font-size: 1.2em;
            margin-bottom: 15px;
            text-align: center; /* Keskitetty otsikko */
        }

        .modal-actions {
            margin-top: 20px;
            text-align: center; /* Keskitettyjen painikkeiden */
        }

        .add-category-btn {
            background: black;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="admin_dashboard.php" class="header-h1">Taitaja Hallintapaneeli</a>
        <a href="logout.php" class="log">Kirjaudu ulos</a>
    </div>

    <div class="category-list">
        <div class="category-list2">
            <button onclick="openCategoryModal()" class="add-category-btn">Luo uusi kategoria</button>
        </div>

        <div class="category-list1">
            <h2>Kategoriat</h2>
            <?php
            $categories = $conn->query("SELECT * FROM categories ORDER BY id DESC");
            if ($categories->num_rows > 0):
                while ($cat = $categories->fetch_assoc()): ?>
                    <div class="category-item">
                        <a href="manage_questions.php?category_id=<?= $cat['id'] ?>" class="category-link">
                            <?= htmlspecialchars($cat['name']) ?>
                        </a>
                    </div>
                <?php endwhile;
            else: ?>
                <p>Ei kategorioita vielä</p>
            <?php endif; ?>
        </div>
    </div>

    <div id="categoryModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">Luo uusi kategoria</div>
            <form method="POST">
                <input type="text" name="category_name" required 
                       placeholder="Kategorian nimi"
                       style="width: 80%; /* Lyhennetty leveyttä */
                              padding: 8px;
                              margin: 0 auto 10px;
                              display: block; /* Keskitetty kenttä */
                              border: 1px solid #ddd;
                              border-radius: 4px;">
                <div class="modal-actions">
                    <button type="submit" style="background: #007bff;...">Luo</button>
                    <button type="button" onclick="closeModal()"...>Peru</button>
                </div>
            </form>
        </div>
    </div>


    <div class="footer">
        <h2>Taitaja2025 -semifinaali</h2>
        <p>Veeti Myllymäki | Kpedu</p>
    </div>

    <script>
        // Pop-up-toiminnot
        function openCategoryModal() {
            document.getElementById('categoryModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('categoryModal').style.display = 'none';
        }

        // Sulje pop-up klikkauksella ulkopuolelle
        window.onclick = function(event) {
            if (event.target == document.getElementById('categoryModal')) {
                closeModal();
            }
        }
    </script>
</body>
</html>


<?php
session_start();
include('connect.php');

if (!isset($_SESSION['teacher_id'])) {
    header("Location: index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['category_name'])) {
    $category_name = trim($_POST['category_name']);
    
    if (!empty($category_name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $category_name);
        $stmt->execute();
    }
    header("Location: admin_dashboard.php");
    exit();
}
?>
