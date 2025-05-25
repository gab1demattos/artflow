<?php
require_once(__DIR__ . '/../database/database.php');
require_once(__DIR__ . '/../database/session.php');

header('Content-Type: application/json');
$session = Session::getInstance();
$user = $session->getUser() ?? null;
if (!$user) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit();
}

$db = Database::getInstance();
$userId = $user['id'];

// Total earnings (sum of price for completed orders as freelancer)
$stmt = $db->prepare('SELECT SUM(s.price) as total FROM Exchange e JOIN Service s ON e.service_id = s.id WHERE s.user_id = ? AND e.status = ?');
$stmt->execute([$userId, 'completed']);
$totalEarnings = floatval($stmt->fetchColumn() ?: 0);

// Number of completed services (as freelancer)
$stmt = $db->prepare('SELECT COUNT(*) FROM Exchange e JOIN Service s ON e.service_id = s.id WHERE s.user_id = ? AND e.status = ?');
$stmt->execute([$userId, 'completed']);
$completedServices = intval($stmt->fetchColumn());

// Number of current listings (active services)
$stmt = $db->prepare('SELECT COUNT(*) FROM Service WHERE user_id = ?');
$stmt->execute([$userId]);
$currentListings = intval($stmt->fetchColumn());

// Earnings per day (date, sum of price for completed orders as freelancer)
$stmt = $db->prepare('SELECT DATE(e.date) as day, SUM(s.price) as amount FROM Exchange e JOIN Service s ON e.service_id = s.id WHERE s.user_id = ? AND e.status = ? GROUP BY day ORDER BY day ASC');
$stmt->execute([$userId, 'completed']);
$earningsPerDay = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Format earningsPerDay as array of ['date' => ..., 'amount' => ...]
$earningsPerDay = array_map(function($row) {
    return [
        'date' => $row['day'],
        'amount' => floatval($row['amount'])
    ];
}, $earningsPerDay);

// Output
echo json_encode([
    'success' => true,
    'total_earnings' => $totalEarnings,
    'completed_services' => $completedServices,
    'current_listings' => $currentListings,
    'earnings_per_day' => $earningsPerDay
]);
