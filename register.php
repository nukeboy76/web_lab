<?php
require_once __DIR__.'/db.php';
$errors = [];
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['pass'] ?? '';
    $pass2 = $_POST['pass2'] ?? '';

    if ($name === '' || $email === '' || $pass === '' || $pass2 === '') {
        $errors[] = 'Заполните все поля.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Некорректный email.';
    } elseif ($pass !== $pass2) {
        $errors[] = 'Пароли не совпадают.';
    }

    if (!$errors) {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $mysqli = get_db_connection();
        $stmt = $mysqli->prepare('INSERT INTO users (name,email,password_hash) VALUES (?,?,?)');
        if(!$stmt){
            $errors[] = 'DB error';
        } else {
            $stmt->bind_param('sss', $name, $email, $hash);
            if($stmt->execute()) {
                $success = true;
            } else {
                $errors[] = 'Не удалось сохранить данные.';
            }
            $stmt->close();
        }
        $mysqli->close();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Регистрация</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h2>Регистрация</h2>
<?php if($success): ?>
<p>Регистрация успешна.</p>
<?php else: ?>
<?php if($errors): ?>
<ul>
<?php foreach($errors as $e): ?>
  <li><?=htmlspecialchars($e)?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
<form method="post" class="contacts" action="register.php">
  <label>Имя
    <input type="text" name="name" value="<?=htmlspecialchars($name ?? '')?>" required>
  </label>
  <label>Email
    <input type="email" name="email" value="<?=htmlspecialchars($email ?? '')?>" required>
  </label>
  <label>Пароль
    <input type="password" name="pass" required>
  </label>
  <label>Повторите пароль
    <input type="password" name="pass2" required>
  </label>
  <input type="submit" value="Зарегистрироваться">
</form>
<?php endif; ?>
</body>
</html>
