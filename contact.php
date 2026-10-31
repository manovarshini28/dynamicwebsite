<?php
require __DIR__ . '/config.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['ok'=>false,'error'=>'POST only']); exit; }

$name  = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$msg   = trim($_POST['message'] ?? '');

if ($name === '' || $msg === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['ok'=>false,'error'=>'Please fill in your name, a valid email and a message.']); exit;
}
if (mb_strlen($name) > 80 || mb_strlen($msg) > 2000) {
    echo json_encode(['ok'=>false,'error'=>'That is a bit too long.']); exit;
}

try {
    $st = db()->prepare('INSERT INTO messages (name, email, message) VALUES (?, ?, ?)');
    $st->execute([$name, $email, $msg]);
    echo json_encode(['ok'=>true]);
} catch (Throwable $t) {
    http_response_code(500);
    echo json_encode(['ok'=>false,'error'=>'Database error. Is MySQL running?']);
}
