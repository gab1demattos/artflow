<?php function drawHeader($user)
{ ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>artflow</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/responsive/global-responsive.css">
    </head>

    <body>
        <header>
            <h1><a href="/" class='artflow-text'>artflow</a></h1>
            <nav id="menu">
                <div id="search-bar">
                    <input type="text" id="search-input" placeholder="Search here..." />
                    <button id="search-button"><img src="/images/logos/search.png" alt="Search" id="search-icon"></button>
                </div>
                <ul id="buttons">
                    <?php if (!$user): ?>
                        <li><button class="button filled hovering">Sign Up</button></li>
                    <?php else: ?>
                        <li><button id="sidebar-open" onclick="openSidebar()" aria-label="Open Sidebar">☰</button></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </header>

        <?php if ($user): ?>
            <div id="sidebar">
                <div id="profile">
                    <?php
                    echo '<a href="/pages/profile.php?username=' . htmlspecialchars($user['username']) . '" id="profile-link" aria-label="View Profile">'
                    ?>
                    <img src="<?= isset($user['profile_image']) && $user['profile_image'] ? htmlspecialchars($user['profile_image']) : '/images/user_pfp/default.png' ?>" alt="Avatar" id="avatar-sidebar" class="profile-img">
                    <div>
                        <h2 id="profile-name"><?= htmlspecialchars($user['name']) ?></h2>
                        <h3 id="profile-username">@<?= htmlspecialchars($user['username']) ?></h3>
                    </div>
                    </a>
                </div>
                <ul id="sidebar-list">
                    <li class="sidebar-item" id="new-service-button">
                        <img src="/images/logos/add_circle.png" alt="New Service" class="logo">
                        <button id="open-new-service-modal">New Service</button>
                    </li>
                    <li class="sidebar-item">
                      <img src="/images/logos/activity.png" alt="Activity" class="logo">
                      <a href="/pages/activity.php"><button>Activity</button></a>
                    </li>
                    <li class="sidebar-item">
                        <img src="/images/logos/messages.png" alt="Messages" class="logo">
                        <a href="/pages/messages.php" style="text-decoration: none; color: inherit;"><button>Messages</button></a>
                    </li>
                    <li class="sidebar-item">
                        <img src="/images/logos/stats.png" alt="Stats" class="logo">
                        <button>Stats</button>
                    </li>
                    <li class="sidebar-item" id="settings">
                        <img src="/images/logos/settings.png" alt="Settings" class="logo">
                        <button>Settings</button>
                    </li>
                    <li class="sidebar-item">
                        <img src="/images/logos/logout.png" alt="Log Out" class="logo">
                        <form action="/actions/login/logout.php" method="post"><button>Log Out</button></form>
                    </li>
                </ul>
            </div>
            <div id="overlay" onclick="closeSidebar()"></div>
            <script src="/js/sidebar.js"></script>
        <?php endif; ?>
    <?php } ?>

    <?php function drawTitle()
    { ?>
        <main class="container">
            <section id="title">
                <h2>where creativity<br>
                    <span class='flow-text'>flows</span> seamlessly
                </h2>
                <p>a collection of diverse artistic talents</p>
                <div id="boxes">
                    <div class="box" id="box1"></div>
                    <div class="box" id="box2"></div>
                    <div class="box" id="box3"></div>
                </div>
            </section>

        <?php } ?>

        <?php function drawCategories()
        { ?>
            <section id="categories">
                <div id="block">
                    <h2>Explore Categories</h2>
                    <?php
                    require_once __DIR__ . '/../database/classes/category.class.php';
                    $session = Session::getInstance();
                    $user = $session->getUser();
                    if ($user && isset($user['user_type']) && $user['user_type'] === 'admin'): ?>
                        <button id="open-category-modal" class="button filled hovering" type="button" style="margin-bottom:2em;">Add Category</button>
                    <?php endif; ?>
                    <div id="category-list">
                        <?php
                        $categories = Category::getCategories();
                        $db = Database::getInstance();
                        // Only show the first 6 categories on the main page
                        $displayedCategories = array_slice($categories, 0, 6);
                        foreach ($displayedCategories as $index => $category):
                            // Fetch subcategories
                            $stmt = $db->prepare('SELECT name FROM Subcategory WHERE category_id = ?');
                            $stmt->execute([$category['id']]);
                            $subcategories = $stmt->fetchAll(PDO::FETCH_COLUMN);
                        ?>
                            <a href="/pages/category.php?id=<?= $category['id'] ?>" class="category-item" style="text-decoration:none;color:inherit;" aria-label="View category <?= htmlspecialchars($category['category_type']) ?>">

                                <span class="category-link" style="pointer-events:none;"><?= htmlspecialchars($category['category_type']) ?></span>

                            </a>
                        <?php endforeach; ?>
                    </div>
                    <a id="link" href="/pages/see-more-categories.php">see more -></a>
                </div>
                <?php if ($user && isset($user['user_type']) && $user['user_type'] === 'admin'): ?>
                    <div id="category-modal-overlay" class="modal-overlay hidden">
                        <div class="modal" id="category-modal">
                            <div class="modal-content">
                                <div class="form-container">
                                    <h2>Add Category</h2>
                                    <form id="category-form" class="form" action="/actions/create-category.php" method="post" enctype="multipart/form-data">
                                        <input type="text" name="category_name" placeholder="Category name" required>
                                        <input type="file" name="category_image" accept="image/*">
                                        <input type="text" name="subcategories" placeholder="Subcategories (comma separated)">
                                        <div class="button-container">
                                            <button type="submit" class="button filled classic">Create</button>
                                            <button type="button" id="close-category-modal" class="button outline">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </section>
        <?php } ?>

        <?php function drawInfo()
        { ?>
            <section id="info">
                <div id="info-content">
                    <h2>Our motivation</h2>
                    <p>Etiam pellentesque tempus rutrum. Nullam eget nisl nec nulla ultrices commodo eget eget erat. Phasellus non rutrum erat. Duis nec rhoncus enim. Sed condimentum, odio facilisis maximus aliquet, tellus arcu consequat nibh, nec ultrices erat mauris vitae nisi. In maximus posuere egestas. Aenean congue justo non augue eleifend eleifend. Pellentesque dapibus, orci vitae tempus posuere, dolor risus dapibus augue, sed vehicula orci neque ac orci. Phasellus auctor vulputate volutpat.</p>
                </div>
            </section>

        <?php } ?>


        <?php function drawFooter($user)
        { ?>
            <footer id="end">
                <h2>artflow</h2>
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
                    <p>This website was built in the context of the course Linguagens e Tecnologias Web of the Bachelor's Informatics and Computing Engineering of University of Porto .</p>
                    <p>© All rights reserved.</p>
                </div>
            </footer>
        </main>
        <?php if (!$user): ?>
            <?php include __DIR__ . '/../pages/modals/sign-up-modal.php'; ?>
            <?php include __DIR__ . '/../pages/modals/sign-in-modal.php'; ?>
            <?php include __DIR__ . '/../pages/modals/go-with-flow-modal.php'; ?>
        <?php endif; ?>
        <?php include __DIR__ . '/../pages/modals/new-service-modal.php'; ?>
        <!-- Load the modular JavaScript files -->
        <script src="/js/modals.js"></script>
        <script src="/js/categories.js"></script>
        <script src="/js/app.js"></script>
        <!-- Keep script.js for backward compatibility -->
        <script src="/js/script.js"></script>
    </body>

    </html>
<?php } ?>