<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: manage.php');
    exit();
}

try {
    include __DIR__ . '/includes/DatabaseConnection.php';
    include __DIR__ . '/includes/DatabaseFunctions.php';

    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if ($id > 0) {
        $event = findById($connection, 'events', 'id', $id);
        $title = $event ? $event['title'] : "Event #{$id}";

        // execute deletion via reusable generic function
        delete($connection, 'events', 'id', $id);

        $_SESSION['flash_message'] = [
            'type' => 'success',
            'text' => 'Event "' . $title . '" was permanently deleted from the database.'
        ];
    } else {
        $_SESSION['flash_message'] = [
            'type' => 'error',
            'text' => 'Invalid event ID provided for deletion.'
        ];
    }

} catch (PDOException $e) {
    $_SESSION['flash_message'] = [
        'type' => 'error',
        'text' => 'Unable to delete event: ' . $e->getMessage()
    ];
}

header('Location: manage.php');
exit();
