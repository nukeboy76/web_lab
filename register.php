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
  <script defer src="js/main.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header>
  <div class="logo"><img src="images/logo.jpg" alt="Логотип"></div>
  <div class="site-title">ГАЛАКТИКА</div>
  <form id="login-form">
    <input type="text" name="login" placeholder="логин" required>
    <input type="password" name="pass" placeholder="пароль" required>
    <input type="submit" value="Войти">
  </form>
</header>
<nav class="top">
  <ul class="nav-links">
    <li><a href="index.html">Главная</a></li>
    <li><a href="about.html">О нас</a></li>
    <li><a href="catalog.html">Каталог</a></li>
    <li><a href="contacts.html">Контакты</a></li>
    <li><a href="guestbook.php">Отзывы</a></li>
    <li><a href="register.php">Регистрация</a></li>
  </ul>
  <form id="site-search" method="get" action="search.php">
    <input type="text" name="q" placeholder="поиск">
    <input type="submit" value="искать">
  </form>
</nav>
<main>
  <aside class="sidebar-left">
    <ul>
      <li><a href="index.html">Страница 1</a></li>
      <li><a href="about.html">Страница 2</a></li>
      <li><a href="catalog.html">Страница 3</a></li>
      <li><a href="contacts.html">Страница 4</a></li>
    </ul>
  </aside>

  <article>
    <h2>Регистрация</h2>
    <?php if($success): ?>
      <p class="notice">Регистрация прошла успешно.</p>
      <script>
        localStorage.setItem('user', <?=json_encode($name)?>);
        setTimeout(function(){ location.href='index.html'; }, 2000);
      </script>
    <?php else: ?>
      <?php if($errors): ?>
      <ul>
      <?php foreach($errors as $e): ?>
        <li><?=htmlspecialchars($e)?></li>
      <?php endforeach; ?>
      </ul>
      <?php endif; ?>
      <form method="post" class="contacts" action="register.php">
        <label><span>Имя</span>
          <input type="text" name="name" value="<?=htmlspecialchars($name ?? '')?>" required>
        </label>
        <label><span>Email</span>
          <input type="email" name="email" value="<?=htmlspecialchars($email ?? '')?>" required>
        </label>
        <label><span>Пароль</span>
          <input type="password" name="pass" required>
        </label>
        <label><span>Повторите пароль</span>
          <input type="password" name="pass2" required>
        </label>
        <input type="submit" value="Зарегистрироваться">
      </form>
    <?php endif; ?>
  </article>

  <aside class="sidebar-right">
    <div class="banner">
      <a href="https://www.salesforce.com/crm/what-is-crm/" target="_blank">
        <img src="https://www.techmatrixconsulting.com/images/services/crm-banner-mob.jpg" alt="Salesforce CRM">
      </a>
    </div>
    <div class="banner">
      <a href="https://developer.android.com/" target="_blank">
        <img src="https://i.pinimg.com/236x/58/52/82/585282f0fa2543021550dd06f145a073.jpg" alt="Android Mobile Development">
      </a>
    </div>
    <div class="banner">
      <a href="https://aws.amazon.com/products/compute/" target="_blank">
        <img src="https://avatars.mds.yandex.net/i?id=87ab1b5f1ad3f72f5e0d0c4d5ccce48ff0c3a698-4955727-images-thumbs&n=13" alt="AWS Cloud Compute">
      </a>
    </div>
    <p class="banner-note">(кликните на баннер для перехода к товару)</p>
  </aside>
</main>
<footer id="footer">
  &copy; 2025 ООО «Галактика». Все права защищены
</footer>
</body>
</html>
