// JS for tab switching on the activity page
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.activity-tab');
    const contents = document.querySelectorAll('.activity-tab-content');
    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            tabs.forEach(t => t.classList.remove('active'));
            contents.forEach(c => c.classList.remove('active'));
            tab.classList.add('active');
            document.getElementById(tab.dataset.tab).classList.add('active');
        });
    });

    // Fetch and render orders dynamically
    fetch('/actions/activity/get-orders.php')
        .then(res => res.json())
        .then(data => {
            if (!data.success) return;
            // Render Your Orders
            const yourOrders = document.getElementById('your-orders');
            yourOrders.innerHTML = '';
            if (data.yourOrders.length === 0) {
                yourOrders.innerHTML = '<div class="no-orders">No orders found.</div>';
            } else {
                // Sort: in progress first, then completed
                const sortedOrders = data.yourOrders.slice().sort((a, b) => {
                    if (a.status === b.status) return 0;
                    if (a.status === 'in progress') return -1;
                    if (b.status === 'in progress') return 1;
                    return 0;
                });
                sortedOrders.forEach(order => {
                    yourOrders.innerHTML += `
                    <div class="order-card">
                        <div class="order-header">
                            <span class="order-title">${order.title}</span>
                            <span class="order-status ${order.status === 'completed' ? 'completed' : 'in-progress'}">${order.status === 'completed' ? 'Completed' : 'In Progress'}</span>
                        </div>
                        <div class="order-details">
                            <div><strong>Seller:</strong> ${order.seller_name} (@${order.seller_username})</div>
                            <div><strong>Delivery:</strong> ${order.delivery_time} days</div>
                            <div><strong>Requirements:</strong> ${order.requirements}</div>
                            <div><strong>Total:</strong> ${order.price}€</div>
                            <div><strong>Date:</strong> ${order.date ? order.date.split(' ')[0] : ''}</div>
                        </div>
                    </div>`;
                });
            }
            // Render Orders From Others
            const ordersFromOthers = document.getElementById('orders-from-others');
            ordersFromOthers.innerHTML = '';
            if (data.ordersFromOthers.length === 0) {
                ordersFromOthers.innerHTML = '<div class="no-orders">No orders found.</div>';
            } else {
                // Sort: not delivered (in progress) first, then delivered (completed)
                const sortedOrders = data.ordersFromOthers.slice().sort((a, b) => {
                    if (a.status === b.status) return 0;
                    if (a.status === 'in progress') return -1;
                    if (b.status === 'in progress') return 1;
                    return 0;
                });
                sortedOrders.forEach(order => {
                    const delivered = order.status === 'completed';
                    ordersFromOthers.innerHTML += `
                    <div class="order-card" data-order-id="${order.id}">
                        <div class="order-header">
                            <span class="order-title">${order.title}</span>
                            <span class="order-status ${delivered ? 'delivered' : 'not-delivered'}">${delivered ? 'Delivered' : 'Not Delivered'}</span>
                        </div>
                        <div class="order-details">
                            <div><strong>Buyer:</strong> ${order.buyer_name} (@${order.buyer_username})</div>
                            <div><strong>Delivery:</strong> ${order.delivery_time} days</div>
                            <div><strong>Requirements:</strong> ${order.requirements}</div>
                            <div><strong>Total:</strong> ${order.price}€</div>
                            <div><strong>Date:</strong> ${order.date ? order.date.split(' ')[0] : ''}</div>
                        </div>
                        ${!delivered ? '<button class="mark-delivered-btn">Mark as Delivered</button>' : ''}
                    </div>`;
                });
            }
            // Re-attach mark delivered listeners
            document.querySelectorAll('.mark-delivered-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const card = btn.closest('.order-card');
                    if (!card) return;
                    const orderId = card.getAttribute('data-order-id');
                    btn.disabled = true;
                    fetch('/actions/activity/mark-delivered.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `order_id=${encodeURIComponent(orderId)}`
                    })
                        .then(res => res.json())
                        .then(result => {
                            if (result.success) {
                                const status = card.querySelector('.order-status');
                                if (status) {
                                    status.textContent = 'Delivered';
                                    status.classList.remove('not-delivered');
                                    status.classList.add('delivered');
                                }
                                btn.remove();
                            } else {
                                btn.disabled = false;
                                alert(result.error || 'Failed to mark as delivered.');
                            }
                        })
                        .catch(() => {
                            btn.disabled = false;
                            alert('Failed to mark as delivered.');
                        });
                });
            });
        });
});
