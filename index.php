<?php
    $redirect = 'pages';

    if (isset($_GET['signup'])) {
        $redirect .= '?signup=' . urlencode($_GET['signup']);
        if (isset($_GET['username'])) {
            $redirect .= '&username=' . urlencode($_GET['username']);
        }
    }

    header('Location: ' . $redirect);
    exit;
?>