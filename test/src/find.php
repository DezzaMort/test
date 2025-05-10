<?php

header('Content-Type: application/json');

require_once('../config/config.php');
require_once('../src/db.php');

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if (strlen($query) < 3) {
    echo json_encode([]);
    exit;
}

try {
    $db = connectToDb();

    $sql = 'SELECT p.title, c.body
            FROM posts p
            JOIN comments c ON c.post_id = p.id
            WHERE c.body LIKE :query';

    $stmt = $db->prepare($sql);
    $stmt->execute(['query' => '%' . $query . '%']);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as &$item) {
        $lines = explode("\n", $item['body']);
        $filtered = array_filter($lines, fn($line) => stripos($line, $query) !== false);
        $item['body'] = implode("\n", $filtered);
    }

    echo json_encode($results);
} catch (Exception $e) {
    echo json_encode([]);
}
