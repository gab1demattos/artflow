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
        <h2>Post a New Service</h2>
        <form id="new-service-form" action="/actions/create-service.php" method="post" enctype="multipart/form-data">
          <label>Title:<br>
            <input type="text" name="title" required maxlength="80">
            <br>
          </label>
          <label>Description:<br>
            <textarea name="description" required rows="5" maxlength="1000"></textarea>
            <br>
          </label>
          <label>Category:<br>
            <div style="display: flex; align-items: center; gap: 0.7em;">
              <select name="category_id" id="category-select" required>
                <option value="">Select category</option>
                <?php foreach ($categories as $cat): ?>
                  <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category_type']) ?></option>
                <?php endforeach; ?>
              </select>
              <button type="button" id="open-subcategory-overlay" class="button outline yellow hovering" style="display:none;white-space:nowrap;">Choose subcategories</button>
            </div>
          </label>
          <div id="subcategory-section"></div>
          <div class="row-fields">
            <label>Delivery Time (days):<br>
              <input type="number" name="delivery_time" min="1" max="60" required>
              <br>
            </label>
            <label>Price (€):<br>
              <input type="number" name="price" min="0" step="0.01" required>
              <br>
            </label>
          </div>
          <label>Media (images/videos):<br>
            <input type="file" name="media[]" accept="image/*,video/*" multiple required>
            <br>
            <small>Choose up to 5 files. The first image will be the cover.</small>
          </label>
          <div id="primary-image-section"></div>
          <div class="button-container">
            <button type="submit" class="button filled long hovering">Create Service</button>
          </div>
        </form>
      </div>
    </div>
    <script>
    // Subcategories data from PHP
    const subcategoriesByCategory = <?php echo json_encode($subcategoriesByCategory); ?>;
    </script>
    <button class="close-modal" aria-label="Close">&times;</button>
  </div>
</div>
<?php include __DIR__ . '/subcategory-overlay-modal.php'; ?>
<!-- No inline script here: modal logic is handled in js/script.js -->
