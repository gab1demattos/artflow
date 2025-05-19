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
<main class="container" style="min-height:80vh;">
    <section style="max-width:600px;margin:2em auto 3em auto;background:white;padding:2em 2.5em 2.5em 2.5em;border-radius:18px;box-shadow:0 2px 10px #0002;">
        <h2 style="text-align:center;font-family:'Modak',sans-serif;color:var(--purple);margin-bottom:1.5em;">Post a New Service</h2>
        <form id="new-service-form" action="/actions/create-service.php" method="post" enctype="multipart/form-data">
            <label>Title:<br>
                <input type="text" name="title" required maxlength="80" style="width:100%;margin-bottom:1em;">
            </label>
            <label>Description:<br>
                <textarea name="description" required rows="5" maxlength="1000" style="width:100%;margin-bottom:1em;"></textarea>
            </label>
            <label>Category:<br>
                <select name="category_id" id="category-select" required style="width:100%;margin-bottom:1em;">
                    <option value="">Select category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category_type']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <div id="subcategory-section" style="margin-bottom:1em;">
                <!-- Subcategories will be loaded here by JS -->
            </div>
            <label>Delivery Time (days):<br>
                <input type="number" name="delivery_time" min="1" max="60" required style="width:100px;margin-bottom:1em;">
            </label>
            <label>Price (€):<br>
                <input type="number" name="price" min="0" step="0.01" required style="width:120px;margin-bottom:1em;">
            </label>
            <label>Media (images/videos, max 5):<br>
                <input type="file" name="media[]" accept="image/*,video/*" multiple required style="margin-bottom:0.5em;">
                <small>Choose up to 5 files. You will select a primary image after upload.</small>
            </label>
            <div id="primary-image-section" style="margin:1em 0;"></div>
            <div class="button-container" style="margin-top:2em;">
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
        html += '<select name="subcategories[]" multiple size="3" style="width:100%;margin-bottom:1em;">';
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
<?php drawFooter($user); ?>
