<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirjaudu sisään</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <a href="index.html" class="header-h1">Taitaja Tietotesti</a>
        <a href="#" class="log" id="loginBtn">Kirjaudu sisään</a>
    </div>

    <!-- Kirjautumismodaali -->
    <div id="loginModal" class="modal">
        <div class="main4">
            <h2>Kirjaudu sisään</h2>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div class="error" style="color:red; margin-bottom:15px;">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form id="loginForm" action="login.php" method="POST" class="write">
                <input type="text" name="username" class="write1" placeholder="Syötä käyttäjätunnus" required>
                <input type="password" name="password" class="write1" placeholder="Syötä salasana" required>
                <button type="submit" class="button5">Kirjaudu sisään</button>
            </form>
        </div>
    </div>

    <div class="footer">
        <h2>Taitaja2025 -semifinaali</h2>
        <p>Veeti Myllymäki | Kpedu</p>
    </div>

    <script>
        // Modaalin hallinta
        const modal = document.getElementById("loginModal");
        const btn = document.getElementById("loginBtn");
        const span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>