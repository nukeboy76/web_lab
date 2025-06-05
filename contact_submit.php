<?php
require_once __DIR__.'/db.php';
header('Content-Type: application/json; charset=utf-8');

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$topic = trim($_POST['topic'] ?? '');
$msg = trim($_POST['msg'] ?? '');
$doc = trim($_POST['doc'] ?? '');

$errors = [];
if ($name === '' || $email === '' || $topic === '' || $msg === '') {
    $errors[] = 'Заполните все обязательные поля.';
}
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Некорректный email.';
}
if ($errors) {
    http_response_code(400);
    echo json_encode(['error' => implode(' ', $errors)], JSON_UNESCAPED_UNICODE);
    exit;
}

$mysqli = get_db_connection();
$stmt = $mysqli->prepare('INSERT INTO feedback (created_at,name,email,topic,message,doc_url) VALUES (NOW(),?,?,?,?,?)');
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'DB error'], JSON_UNESCAPED_UNICODE);
    $mysqli->close();
    exit;
}
$stmt->bind_param('sssss', $name, $email, $topic, $msg, $doc);
$stmt->execute();
$stmt->close();
$mysqli->close();

echo json_encode(['ok' => true]);
