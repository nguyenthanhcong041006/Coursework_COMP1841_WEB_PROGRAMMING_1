<?php
try {
    // __
    include __DIR__ . '/includes/DatabaseConnection.php';
    include __DIR__ . '/includes/DatabaseFunctions.php';

    // gather system statistics and metrics
    $stats = [
        'events_count' => total($connection, 'events'),
        'organisers_count' => total($connection, 'organisers'),
        'venues_count' => total($connection, 'venues'),
    ];

    // fetch the top 3 upcoming highlighted events (3-table relational JOIN)
    $featuredEvents = getEventsWithDetails($connection, '', '', 3);

    // fetch unique categories for quick navigation shortcuts
    $categories = getCategories($connection);

    $title = 'Home';

    ob_start();
    include __DIR__ . '/templates/home.html.php';
    $output = ob_get_clean();

} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = '<div class="alert alert-error">Database error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</div>';
}

include __DIR__ . '/templates/layout.html.php';
