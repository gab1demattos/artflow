<?php 
// You can add PHP code here if needed
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>artflow</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/main.css">
    </head>
    <body>
        <header>
            <h1>artflow</h1>
            <nav id="menu">
                <input type="checkbox" id="nav_bar">
                <label class="nav_bar" for="nav_bar"></label>
                <ul id="buttons">
                    <li><button class="button filled hovering">Sign Up</button></li>
                </ul>
            </nav>
        </header>
        <main class="container">
            <section id="title">
                <h2>where creativity<br>
                <span id="diff">flows</span> seamlessly</h2>
                <p>a collection of diverse artistic talents</p>
            </section>
            <section id="categories"></section>
            <section id="info">
                <div></div>
            </section>
            <footer id="end"></footer>
        </main>

        <?php include '../pages/modals/sign-up-modal.php'; ?>
        <?php include '../pages/modals/sign-in-modal.php'; ?>
        
        <script src="js/script.js"></script>
    </body>
</html>