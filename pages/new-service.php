<?php
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../database/categories.php');
require_once(__DIR__ . '/../templates/home.tpl.php');

$session = Session::getInstance();
$user = $session->getUser() ?? null;
if (!$user) {
    header('Location: /');
    exit();
}

$categories = getCategories();
$db = Database::getInstance();

// Fetch all subcategories grouped by category
$subcategoriesByCategory = [];
foreach ($categories as $cat) {
    $stmt = $db->prepare('SELECT id, name FROM Subcategory WHERE category_id = ?');
    $stmt->execute([$cat['id']]);
    $subcategoriesByCategory[$cat['id']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

drawHeader($user);
?>
<main class="container">
    <section>
        <h2>Post a New Service</h2>
        <form id="new-service-form" action="/actions/create-service.php" method="post" enctype="multipart/form-data">
            <label>Title:<br>
                <input type="text" name="title" required maxlength="80">
            </label>
            <label>Description:<br>
                <textarea name="description" required rows="5" maxlength="1000"></textarea>
            </label>
            <label>Category:<br>
                <select name="category_id" id="category-select" required>
                    <option value="">Select category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category_type']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <div id="subcategory-section"></div>
            <label>Delivery Time (days):<br>
                <input type="number" name="delivery_time" min="1" max="60" required>
            </label>
            <label>Price (€):<br>
                <input type="number" name="price" min="0" step="0.01" required>
            </label>
            <label>Media (images/videos, max 5):<br>
                <input type="file" name="media[]" accept="image/*,video/*" multiple required>
                <small>Choose up to 5 files. You will select a primary image after upload.</small>
            </label>
            <div id="primary-image-section"></div>
            <div class="button-container">
                <button type="submit" class="button filled long hovering">Create Service</button>
            </div>
        </form>
    </section>
</main>
<script>
// Subcategory dynamic loading
const subcategoriesByCategory = <?php echo json_encode($subcategoriesByCategory); ?>;
const categorySelect = document.getElementById('category-select');
const subcatSection = document.getElementById('subcategory-section');
categorySelect.addEventListener('change', function() {
    const catId = this.value;
    subcatSection.innerHTML = '';
    if (catId && subcategoriesByCategory[catId] && subcategoriesByCategory[catId].length > 0) {
        let html = '<label>Subcategories (optional):<br>';
        html += '<select name="subcategories[]" multiple size="3">';
        subcategoriesByCategory[catId].forEach(function(sub) {
            html += `<option value="${sub.id}">${sub.name}</option>`;
        });
        html += '</select></label>';
        subcatSection.innerHTML = html;
    }
});
// Media upload: limit to 5 files
const mediaInput = document.querySelector('input[type="file"][name="media[]"]');
mediaInput.addEventListener('change', function() {
    if (this.files.length > 5) {
        alert('You can upload a maximum of 5 files.');
        this.value = '';
    }
});
</script>
<link rel="stylesheet" href="/css/main.css">
<link rel="stylesheet" href="/css/new-service.css">
<?php drawFooter($user); ?>
