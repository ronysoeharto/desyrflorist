<?php

// Ensure writable directories in /tmp for serverless environment
$dirs = [
    '/tmp/views',
    '/tmp/sessions',
    '/tmp/cache',
    '/tmp/logs',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Copy sqlite database to /tmp if needed
$sourceDb = __DIR__ . '/../database/database.sqlite';
$targetDb = '/tmp/database.sqlite';

if (file_exists($sourceDb)) {
    if (! file_exists($targetDb) || filemtime($sourceDb) > filemtime($targetDb)) {
        copy($sourceDb, $targetDb);
    }
} elseif (! file_exists($targetDb)) {
    touch($targetDb);
}

// Forward to public/index.php
require __DIR__ . '/../public/index.php';
