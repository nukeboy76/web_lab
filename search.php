<?php
// simple product search by first letter
$catalog = [
    ['name' => 'CRM‑PRO', 'url' => 'product/crm-pro.html', 'desc' => 'Корпоративная CRM‑система для бизнеса'],
    ['name' => 'Mobile‑Suite', 'url' => 'product/mobile-suite.html', 'desc' => 'Нативные мобильные приложения'],
    ['name' => 'Cloud‑Stack', 'url' => 'product/cloud-stack.html', 'desc' => 'Оркестрация Docker/K8s'],
    ['name' => 'Portal‑X', 'url' => 'product/portal-x.html', 'desc' => 'Корпоративный портал'],
];

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];
if ($q !== '') {
    $letter = mb_strtolower(mb_substr($q, 0, 1));
    foreach ($catalog as $item) {
        if (mb_strtolower(mb_substr($item['name'],0,1)) === $letter) {
            $results[] = $item;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Поиск</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<h2>Результаты поиска</h2>
<form method="get" action="search.php" style="margin-bottom:20px;">
<input type="search" name="q" value="<?=htmlspecialchars($q)?>" placeholder="введите букву">
<input type="submit" value="Поиск">
</form>
<?php if($q===''): ?>
<p>Введите букву названия товара.</p>
<?php elseif(empty($results)): ?>
<p>Ничего не найдено.</p>
<?php else: ?>
<ul>
<?php foreach($results as $r): ?>
  <li><a href="<?=htmlspecialchars($r['url'])?>"><?=htmlspecialchars($r['name'])?></a> — <?=htmlspecialchars($r['desc'])?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
</body>
</html>
