<?php include "connect.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
</head>
<body>
    <div class="gamepage">
        <div class="header">
            <a href="index.html" class="header-h1">Taitaja Tietotesti</a>
            <a href="kirjaudu.php" class="log">Kirjaudu sisään</a>
        </div>

        <div class="whole">
            <div class="startgame">

                <h2 class="play-h">Aloita peli</h2>
                <form action="startgame.php" method="post">
                <label for="opettaja">Opettaja</label>
                <select name="opettaja" id="opettaja">
                    <?php 
                    //parannus(php ulkoiseen tiedostoon ja liitetään se tarvittaviin kohtiin)
                    $sql = "SELECT username FROM teachers";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        //jokaisen rivin tiedot
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['username'] . "'>" . $row['username'] . "</option>";
                        }
                    } else {
                        echo "0 results";
                    }
                    ?>
                </select>

                <label for="kategoria">Kategoria</label>
                <select name="kategoria" id="kategoria">
                <?php 
                    $sql = "SELECT name FROM categories";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        //jokaisen rivin tiedot
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                        }
                    } else {
                        echo "0 results";
                    }
                    ?>
                </select>

                <div class="ask">
                    <label>Valitse kysymysten määrä</label>
                    
                    <div>
                        <input type="radio" name="kysymykset" id="lyhyt" value="5">
                        <label for="lyhyt">Lyhyt (5)</label>
                    </div>
                    
                    <div>
                        <input type="radio" name="kysymykset" id="keskipitka" checked="checked" value="10">
                        <label for="keskipitka">Keskipitkä (10)</label>
                    </div>
                    
                    <div>
                        <input type="radio" name="kysymykset" id="pitka" value="15">
                        <label for="pitka">Pitkä (15)</label>
                    </div>
                </div>
                    <!--TARVITSEE SUBMIT-->
                <button type="submit" class="button1">Aloita peli</button>
                </form>
            </div>

            <img src="images/3.jpg" alt="" class="img3">
        </div>
        <div class="footer">
            <h2>Taitaja2025 -semifinaali</h2>
            <p>Veeti Myllymäki | Kpedu</p>
        </div>
    </div>
</body>
</html>
