<?php

try {
    include __DIR__ . '/includes/DatabaseConnection.php';
    include __DIR__ . '/includes/DatabaseFunctions.php';

    // retrieve all organisers with their scheduled events (One-to-Many relational mapping)
    $organisers = getOrganisersWithEvents($connection);

    // retrieve all venues with events hosted at each location (One-to-Many relational mapping)
    $venues = getVenuesWithEvents($connection);

    $title = 'Organisers & Venues';

    ob_start();
    include __DIR__ . '/templates/organisers_venues.html.php';
    $output = ob_get_clean();

} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = '<div class="alert alert-error">Database error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</div>';
}

include __DIR__ . '/templates/layout.html.php';
