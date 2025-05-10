<?php

require_once(__DIR__ . '/../config/config.php');
function connectToDB(): PDO
{
    try {
        $db = new PDO(DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        die("Ошибка подключения к базе данных: " . $e->getMessage());
    }

    return $db;
}

function execute(PDO $db, string $sql, array $valies) : bool
{
    $stmt = $db->prepare($sql);
    return $stmt->execute($valies);
}