<!-- Payment Modal -->
<div id="payment-modal-overlay" class="modal-overlay hidden">
    <div class="modal">
        <div class="modal-content">
            <div class="payment-form-section">
                <h2>Payment Details</h2>
                <form id="payment-form" autocomplete="off">
                    <label>Card Number
                        <input type="text" name="card" maxlength="19" placeholder="1234 5678 9012 3456" required pattern="[0-9 ]+">
                    </label>
                    <label>Name on Card
                        <input type="text" name="name" maxlength="40" placeholder="Full Name" required>
                    </label>
                    <div style="display:flex;gap:1em;">
                        <label style="flex:1;">Exp. Date
                            <input type="text" name="exp" maxlength="5" placeholder="MM/YY" required pattern="[0-9/]+">
                        </label>
                        <label style="flex:1;">CVV
                            <input type="text" name="cvv" maxlength="4" placeholder="123" required pattern="[0-9]+">
                        </label>
                    </div>
                    <div class="button-container">
                        <button id="payment-confirm" class="button filled long hovering">Confirm Payment</button>
                        <button type="button" id="close-payment-modal" class="button outline">Cancel</button>
                    </div>
                </form>
            </div>
            <div class="order-overview-section" id="order-overview-section">
                <h3>Order Overview</h3>
                <p id="order-title">Service: <span></span></p>
                <p id="order-owner">Seller: <span></span></p>
                <p id="order-delivery">Delivery: <span></span></p>
                <p id="order-requirements">Requirements: <span></span></p>
                <div class="order-total">Total: <span id="order-total"></span> €</div>
            </div>
        </div>
    </div>
</div>