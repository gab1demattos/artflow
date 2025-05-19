<?php function drawHeader($user){ ?>
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
            <div id="search-bar">
                <input type="text" id="search-input" placeholder="Search here..." />
                <button id="search-button"><img src="images/search.png" alt="Search" id="search-icon"></button>
            </div>
            <ul id="buttons">
                <?php if (!$user): ?>
                    <li><button class="button filled hovering">Sign Up</button></li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php if ($user): ?>
            <button id="sidebar-open" onclick="openSidebar()" aria-label="Open Sidebar">☰</button>
        <?php endif; ?>
    </header>

    <?php if ($user): ?>
    <div id="sidebar">
        <button id="sidebar-close" onclick="closeSidebar()">x</button>
        <ul id="sidebar-list">
            <li id="new-service-button"><button>New Service</buuton></li>
            <li class="sidebar-item">
                <img src="images/profile.png" alt="Profile" class="logo">
                <button>Profile</buuton>
            </li>
            <li class="sidebar-item">
                <img src="images/activity.png" alt="Activity" class="logo">
                <button>Activity</button>
            </li>
            <li class="sidebar-item">
                <img src="images/messages.png" alt="Messages" class="logo">
                <button>Messages</button>
            </li>
            <li class="sidebar-item">
                <img src="images/stats.png" alt="Stats" class="logo">
                <button>Stats</buTton>
            </li>
            <li class="sidebar-item" id="settings">
                <img src="images/settings.png" alt="Settings" class="logo">
                <button>Settings</button>
            </li>
            <li class="sidebar-item">
                <img src="images/logout.png" alt="Log Out" class="logo">
                <form action="/actions/logout.php" method="post"><button>Log Out</button></form>
            </li>
        </ul>
    </div>
    <div id="overlay" onclick="closeSidebar()"></div>
    <?php endif; ?>

<?php } ?>

<?php function drawTitle(){ ?>
        <main class="container">
                <section id="title">
                    <h2>where creativity<br>
                    <span class='flow-text'>flows</span> seamlessly</h2>
                    <p>a collection of diverse artistic talents</p>
                    <div id="boxes">
                        <div class="box" id="box1"></div>
                        <div class="box" id="box2"></div>
                        <div class="box" id="box3"></div>
                    </div>
                </section>

<?php } ?>

<?php function drawCategories(){ ?>
                <section id="categories">
                    <div id="block">
                        <h2>Explore categories</h2>
                        <div id="category-list">
                            <?php $categories = getCategories(); ?>
                            <?php foreach ($categories as $index => $category): ?>
                                <?php if ($index >= 6) break; // limit to the first 6 categories ?>
                                <div class="category-item">
                                    <a class="category-link"><?= htmlspecialchars($category['category_type']) ?></a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <a id="link">see more -></a>
                    </div>
                </section>

<?php } ?>

<?php function drawInfo(){ ?>
                <section id="info">
                    <div id="info-content">
                        <h2>Our motivation</h2>
                        <p>Etiam pellentesque tempus rutrum. Nullam eget nisl nec nulla ultrices commodo eget eget erat. Phasellus non rutrum erat. Duis nec rhoncus enim. Sed condimentum, odio facilisis maximus aliquet, tellus arcu consequat nibh, nec ultrices erat mauris vitae nisi. In maximus posuere egestas. Aenean congue justo non augue eleifend eleifend. Pellentesque dapibus, orci vitae tempus posuere, dolor risus dapibus augue, sed vehicula orci neque ac orci. Phasellus auctor vulputate volutpat.</p>
                    </div>
                </section>

<?php } ?>


<?php function drawFooter($user){ ?>
            <footer id="end">
                <h2>Artflow</h2>
                <div id="end-content">
                    <div id="authors">
                        <h3>Developed by:</h3>
                        <ul>
                            <li class="author">@ Francisca Portugal</li>
                            <li class="author">@ Gabriela de Mattos</li>
                            <li class="author">@ Maria Luiza Vieira</li>
                        </ul>
                    </div>
                    <div id="footer-links">
                        <h3>Quick Links:</h3>
                        <ul>
                            <li class="footer-link"><a>Home</a></li>
                            <li class="footer-link"><a>Page 1</a></li>
                            <li class="footer-link"><a>Page 2</a></li>
                        </ul>
                    </div>
                </div>
                <div id="end-footer">
                    <p>This website was built in the context of the course Linguagens e Tecnologias Web of the Bachelor’s Informatics and Computing Engineering of University of Porto .</p>
                    <p>© All rights reserved.</p>
                </div>
            </footer>
        </main>
        <?php if (!$user): ?>
            <?php include __DIR__ . '/../pages/modals/sign-up-modal.php'; ?>
            <?php include __DIR__ . '/../pages/modals/sign-in-modal.php'; ?>
            <?php include __DIR__ . '/../pages/modals/go-with-flow-modal.php'; ?>
        <?php endif; ?>
        
        <script src="js/script.js"></script>
    </body>
</html>
<?php } ?>
