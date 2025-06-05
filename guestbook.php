<?php
$file = __DIR__.'/data/guestbook.txt';
$posted = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $comment = trim($_POST['comment'] ?? '');
    $rating = $_POST['rating'] ?? '';
    $subscribe = isset($_POST['subscribe']) ? 'yes' : 'no';
    $city = trim($_POST['city'] ?? '');
    $product = $_POST['product'] ?? '';
    $entry = date('Y-m-d H:i')."\t$name\t$city\t$rating\t$subscribe\t$product\t".str_replace("\n"," ",$comment)."\n";
    file_put_contents($file, $entry, FILE_APPEND|LOCK_EX);
    $posted = true;
}
$entries = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Гостевая книга</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<h2>Гостевая книга</h2>
<?php if($posted): ?><p>Спасибо за отзыв!</p><?php endif; ?>
<form method="post" action="guestbook.php">
<label>Имя: <input type="text" name="name" required></label><br>
<label>Город: <input type="text" name="city"></label><br>
<label>Любимый продукт:
<select name="product">
<option value="CRM‑PRO">CRM‑PRO</option>
<option value="Mobile‑Suite">Mobile‑Suite</option>
<option value="Cloud‑Stack">Cloud‑Stack</option>
<option value="Portal‑X">Portal‑X</option>
</select></label><br>
<label>Оценка:
  <input type="radio" name="rating" value="1">1
  <input type="radio" name="rating" value="2">2
  <input type="radio" name="rating" value="3" checked>3
  <input type="radio" name="rating" value="4">4
  <input type="radio" name="rating" value="5">5
</label><br>
<label><input type="checkbox" name="subscribe" value="1"> Подписаться на новости</label><br>
<label>Комментарий:<br>
<textarea name="comment" rows="4" cols="40"></textarea></label><br>
<button type="submit">Отправить</button>
</form>
<h3>Последние отзывы</h3>
<div style="max-height:200px; overflow:auto; border:1px solid #ccc; padding:5px;">
<?php foreach(array_reverse($entries) as $line): list($dt,$n,$c,$r,$s,$p,$comm)=explode("\t",$line); ?>
<p><b><?=htmlspecialchars($n)?></b> (<?=htmlspecialchars($c)?>) — <?=htmlspecialchars($p)?> — оценка <?=htmlspecialchars($r)?>:<br><?=htmlspecialchars($comm)?></p>
<hr>
<?php endforeach; ?>
</div>
</body>
</html>
