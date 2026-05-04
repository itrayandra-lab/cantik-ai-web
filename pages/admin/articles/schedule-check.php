<?php
/**
 * Schedule Check - Auto-publish scheduled articles
 * Bisa dipanggil via cron job: * * * * * php /path/to/admin/articles/schedule-check.php
 */
require_once __DIR__ . '/../config/database.php';

$pdo = getDB();
$stmt = $pdo->prepare("UPDATE articles SET status='published', published_at=NOW() WHERE status='scheduled' AND scheduled_at <= NOW()");
$stmt->execute();
$count = $stmt->rowCount();

if ($count > 0) {
    echo date('Y-m-d H:i:s') . " - Published $count scheduled article(s)\n";
}
