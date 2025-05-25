<?php
require_once(__DIR__ . '/../database/security/csp_helper.php');
CSPHelper::apply();

function drawHeader($user, $currentPage = '')
{ ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>artflow</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="/images/a.png" type="image/png">
        <link rel="stylesheet" href="/css/main.css">
    </head>

    <body>
        <header>
            <h1><a href="/" class='artflow-text'>artflow</a></h1>
            <nav id="menu">
                <ul id="buttons">
                    <li><a href="/pages/services/search.php" id="search-icon-link"><img src="/images/logos/search2.svg" alt="Search" id="search-icon"></a></li>
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
                    echo '<a href="/pages/users/profile.php?username=' . htmlspecialchars($user['username']) . '" id="profile-link" aria-label="View Profile">'
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
                        <a href="/pages/info/activity.php"><button>Activity</button></a>
                    </li>
                    <li class="sidebar-item">
                        <img src="/images/logos/messages.png" alt="Messages" class="logo">
                        <a href="/pages/users/messages.php"><button>Messages</button></a>
                    </li>
                    <li class="sidebar-item">
                        <img src="/images/logos/stats.png" alt="Stats" class="logo">
                        <a href="/pages/info/stats.php"><button>Stats</button></a>
                    </li>
                    <?php if (isset($user['user_type']) && $user['user_type'] === 'admin'): ?>
                        <li class="sidebar-item">
                            <img src="/images/logos/admin_panel.png" alt="Admin Panel" class="logo">
                            <a href="/pages/users/admin.php"><button>Admin Panel</button></a>
                        </li>
                    <?php endif; ?>

                    <li class="sidebar-item" id="logout-button">
                        <img src="/images/logos/logout.png" alt="Log Out" class="logo">
                        <form action="/actions/login/logout.php" method="post"><button>Log Out</button></form>
                    </li>
                </ul>
            </div>
            <div id="overlay" onclick="closeSidebar()"></div>
            <script src="/js/others/sidebar.js"></script>
        <?php endif; ?>

    <?php } ?>

    <?php function drawTitle()
    { ?>
        <main class="container">
            <section id="title">
                <img src="/images/flow_1.svg" alt="flow" id="flow-title">
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
                    ?>
                    <div id="category-list">
                        <?php
                        $categories = Category::getCategories();
                        $db = Database::getInstance();
                        $displayedCategories = array_slice($categories, 0, 6);
                        foreach ($displayedCategories as $index => $category):
                            $stmt = $db->prepare('SELECT name FROM Subcategory WHERE category_id = ?');
                            $stmt->execute([$category['id']]);
                            $subcategories = $stmt->fetchAll(PDO::FETCH_COLUMN);
                        ?>
                            <a href="/pages/services/category.php?id=<?= $category['id'] ?>" class="category-item" style="text-decoration:none;color:inherit;" aria-label="View category <?= htmlspecialchars($category['category_type']) ?>">

                                <span class="category-link" style="pointer-events:none;"><?= htmlspecialchars($category['category_type']) ?></span>

                            </a>
                        <?php endforeach; ?>
                    </div>
                    <button id="see-more" class="button filled orange" onclick="window.location.href='/pages/services/see-more-categories.php'">See More →</button>
                </div>
                <?php if ($user && isset($user['user_type']) && $user['user_type'] === 'admin'): ?>
                    <div id="category-modal-overlay" class="modal-overlay hidden">
                        <div class="modal" id="category-modal">
                            <div class="modal-content">
                                <div class="form-container">
                                    <h2>Add Category</h2>
                                    <form id="category-form" class="form" action="/actions/adminpanel/add-category.php" method="post" enctype="multipart/form-data">
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
                    <p>In an era where artificial intelligence is rapidly transforming creative industries, many fear that AI-generated art will overshadow human creativity.</p>
                    <p>As developers, we recognize the power of technology—but we refuse to embrace trends that devalue real art and the artists behind it. Instead, we choose to build a space that celebrates and empowers human creativity.</p>
                    <p>Our mission is to create a freelancing platform exclusively for artists—a place where illustrators, painters, designers, and digital creators can thrive, connect with clients who value authentic work, and earn a fair income from their craft.</p>
                    <p>Together, we can ensure that artists remain at the heart of creativity. Because the world doesn’t need more machine-made content—it needs <span class='flow-text'>art</span>. And art needs <span class='flow-text'>you</span>.</p>
                </div>
            </section>

        <?php } ?>


        <?php function drawFooter($user, $showGoFlow = false)
        { ?>
            <footer id="end">
                <h2>artflow</h2>
                <div id="end-content">
                    <div id="authors">
                        <h3>Developed by:</h3>
                        <ul>
                            <li class="author footer-link">@ <a href='https://github.com/franpts2'>Francisca Portugal</a></li>
                            <li class="author footer-link">@ <a href='https://github.com/gab1demattos'>Gabriela de Mattos</a></li>
                            <li class="author footer-link">@ <a href='https://github.com/maluviieira'>Maria Luiza Vieira</a></li>
                        </ul>
                    </div>
                    <div id="footer-links">
                        <h3>Quick Links:</h3>
                        <ul>
                            <li class="footer-link"><a href="/">Home</a></li>
                            <li class="footer-link"><a href="/pages/services/see-more-categories.php">All Categories</a></li>
                            <li class="footer-link"><a href="/pages/services/search.php">All Services</a></li>
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
        <?php endif; ?>

        <?php 
        ?>
        <?php include __DIR__ . '/../pages/modals/go-with-flow-modal.php'; ?>

        <?php include __DIR__ . '/../pages/modals/new-service-modal.php'; ?>

        <script src="/js/modal/modals.js"></script>
        <script src="/js/services/categories.js"></script>
        <script src="/js/others/app.js"></script>
        <script src="/js/others/script.js"></script>
        <script src="/js/services/search.js"></script>
        <script src="/js/others/go-flow-helper.js"></script>
        
        <?php if (isset($_SESSION['signup_error'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const signUpModal = document.getElementById('signup-modal-overlay');
                if (signUpModal) {
                    signUpModal.classList.remove('hidden');
                }
                
                if (typeof window.showModalError === 'function') {
                    window.showModalError('signup-modal-overlay', '<?= addslashes(htmlspecialchars($_SESSION['signup_error'])) ?>');
                }
                
                <?php unset($_SESSION['signup_error']); ?>
            });
        </script>
]
        <?php endif; ?>
    </body>

    </html>
<?php } ?>