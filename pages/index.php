<?php 
require_once(__DIR__ . '/../includes/session.php');
$session = Session::getInstance();
$user = $session->getUser() ?? null;
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
            <h1 class='artflow-text'>artflow</h1>
            <nav id="menu">
                <input type="checkbox" id="nav_bar">
                <label class="nav_bar" for="nav_bar"></label>
                <ul id="buttons">
                    <?php if ($user): ?>
                        <li>
                            <form action="/actions/logout.php" method="post">
                                <button type="submit" class="button filled hovering">
                                    <?= htmlspecialchars($user['username']) ?> - Logout
                                </button>
                            </form>
                        </li>
                    <?php else: ?>
                        <li><button class="button filled hovering">Sign Up</button></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </header>
        <main class="container">
            <section id="title">
                <h2>where creativity<br>
                <span class='flow-text'>flows</span> seamlessly</h2>
                <p>a collection of diverse artistic talents</p>
            </section>
            <section id="categories"></section>
            <section id="info">
                <div></div>
            </section>
            <footer id="end"></footer>
        </main>

        <?php if (!$user): ?>
            <?php include '../pages/modals/sign-up-modal.php'; ?>
            <?php include '../pages/modals/sign-in-modal.php'; ?>
            <?php include '../pages/modals/go-with-flow-modal.php'; ?>
        <?php endif; ?>
        
        <script src="js/script.js"></script>
    </body>
</html>