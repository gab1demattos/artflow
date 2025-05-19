<?php
// Draw the New Service modal (to be included in the main layout, not as a standalone page)
$categories = getCategories();
$db = Database::getInstance();
$subcategoriesByCategory = [];
foreach ($categories as $cat) {
    $stmt = $db->prepare('SELECT id, name FROM Subcategory WHERE category_id = ?');
    $stmt->execute([$cat['id']]);
    $subcategoriesByCategory[$cat['id']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<div id="new-service-modal-overlay" class="modal-overlay hidden">
  <div class="modal" id="new-service-modal">
    <div class="modal-content">
      <div class="form-container">
        <h2 style="color:var(--purple);">Post a New Service</h2>
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
      </div>
    </div>
    <button class="close-modal" aria-label="Close" style="position:absolute;top:1em;right:1em;font-size:2rem;background:none;border:none;color:#888;cursor:pointer;">&times;</button>
  </div>
</div>
<!-- No inline script here: modal logic is handled in js/script.js -->
