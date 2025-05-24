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
<!-- Edit Service Modal -->
<div id="editServiceModal" class="modal" style="display:none;">
  <div class="modal-content">
    <span class="close" id="closeEditServiceModal">&times;</span>
    <h2>Edit Service</h2>
    <form id="editServiceForm" action="/actions/edit-service.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="service_id" id="edit-service-id" value="">
      <label for="edit-service-title">Title</label>
      <input type="text" id="edit-service-title" name="title" required>

      <label for="edit-service-description">Description</label>
      <textarea id="edit-service-description" name="description" rows="4" required></textarea>

      <label for="edit-service-category">Category</label>
      <select id="edit-service-category" name="category" required>
        <!-- Categories will be populated by JS -->
        <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category_type']) ?></option>
        <?php endforeach; ?>
      </select>

      <label for="edit-service-subcategory">Subcategory</label>
      <select id="edit-service-subcategory" name="subcategory" required>
        <!-- Subcategories will be populated by JS -->
      </select>

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

      <button type="submit" class="btn">Save Changes</button>
    </form>
  </div>
</div>
<script>
  // Subcategories data from PHP
  const editSubcategoriesByCategory = <?php echo json_encode($subcategoriesByCategory); ?>;
</script>
<?php include __DIR__ . '/subcategory-overlay-modal.php'; ?>
<!-- Modal logic for edit-service handled in js/script.js or a new edit-service.js -->
