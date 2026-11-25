<?php

/**
 * execute a prepared SQL statement securely with parameter binding (SQL injection prevention)
 * @param PDO $pdo active PDO connection instance
 * @param string $sql SQL query string
 * @param array $parameters associative array of bound parameters
 * @return PDOStatement
 */
function query($pdo, $sql, $parameters = []) {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($parameters);
    return $stmt;
}

/**
 * count total rows in a table with optional WHERE criteria
 * @param PDO $pdo active PDO connection instance
 * @param string $table target table name
 * @param string $where optional WHERE clause (e.g. 'status = :st')
 * @param array $parameters parameters for the WHERE clause
 * @return int total row count
 */
function total($pdo, $table, $where = '', $parameters = []) {
    $sql = "SELECT COUNT(*) FROM `{$table}`";
    if (!empty($where)) {
        $sql .= " WHERE {$where}";
    }
    $stmt = query($pdo, $sql, $parameters);
    return (int) $stmt->fetchColumn();
}

/**
 * fetch all records from a table with optional ordering (read all)
 * @param PDO $pdo active PDO connection instance
 * @param string $table target table name
 * @param string $orderBy optional sorting criteria (e.g. 'event_date ASC')
 * @return array array of associative rows
 */
function findAll($pdo, $table, $orderBy = '') {
    $sql = "SELECT * FROM `{$table}`";
    if (!empty($orderBy)) {
        $sql .= " ORDER BY {$orderBy}";
    }
    $stmt = query($pdo, $sql);
    return $stmt->fetchAll();
}

/**
 * retrieve a single record by primary key (read by ID)
 * @param PDO $pdo active PDO connection instance
 * @param string $table target table name
 * @param string $primaryKey primary key column name (defaults to 'id')
 * @param mixed $value key value to match
 * @return array|false record associative array or false if not found
 */
function findById($pdo, $table, $primaryKey, $value) {
    $sql = "SELECT * FROM `{$table}` WHERE `{$primaryKey}` = :value LIMIT 1";
    $stmt = query($pdo, $sql, [':value' => $value]);
    return $stmt->fetch();
}

/**
 * generic record insertion (create)
 * dynamically extracts column names and constructs named placeholders
 * @param PDO $pdo active PDO connection instance
 * @param string $table target table name
 * @param array $fields associative array of column => value pairs
 * @return string ID of newly created record (AUTO_INCREMENT)
 */
function insert($pdo, $table, $fields) {
    $columns = array_keys($fields);
    $placeholders = array_map(function($col) {
        return ':' . $col;
    }, $columns);

    $sql = "INSERT INTO `{$table}` (" . implode(', ', array_map(function($col) {
        return "`{$col}`";
    }, $columns)) . ") VALUES (" . implode(', ', $placeholders) . ")";

    $parameters = [];
    foreach ($fields as $col => $val) {
        $parameters[':' . $col] = $val;
    }

    query($pdo, $sql, $parameters);
    return $pdo->lastInsertId();
}

/**
 * generic record update by primary key (update)
 * @param PDO $pdo active PDO connection instance
 * @param string $table target table name
 * @param string $primaryKey primary key column name
 * @param array $fields associative array of column => value pairs (including primary key)
 * @return bool true on success
 */
function update($pdo, $table, $primaryKey, $fields) {
    $primaryKeyValue = $fields[$primaryKey];
    unset($fields[$primaryKey]);

    $setClauses = [];
    $parameters = [];
    foreach ($fields as $col => $val) {
        $setClauses[] = "`{$col}` = :{$col}";
        $parameters[':' . $col] = $val;
    }
    $parameters[':pk_val'] = $primaryKeyValue;

    $sql = "UPDATE `{$table}` SET " . implode(', ', $setClauses) . " WHERE `{$primaryKey}` = :pk_val";
    query($pdo, $sql, $parameters);
    return true;
}

/**
 * generic record deletion by primary key (delete)
 * @param PDO $pdo active PDO connection instance
 * @param string $table target table name
 * @param string $primaryKey primary key column name
 * @param mixed $id primary key value to delete
 * @return bool true on success
 */
function delete($pdo, $table, $primaryKey, $id) {
    $sql = "DELETE FROM `{$table}` WHERE `{$primaryKey}` = :id";
    query($pdo, $sql, [':id' => $id]);
    return true;
}

/**
 * fetch events with full relational details (3-table INNER JOIN across events, organisers, venues)
 * supports category filtering, keyword search, and record limits
 */
function getEventsWithDetails($pdo, $category = '', $search = '', $limit = null) {
    $sql = "SELECT 
                e.*,
                o.full_name AS organiser_name,
                o.email AS organiser_email,
                o.logo_url AS organiser_logo,
                v.name AS venue_name,
                v.city AS venue_city,
                v.address AS venue_address,
                v.capacity AS venue_capacity
            FROM `events` e
            INNER JOIN `organisers` o ON e.organiser_id = o.id
            INNER JOIN `venues` v ON e.venue_id = v.id
            WHERE 1=1";
    
    $params = [];

    if (!empty($category)) {
        $sql .= " AND e.category = :category";
        $params[':category'] = $category;
    }

    if (!empty($search)) {
        $sql .= " AND (e.title LIKE :search1 OR e.description LIKE :search2 OR v.city LIKE :search3)";
        $params[':search1'] = '%' . $search . '%';
        $params[':search2'] = '%' . $search . '%';
        $params[':search3'] = '%' . $search . '%';
    }

    $sql .= " ORDER BY e.event_date ASC, e.start_time ASC";

    if ($limit !== null && (int)$limit > 0) {
        $sql .= " LIMIT " . (int)$limit;
    }

    $stmt = query($pdo, $sql, $params);
    return $stmt->fetchAll();
}

