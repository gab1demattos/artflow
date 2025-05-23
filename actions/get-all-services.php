<?php
// Returns JSON: [ {id, title, owner, category, ...} ... ]
require_once(__DIR__ . '/../database/session.php');
$session = Session::getInstance();
$user = $session->getUser() ?? null;
if (!$user || $user['user_type'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit();
}
// TODO: Fetch all services from DB
