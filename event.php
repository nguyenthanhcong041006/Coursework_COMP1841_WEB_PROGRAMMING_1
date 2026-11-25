<?php

try {
    include __DIR__ . '/includes/DatabaseConnection.php';
    include __DIR__ . '/includes/DatabaseFunctions.php';

    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($id <= 0) {
        header('Location: events.php');
        exit();
    }

    // fetch event details with complete Organiser and Venue profiles (3-table JOIN)
    $event = getEventByIdWithDetails($connection, $id);

    if (!$event) {
        $title = 'Event Not Found';
        $output = '
        <div class="container" style="padding: 5rem 0; text-align: center;">
            <div style="background: #fff; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 4rem 2rem; max-width: 600px; margin: 0 auto;">
                <h1 style="font-family: var(--font-heading); font-size: 1.75rem; margin-bottom: 1rem;">Event Not Found</h1>
                <p style="color: var(--text-muted); margin-bottom: 2rem;">The event bulletin you are looking for does not exist or may have been deleted.</p>
                <a href="events.php" class="btn btn-primary">Browse Events Directory</a>
            </div>
        </div>';
        include __DIR__ . '/templates/layout.html.php';
        exit();
    }

    $title = 'Event Details';

    ob_start();
    include __DIR__ . '/templates/event_details.html.php';
    $output = ob_get_clean();

} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = '<div class="alert alert-error">Database error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</div>';
}

include __DIR__ . '/templates/layout.html.php';
