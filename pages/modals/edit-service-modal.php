<?php
require_once __DIR__ . '/../../database/classes/category.class.php';
$categories = Category::getCategories();
$db = Database::getInstance();
$subcategoriesByCategory = [];
foreach ($categories as $cat) {
    $stmt = $db->prepare('SELECT id, name FROM Subcategory WHERE category_id = ?');
    $stmt->execute([$cat['id']]);
    $subcategoriesByCategory[$cat['id']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<div id="edit-service-modal-overlay" class="modal-overlay hidden">
  <div class="modal" id="editServiceModal">
    <div class="modal-content">
      <div class="form-container">
        <h2>Edit Service</h2>
        <form id="editServiceForm" action="/actions/edit-service.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="service_id" id="edit-service-id" value="">
          <label for="edit-service-title">Title</label>
          <input type="text" id="edit-service-title" name="title" required>
          <label for="edit-service-description">Description</label>
          <textarea id="edit-service-description" name="description" rows="4" required></textarea>
          <label for="edit-service-category">Category</label>
          <div style="display: flex; align-items: center; gap: 0.7em; margin-bottom: 1.1em;">
            <select id="edit-service-category" name="category" required>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category_type']) ?></option>
              <?php endforeach; ?>
            </select>
            <button type="button" id="edit-open-subcategory-overlay" class="button outline green hovering" style="display:none;white-space:nowrap;">Choose subcategories</button>
          </div>
          <div id="edit-subcategory-section"></div>

          <div class="edit-service-row">
            <div class="edit-service-col">
              <label for="edit-service-price">Price (€)</label>
              <input type="number" id="edit-service-price" name="price" min="1" step="0.01" required>
            </div>
            <div class="edit-service-col">
              <label for="edit-service-delivery">Delivery Time (days)</label>
              <input type="number" id="edit-service-delivery" name="delivery_time" min="1" required>
            </div>
          </div>

          <label for="edit-service-images">Images</label>
          <input type="file" id="edit-service-images" name="images[]" accept="image/*" multiple>
          <div id="edit-service-image-preview"></div>

          <div class="button-container">
            <button type="submit" class="button filled hovering long" id="edit-service-save-btn">Save Changes</button>
            <button type="button" class="button outline long" id="cancel-edit-service">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  const editSubcategoriesByCategory = <?php echo json_encode($subcategoriesByCategory); ?>;
</script>
<?php include __DIR__ . '/subcategory-overlay-modal.php'; ?>
