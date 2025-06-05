<?php
$search_q = trim($_GET['q'] ?? '');
$results = [];
$error = '';

if ($search_q !== '') {
    $letter = mb_substr($search_q, 0, 1);
    if (!preg_match('/^\p{L}$/u', $letter)) {
        $error = 'Введите одну букву.';
    } else {
        require_once __DIR__.'/db.php';
        $mysqli = get_db_connection();
        $stmt = $mysqli->prepare('SELECT name, alias, short_description FROM product WHERE name LIKE CONCAT(?, "%")');
        $stmt->bind_param('s', $letter);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $results[] = [
                'name'  => $row['name'],
                'alias' => $row['alias'],
                'desc'  => $row['short_description'],
            ];
        }
        $stmt->close();
        $mysqli->close();
    }
}

if (isset($_GET['format']) && $_GET['format']==='json') {
    header('Content-Type: application/json; charset=utf-8');
    if ($error) {
        http_response_code(400);
        echo json_encode(['error'=>$error], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode($results, JSON_UNESCAPED_UNICODE);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Поиск</title>
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
    <h2>Результаты поиска</h2>
    <form method="get" action="search.php" style="margin-bottom:20px;">
      <input type="search" name="q" value="<?=htmlspecialchars($search_q)?>" placeholder="введите букву">
      <input type="submit" value="Поиск">
    </form>
    <?php if($search_q===''): ?>
      <p>Введите букву названия товара.</p>
    <?php elseif($error): ?>
      <p><?=htmlspecialchars($error)?></p>
    <?php elseif(empty($results)): ?>
      <p>Ничего не найдено.</p>
    <?php else: ?>
      <ul>
        <?php foreach($results as $r): ?>
          <li><a href="product/<?=htmlspecialchars($r['alias'])?>.html"><?=htmlspecialchars($r['name'])?></a> — <?=htmlspecialchars($r['desc'])?></li>
        <?php endforeach; ?>
      </ul>
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
  &copy;&nbsp;2025 ООО «Галактика». Все права защищены
</footer>
</body>
</html>
