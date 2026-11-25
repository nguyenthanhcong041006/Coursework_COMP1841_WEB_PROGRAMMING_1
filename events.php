<?php

try {
    include __DIR__ . '/includes/DatabaseConnection.php';
    include __DIR__ . '/includes/DatabaseFunctions.php';

    // parse search query and category filter parameters from GET
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $selectedCategory = isset($_GET['category']) ? trim($_GET['category']) : '';

    // fetch event records with complete Organiser & Venue data (3-table JOIN)
    $events = getEventsWithDetails($connection, $selectedCategory, $search);

    // fetch unique categories to populate dropdowns and category filter pills
    $categories = getCategories($connection);

    $title = 'Events';

    ob_start();
    include __DIR__ . '/templates/events.html.php';
    $output = ob_get_clean();

} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = '<div class="alert alert-error">Database error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</div>';
}

include __DIR__ . '/templates/layout.html.php';
