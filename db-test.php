<?php
declare(strict_types=1);

require_once __DIR__ . '/api/config.php';

header('Content-Type: text/plain; charset=utf-8');

try {
    $pdo = db();

    echo "Database connection successful.\n";
    echo "Database: fightclubsa\n";

    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "Tables found: " . count($tables) . "\n";

    foreach ($tables as $table) {
        echo "- " . $table . "\n";
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo "Database connection failed.\n";
    echo $e->getMessage();
}