<?php

// Constanten (connectie-instellingen databank)
define('DB_HOST', 'localhost');
define('DB_USER', 'thibaultviaene');
define('DB_PASS', 'Azerty123!');
define('DB_NAME', 'thibault_viaene_portfolio');


date_default_timezone_set('Europe/Brussels');

// Verbinding maken met de databank
try {
    $db = new PDO('mysql:host=' . DB_HOST .';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection Error: ' .  $e->getMessage();
    exit;
}

// Opvragen van alle taken uit de tabel tasks
$stmt = $db->prepare('SELECT * FROM messages2 ORDER BY added_on DESC');
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);


?><!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <title>My Messages</title>
</head>
<body>
    <?php if (sizeof($items) > 0) { ?>
    <ul>
        <?php foreach ($items as $item) { ?>
        <li><?php echo htmlentities($item['Name']); ?>: <?php echo htmlentities($item['Via']); ?> (<?php echo (new Datetime($item['added_on']))->format('d-m-Y H:i:s'); ?>)</li>
        <?php } ?>
    </ul>
    <?php
    } else {
        echo '<p>No messages yet.</p>' . PHP_EOL;
    }
    ?>
</body>