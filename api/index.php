<?php

// Ensure writable directories in /tmp for serverless environment
$dirs = [
    '/tmp/views',
    '/tmp/sessions',
    '/tmp/cache',
    '/tmp/logs',
    '/tmp/storage/app/public',
    '/tmp/storage/app/public/banners',
    '/tmp/storage/app/public/custom-options',
    '/tmp/storage/app/public/payments',
    '/tmp/storage/app/public/delivery-proofs',
    '/tmp/storage/app/public/settings',
    '/tmp/storage/app/public/categories',
];

foreach ($dirs as $dir) {
    if (! is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

putenv('FILESYSTEM_PUBLIC_ROOT=/tmp/storage/app/public');
$_ENV['FILESYSTEM_PUBLIC_ROOT'] = '/tmp/storage/app/public';
$_SERVER['FILESYSTEM_PUBLIC_ROOT'] = '/tmp/storage/app/public';

// Copy sqlite database to /tmp if needed
$sourceDb = __DIR__.'/../database/database.sqlite';
$targetDb = '/tmp/database.sqlite';

if (file_exists($sourceDb)) {
    if (! file_exists($targetDb) || filemtime($sourceDb) > filemtime($targetDb)) {
        copy($sourceDb, $targetDb);
    }
} elseif (! file_exists($targetDb)) {
    touch($targetDb);
}

// Forward to public/index.php
require __DIR__.'/../public/index.php';
