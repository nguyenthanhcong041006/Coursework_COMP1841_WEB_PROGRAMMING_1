<?php

try {
    include __DIR__ . '/includes/DatabaseConnection.php';
    include __DIR__ . '/includes/DatabaseFunctions.php';

    $errors = [];
    $successMessage = '';
    $formData = [
        'full_name' => '',
        'name' => '',
        'email' => '',
        'subject' => 'Event Inquiry / Question',
        'event_id' => isset($_GET['event_id']) ? (int)$_GET['event_id'] : '',
        'message' => '',
    ];

    // retrieve event list to populate related event dropdown options
    $eventsList = findAll($connection, 'events', 'event_date ASC');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fullName = trim($_POST['full_name'] ?? $_POST['name'] ?? '');
        $formData['full_name'] = $fullName;
        $formData['name'] = $fullName;
        $formData['email'] = trim($_POST['email'] ?? '');
        $formData['subject'] = trim($_POST['subject'] ?? '');
        $formData['event_id'] = !empty($_POST['event_id']) ? (int)$_POST['event_id'] : null;
        $formData['message'] = trim($_POST['message'] ?? '');

        // server-side validation
        if (empty($formData['full_name'])) {
            $errors[] = 'Please provide your full name.';
        }
        if (empty($formData['email']) || !filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email address is required so we can reply to you.';
        }
        if (empty($formData['subject'])) {
            $errors[] = 'Please select a subject for your inquiry.';
        }
        if (empty($formData['message']) || strlen($formData['message']) < 10) {
            $errors[] = 'Your message must be at least 10 characters in length.';
        }

        // if validation passes -> save inquiry to contact_messages table and dispatch email
        if (empty($errors)) {
            $messageData = [
                'full_name' => $formData['full_name'],
                'email' => $formData['email'],
                'subject' => $formData['subject'],
                'event_id' => $formData['event_id'],
                'message' => $formData['message'],
                'status' => 'Unread'
            ];

            insert($connection, 'contact_messages', $messageData);

            // dispatch notification email to system administrator
            $to = 'admin@greenwichbulletin.local';
            $subjectLine = '[Greenwich Event Bulletin] ' . $formData['subject'];
            $emailBody = "New message received from: {$formData['full_name']} ({$formData['email']})\n\n"
                       . "Event ID: " . ($formData['event_id'] ? '#' . $formData['event_id'] : 'General') . "\n"
                       . "Subject: {$formData['subject']}\n\n"
                       . "Message:\n{$formData['message']}\n";
            $headers = "From: webmaster@greenwichbulletin.local\r\n"
                     . "Reply-To: {$formData['email']}\r\n"
                     . "X-Mailer: PHP/" . phpversion();

            @mail($to, $subjectLine, $emailBody, $headers);

            $successMessage = 'Thank you, ' . $formData['full_name'] . '! Your message has been successfully submitted to the system administrator.';

            // reset form fields after successful submission
            $formData = [
                'full_name' => '',
                'name' => '',
                'email' => '',
                'subject' => 'Event Inquiry / Question',
                'event_id' => '',
                'message' => '',
            ];
        }
    }

    $title = 'Contact';

    ob_start();
    include __DIR__ . '/templates/contact.html.php';
    $output = ob_get_clean();

} catch (PDOException $e) {
    $title = 'An error has occurred';
    $output = '<div class="alert alert-error">Database error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</div>';
}

include __DIR__ . '/templates/layout.html.php';
