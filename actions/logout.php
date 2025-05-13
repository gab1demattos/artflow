<?php
declare(strict_types=1);
require_once(__DIR__ . '/../includes/session.php');

$session = Session::getInstance();
$session->logout();

header('Location: ../index.php');
exit();
?>