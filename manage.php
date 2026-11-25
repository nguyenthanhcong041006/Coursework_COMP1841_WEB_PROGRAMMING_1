<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    include __DIR__ . '/includes/DatabaseConnection.php';
    include __DIR__ . '/includes/DatabaseFunctions.php';

    // retrieve flash notification from session (after Add/Edit/Delete action)
    $flashMessage = $_SESSION['flash_message'] ?? null;
    unset($_SESSION['flash_message']);

    // retrieve all events with full details (3-table JOIN for Organiser & Venue names)
    $events = getEventsWithDetails($connection);

    $title = 'Manage Events';

    ob_start();
    include __DIR__ . '/templates/manage_events.html.php';
    $output = ob_get_clean();

} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = '<div class="alert alert-error">Database error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</div>';
}

include __DIR__ . '/templates/layout.html.php';
