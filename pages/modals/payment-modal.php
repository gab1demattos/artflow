<!-- Payment Modal -->
<div id="payment-modal-overlay" class="modal-overlay hidden">
  <div class="modal-content">
    <h2>Payment Details</h2>
    <form id="payment-form">
      <label for="card-name">Cardholder Name</label>
      <input type="text" id="card-name" name="card-name" required />
      <label for="card-number">Card Number</label>
      <input type="text" id="card-number" name="card-number" maxlength="19" required />
      <label for="card-expiry">Expiry Date (MM/YY)</label>
      <input type="text" id="card-expiry" name="card-expiry" maxlength="5" required />
      <label for="card-cvc">CVC</label>
      <input type="text" id="card-cvc" name="card-cvc" maxlength="4" required />
      <div class="button-container">
        <button type="button" class="button" id="payment-cancel-btn">Cancel</button>
        <button type="submit" class="button button-primary">Confirm</button>
      </div>
    </form>
  </div>
</div>
