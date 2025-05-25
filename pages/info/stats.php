<?php
require_once __DIR__ . '/../../templates/home.tpl.php';
require_once __DIR__ . '/../../database/session.php';

$session = Session::getInstance();
$user = $session->getUser();
if (!$user) {
    header('Location: /');
    exit();
}

drawHeader($user);
?>
<link rel="stylesheet" href="../../css/main.css">
<main class="admin-panel-container">
    <h2 class="admin-title" style="color:var(--green)">Your Freelancer Stats</h2>
    <div class="stat-row">
        <div class="stat-box stat-box--green">
            <div class="stat-label stat-label--green">Total Earnings</div>
            <div class="stat-value stat-value--green" id="stat-earnings">&mdash;</div>
        </div>
        <div class="stat-box stat-box--green">
            <div class="stat-label stat-label--green">Completed Services</div>
            <div class="stat-value stat-value--green" id="stat-completed">&mdash;</div>
        </div>
        <div class="stat-box stat-box--green">
            <div class="stat-label stat-label--green">Current Listings</div>
            <div class="stat-value stat-value--green" id="stat-listings">&mdash;</div>
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
    const res = await fetch('../../actions/activity/get-user-stats.php');
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
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        },
        data: {
            labels: labels,
            datasets: [{
                label: 'Earnings (€)',
                data: values,
                borderColor: getComputedStyle(document.documentElement).getPropertyValue('--green').trim() || '#3bb77e',
                backgroundColor: 'rgba(61, 217, 188, 0.15)', // light green fill
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                pointBackgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--green').trim() || '#3bb77e',
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
<?php 
drawFooter($user);
 ?>
