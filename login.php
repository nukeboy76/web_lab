<?php
require_once __DIR__.'/db.php';
session_start();
header('Content-Type: application/json');

$login = trim($_POST['login'] ?? '');
$pass  = $_POST['pass'] ?? '';

if ($login === '' || $pass === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Заполните все поля']);
    exit;
}

$mysqli = get_db_connection();
$stmt = $mysqli->prepare('SELECT id,name,password_hash FROM users WHERE email=?');
$stmt->bind_param('s', $login);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
$stmt->close();
$mysqli->close();

if ($user && password_verify($pass, $user['password_hash'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    echo json_encode(['ok' => true, 'name' => $user['name']]);
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Неверный логин или пароль']);
}
?>
