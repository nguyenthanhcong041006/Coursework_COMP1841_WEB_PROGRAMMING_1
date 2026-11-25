<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    include __DIR__ . '/includes/DatabaseConnection.php';
    include __DIR__ . '/includes/DatabaseFunctions.php';

    $id = isset($_GET['id']) ? (int)$_GET['id'] : (int)($_POST['id'] ?? 0);

    if ($id <= 0) {
        header('Location: manage.php');
        exit();
    }

    // verify if the event exists in the database
    $existingEvent = findById($connection, 'events', 'id', $id);
    if (!$existingEvent) {
        $_SESSION['flash_message'] = [
            'type' => 'error',
            'text' => 'Event not found in the database.'
        ];
        header('Location: manage.php');
        exit();
    }

    $errors = [];
    $isEdit = true;

    // retrieve organisers and venues to populate foreign key select options
    $organisers = findAll($connection, 'organisers', 'full_name ASC');
    $venues = findAll($connection, 'venues', 'name ASC');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // process uploaded cover image file (file upload only)
        $uploadedImageUrl = handleImageUpload('image_file', '', $errors);

        // use newly uploaded image if present; otherwise retain existing image
        $existingImage = trim($_POST['existing_image'] ?? $existingEvent['image_url']);
        $finalImageUrl = !empty($uploadedImageUrl) ? $uploadedImageUrl : $existingImage;

        $event = [
            'id' => $id,
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'event_date' => trim($_POST['event_date'] ?? ''),
            'start_time' => trim($_POST['start_time'] ?? ''),
            'end_time' => trim($_POST['end_time'] ?? ''),
            'venue_id' => (int)($_POST['venue_id'] ?? 0),
            'organiser_id' => (int)($_POST['organiser_id'] ?? 0),
            'category' => trim($_POST['category'] ?? ''),
            'image_url' => $finalImageUrl,
            'ticket_price' => (float)($_POST['ticket_price'] ?? 0.00),
            'status' => trim($_POST['status'] ?? 'Upcoming'),
        ];

        // server-side validation
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
            $errors[] = 'Please provide an image for the event.';
        }

        // if validation passes -> execute update via generic function
        if (empty($errors)) {
            update($connection, 'events', 'id', $event);

            $_SESSION['flash_message'] = [
                'type' => 'success',
                'text' => 'Event "' . $event['title'] . '" was successfully updated!'
            ];

            header('Location: manage.php');
            exit();
        }
    } else {
        // populate form with existing event data from database
        $event = $existingEvent;
    }

    $title = 'Edit Event';

    ob_start();
    include __DIR__ . '/templates/event_form.html.php';
    $output = ob_get_clean();

} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = '<div class="alert alert-error">Database error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</div>';
}

include __DIR__ . '/templates/layout.html.php';
