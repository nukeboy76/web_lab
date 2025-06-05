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
        $stmt = $mysqli->prepare('SELECT name, alias AS url, short_description FROM product WHERE name LIKE CONCAT(?, "%")');
        $stmt->bind_param('s', $letter);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $results[] = [
                'name' => $row['name'],
                'url'  => 'product/'.htmlspecialchars($row['alias']).'.html',
                'desc' => $row['short_description']
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
</head>
<body>
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
  <li><a href="<?=htmlspecialchars($r['url'])?>"><?=htmlspecialchars($r['name'])?></a> — <?=htmlspecialchars($r['desc'])?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
</body>
</html>
