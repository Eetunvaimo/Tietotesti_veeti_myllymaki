<?php
session_start();
include('connect.php');

if (!isset($_SESSION['teacher_id'])) {
    header("Location: index.html");
    exit();
}

$category_id = $_GET['id'];
$category = $conn->query("SELECT * FROM categories WHERE id = $category_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Muokkaa kategoriaa</title>
    <!-- Sisällytä samat tyylit kuin dashboardissa -->
</head>
<body>
    <div class="header">
        <a href="admin_dashboard.php" class="header-h1">Takaisin hallintapaneeliin</a>
    </div>

    <section>
        <h2>Muokkaa kategoriaa: <?= $category['name'] ?></h2>
        <form action="update_category.php" method="POST">
            <input type="hidden" name="category_id" value="<?= $category_id ?>">
            <input type="text" name="new_name" value="<?= $category['name'] ?>" required>
            <button type="submit">Päivitä</button>
        </form>
    </section>
</body>
</html>