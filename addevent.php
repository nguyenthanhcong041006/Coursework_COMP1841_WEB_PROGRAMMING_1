<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    include __DIR__ . '/includes/DatabaseConnection.php';
    include __DIR__ . '/includes/DatabaseFunctions.php';

    $errors = [];
    $isEdit = false;

    // retrieve organisers and venues to populate foreign key select options
    $organisers = findAll($connection, 'organisers', 'full_name ASC');
    $venues = findAll($connection, 'venues', 'name ASC');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // process uploaded cover image file (file upload only)
        $uploadedImageUrl = handleImageUpload('image_file', '', $errors);

        // collect and sanitize input fields
        $event = [
            // trim() removes whitespace from the beginning and end of a string
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'event_date' => trim($_POST['event_date'] ?? ''),
            'start_time' => trim($_POST['start_time'] ?? ''),
            'end_time' => trim($_POST['end_time'] ?? ''),
            'venue_id' => (int)($_POST['venue_id'] ?? 0),
            'organiser_id' => (int)($_POST['organiser_id'] ?? 0),
            'category' => trim($_POST['category'] ?? ''),
            'image_url' => $uploadedImageUrl,
            'ticket_price' => (float)($_POST['ticket_price'] ?? 0.00),
            'status' => trim($_POST['status'] ?? 'Upcoming'),
        ];

        // server-side validation
        // empty() checks if a variable is empty (null, '', 0, '0', false, array())
        if (empty($event['title'])) {
            $errors[] = 'Event title cannot be empty.';
        }
        if (empty($event['description'])) {
            $errors[] = 'Event description is required.';
        }
        if (empty($event['event_date'])) {
            $errors[] = 'Please provide a valid event date.';
        }
        if (empty($event['start_time']) || empty($event['end_time'])) {
            $errors[] = 'Start time and end time are required.';
        }
        if ($event['start_time'] >= $event['end_time']) {
            $errors[] = 'End time must be after the start time.';
        }
        if ($event['venue_id'] <= 0) {
            $errors[] = 'Please select a valid venue.';
        }
        if ($event['organiser_id'] <= 0) {
            $errors[] = 'Please select a valid organiser.';
        }
        if (empty($event['category'])) {
            $errors[] = 'Category cannot be empty.';
        }
        if (empty($event['image_url'])) {
            $errors[] = 'Please upload a cover image file for the event.';
        }

        // if validation passes -> execute insert via generic function
        if (empty($errors)) {
            $newId = insert($connection, 'events', $event);

            $_SESSION['flash_message'] = [
                'type' => 'success',
                'text' => 'Event "' . $event['title'] . '" was successfully created and published (ID #' . $newId . ')!'
            ];

            header('Location: manage.php');
            exit();
        }
    } else {
        // default initial values for add event form (blank image for user upload)
        $event = [
            'title' => '',
            'description' => '',
            'event_date' => date('Y-m-d', strtotime('+7 days')),
            'start_time' => '10:00',
            'end_time' => '16:00',
            'venue_id' => '',
            'organiser_id' => '',
            'category' => '',
            'image_url' => '',
            'ticket_price' => '0.00',
            'status' => 'Upcoming',
        ];
    }

    $title = 'Add Event';

    ob_start();
    include __DIR__ . '/templates/event_form.html.php';
    $output = ob_get_clean();

} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = '<div class="alert alert-error">Database error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</div>';
}

include __DIR__ . '/templates/layout.html.php';
