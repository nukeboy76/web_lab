<?php
require_once __DIR__.'/db.php';

$mysqli = get_db_connection();
header('Cache-Control: no-store');

$format = $_GET['format'] ?? '';
$posted = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $comment = trim($_POST['comment'] ?? '');
    $rating = intval($_POST['rating'] ?? 0);
    $subscribe = isset($_POST['subscribe']) ? 1 : 0;
    $city = trim($_POST['city'] ?? '');
    $product = trim($_POST['product'] ?? '');

    if ($name === '' || $comment === '') {
        $errors[] = 'Заполните имя и комментарий.';
    }
    if ($rating < 1 || $rating > 5) {
        $errors[] = 'Некорректный рейтинг.';
    }

    if (!$errors) {
        $stmt = $mysqli->prepare('INSERT INTO guestbook (created_at,name,city,rating,subscribe,product,comment) VALUES (NOW(),?,?,?,?,?,?)');
        if ($stmt) {
            $stmt->bind_param('ssiiss', $name, $city, $rating, $subscribe, $product, $comment);
            $stmt->execute();
            $stmt->close();
            $posted = true;
        } else {
            $errors[] = 'DB error';
        }
    }

    if ($format === 'json') {
        header('Content-Type: application/json; charset=utf-8');
        if ($errors) {
            http_response_code(400);
            echo json_encode(['error' => implode(' ', $errors)], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['ok' => true]);
        }
        $mysqli->close();
        exit;
    }
}

$res = $mysqli->query('SELECT created_at,name,city,rating,subscribe,product,comment FROM guestbook ORDER BY created_at DESC');
$entries = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

if ($format === 'json') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($entries, JSON_UNESCAPED_UNICODE);
    $mysqli->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Гостевая книга</title>
  <link rel="stylesheet" href="css/style.css">
  <script defer src="js/main.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header>
  <div class="logo">
    <img src="images/logo.jpg" alt="Логотип Галактики">
  </div>
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
    <li><a href="register.html">Регистрация</a></li>
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
    <h2>Гостевая книга</h2>
    <?php if($posted): ?><p>Спасибо за отзыв!</p><?php endif; ?>
    <form method="post" action="guestbook.php?format=json" class="contacts" id="guestbook-form">
      <label><span>Имя</span>
        <input type="text" name="name" required>
      </label>
      <label><span>Город</span>
        <input type="text" name="city">
      </label>
      <label><span>Любимый продукт</span>
        <select name="product">
          <option value="CRM‑PRO">CRM‑PRO</option>
          <option value="Mobile‑Suite">Mobile‑Suite</option>
          <option value="Cloud‑Stack">Cloud‑Stack</option>
          <option value="Portal‑X">Portal‑X</option>
        </select>
      </label>
      <label><span>Оценка</span>
        <span class="rating-options">
          <input type="radio" name="rating" value="1">1
          <input type="radio" name="rating" value="2">2
          <input type="radio" name="rating" value="3" checked>3
          <input type="radio" name="rating" value="4">4
          <input type="radio" name="rating" value="5">5
        </span>
      </label>
      <label><span>Подписаться на новости</span>
        <input type="checkbox" name="subscribe" value="1">
      </label>
      <label><span>Комментарий</span>
        <textarea name="comment" rows="4" cols="40"></textarea>
      </label>
      <button type="submit">Отправить</button>
    </form>

    <h3>Последние отзывы</h3>
    <div id="guestbook-list" class="guestbook-list" style="max-height:200px; overflow:auto; border:1px solid #ccc; padding:5px;">
    <?php foreach($entries as $e): ?>
      <?php $stars = str_repeat('★', (int)$e['rating']) . str_repeat('☆', 5 - (int)$e['rating']); ?>
      <p><b><?=htmlspecialchars($e['name'])?></b> (<?=htmlspecialchars($e['city'])?>) — <?=htmlspecialchars($e['product'])?> — <?= $stars ?>:<br><?=htmlspecialchars($e['comment'])?></p>
      <hr>
    <?php endforeach; ?>
    </div>
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
