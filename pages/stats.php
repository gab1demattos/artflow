<?php
require_once __DIR__ . '/../templates/home.tpl.php';
require_once __DIR__ . '/../database/session.php';

$session = Session::getInstance();
$user = $session->getUser();
if (!$user) {
    header('Location: /');
    exit();
}

drawHeader($user);
?>
<link rel="stylesheet" href="/css/admin.css">
<main class="admin-panel-container">
    <h2 class="admin-title">Your Stats</h2>
    <div class="admin-stats-row">
        <div class="admin-stat-box">
            <div class="stat-label">Total Earnings</div>
            <div class="stat-value" id="stat-earnings">&mdash;</div>
        </div>
        <div class="admin-stat-box">
            <div class="stat-label">Completed Services</div>
            <div class="stat-value" id="stat-completed">&mdash;</div>
        </div>
        <div class="admin-stat-box">
            <div class="stat-label">Current Listings</div>
            <div class="stat-value" id="stat-listings">&mdash;</div>
        </div>
    </div>
    <div style="max-width:700px;margin:0 auto;">
        <h3 style="text-align:center;margin-bottom:1em;">Earnings Per Day</h3>
        <canvas id="earningsChart" height="120"></canvas>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
async function loadStats() {
    const res = await fetch('/actions/get-user-stats.php');
    const data = await res.json();
    document.getElementById('stat-earnings').textContent = data.total_earnings + '€';
    document.getElementById('stat-completed').textContent = data.completed_services;
    document.getElementById('stat-listings').textContent = data.current_listings;
    // Chart
    const ctx = document.getElementById('earningsChart').getContext('2d');
    const labels = data.earnings_per_day.map(e => e.date);
    const values = data.earnings_per_day.map(e => e.amount);
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Earnings (€)',
                data: values,
                borderColor: '#e6b800',
                backgroundColor: 'rgba(246,221,139,0.2)',
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                pointBackgroundColor: '#e6b800',
                pointBorderColor: '#fff',
                pointHoverRadius: 6
            }]
        },
        options: {
            scales: {
                x: { title: { display: true, text: 'Date' } },
                y: { title: { display: true, text: 'Earnings (€)' }, beginAtZero: true }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
}
loadStats();
</script>
<?php // Optionally, add a footer or close tags if needed ?>
