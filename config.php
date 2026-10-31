<?php
// XAMPP defaults: user "root", empty password. Change if yours differ.
const DB_HOST = 'localhost';
const DB_NAME = 'portfolio';
const DB_USER = 'root';
const DB_PASS = '';
const ADMIN_PASSWORD = 'change-me-123'; // CHANGE THIS before sharing the site

function db(): PDO {
    static $pdo = null;
    if (!$pdo) {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER, DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
    }
    return $pdo;
}

function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