/**
 * fetch single event details by ID with complete organiser & venue profiles (3-table JOIN)
 */
function getEventByIdWithDetails($pdo, $id) {
    $sql = "SELECT 
                e.*,
                o.full_name AS organiser_name,
                o.email AS organiser_email,
                o.phone AS organiser_phone,
                o.website AS organiser_website,
                o.bio AS organiser_bio,
                o.logo_url AS organiser_logo,
                v.name AS venue_name,
                v.address AS venue_address,
                v.city AS venue_city,
                v.capacity AS venue_capacity,
                v.contact_person AS venue_contact,
                v.image_url AS venue_image
            FROM `events` e
            INNER JOIN `organisers` o ON e.organiser_id = o.id
            INNER JOIN `venues` v ON e.venue_id = v.id
            WHERE e.id = :id
            LIMIT 1";
    
    $stmt = query($pdo, $sql, [':id' => $id]);
    return $stmt->fetch();
}

/**
 * retrieve all organisers with their scheduled events (one-to-many relationship)
 */
function getOrganisersWithEvents($pdo) {
    $organisers = findAll($pdo, 'organisers', 'full_name ASC');
    foreach ($organisers as &$org) {
        $sql = "SELECT id, title, event_date, category, status 
                FROM `events` 
                WHERE organiser_id = :org_id 
                ORDER BY event_date ASC";
        $stmt = query($pdo, $sql, [':org_id' => $org['id']]);
        $org['events'] = $stmt->fetchAll();
        $org['event_count'] = count($org['events']);
    }
    unset($org);
    return $organisers;
}

/**
 * retrieve all venues with events hosted at each venue (one-to-many relationship)
 */
function getVenuesWithEvents($pdo) {
    $venues = findAll($pdo, 'venues', 'name ASC');
    foreach ($venues as &$ven) {
        $sql = "SELECT id, title, event_date, start_time, category, status 
                FROM `events` 
                WHERE venue_id = :ven_id 
                ORDER BY event_date ASC";
        $stmt = query($pdo, $sql, [':ven_id' => $ven['id']]);
        $ven['events'] = $stmt->fetchAll();
        $ven['event_count'] = count($ven['events']);
    }
    unset($ven);
    return $venues;
}

/**
 * retrieve distinct event categories for filter navigation
 */
function getCategories($pdo) {
    $sql = "SELECT DISTINCT category FROM `events` ORDER BY category ASC";
    $stmt = query($pdo, $sql);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

/**
 * secure image file upload handler
 * validates file extension, restricts max filesize (5MB), and generates safe unique filenames
 * @param string $fileInputName input file field name from $_FILES
 * @param string $currentUrl existing image path (fallback when no new file is selected)
 * @param array &$errors reference array collecting validation error messages
 * @return string relative image path (e.g. 'uploads/event_16789.jpg') or $currentUrl
 */
function handleImageUpload($fileInputName, $currentUrl = '', &$errors = []) {
    $currentUrl = str_replace('assests/', 'assets/', (string)$currentUrl);
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES[$fileInputName];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExtensions)) {
            $errors[] = 'Invalid image format. Allowed formats: JPG, PNG, WEBP, GIF.';
            return $currentUrl;
        }

        // restrict maximum file size to 5MB
        if ($file['size'] > 5 * 1024 * 1024) {
            $errors[] = 'Image size exceeds maximum limit of 5MB.';
            return $currentUrl;
        }

        // create uploads directory if it does not already exist
        $targetDir = __DIR__ . '/../uploads/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // generate safe randomized filename to prevent overwriting
        $uniqueName = 'event_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
        $targetFile = $targetDir . $uniqueName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return 'uploads/' . $uniqueName;
        } else {
            $errors[] = 'Failed to upload image to server directory.';
            return $currentUrl;
        }
    }

    return $currentUrl;
}

function formatDate($dateString) {
    if (empty($dateString)) return '';
    $dt = new DateTime($dateString);
    return $dt->format('M j, Y');
}

function formatDayAndMonth($dateString) {
    if (empty($dateString)) return ['day' => '', 'month' => '', 'year' => ''];
    $dt = new DateTime($dateString);
    return [
        'day' => $dt->format('d'),
        'month' => strtoupper($dt->format('M')),
        'year' => $dt->format('Y')
    ];
}

function formatTime($timeString) {
    if (empty($timeString)) return '';
    $dt = new DateTime($timeString);
    return $dt->format('H:i');
}

function formatPrice($price) {
    $val = (float)$price;
    if ($val <= 0.0) {
        return 'Free Admission';
    }
    return '£' . number_format($val, 2);
}

/**
 * sanitize output strings to prevent cross-site scripting (XSS) attacks,
 * and automatically normalize legacy 'assests/' folder typo to 'assets/'
 */
function e($str) {
    $normalized = str_replace('assests/', 'assets/', (string)$str);
    return htmlspecialchars($normalized, ENT_QUOTES, 'UTF-8');
}

