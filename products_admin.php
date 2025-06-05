<?php
require_once __DIR__.'/db.php';
$mysqli = get_db_connection();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $alias = trim($_POST['alias'] ?? '');
    if($name==='') $errors[]='Название обязательно';
    if($alias==='') $errors[]='Alias обязателен';
    if(!$errors){
        if(isset($_POST['id']) && $_POST['id']!==''){
            $stmt = $mysqli->prepare('UPDATE product SET name=?, alias=?, price=? WHERE id=?');
            $id = intval($_POST['id']);
            $stmt->bind_param('ssdi', $name, $alias, $price, $id);
        }else{
            $stmt = $mysqli->prepare('INSERT INTO product (manufacturer_id,name,alias,short_description,description,price,image,meta_keywords,meta_description,meta_title) VALUES (1,?,?,"","",?,"","","","")');
            $stmt->bind_param('ssd', $name, $alias, $price);
        }
        $stmt->execute();
        $stmt->close();
        header('Location: products_admin.php');
        exit;
    }
}
$sort = $_GET['sort'] ?? 'name';
if(!in_array($sort,['name','price'])) $sort='name';
$res = $mysqli->query("SELECT id,name,alias,price FROM product ORDER BY $sort ASC");
$items = $res->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Управление товарами</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<h2>Товары</h2>
<?php if($errors): ?><ul><?php foreach($errors as $e): ?><li><?=htmlspecialchars($e)?></li><?php endforeach; ?></ul><?php endif; ?>
<table border="1" cellpadding="5">
<tr><th><a href="?sort=name">Название</a></th><th><a href="?sort=price">Цена</a></th><th>Alias</th><th></th></tr>
<?php foreach($items as $it): ?>
<tr>
  <td><?=htmlspecialchars($it['name'])?></td>
  <td><?=htmlspecialchars($it['price'])?></td>
  <td><?=htmlspecialchars($it['alias'])?></td>
  <td><a href="?edit=<?=intval($it['id'])?>">редактировать</a></td>
</tr>
<?php endforeach; ?>
</table>
<?php
$edit = null;
if(isset($_GET['edit'])){
    foreach($items as $it){ if($it['id']==intval($_GET['edit'])) $edit=$it; }
}
?>
<h3><?= $edit ? 'Редактировать' : 'Добавить' ?> товар</h3>
<form method="post" action="products_admin.php">
<?php if($edit): ?><input type="hidden" name="id" value="<?=intval($edit['id'])?>"><?php endif; ?>
<label>Название <input type="text" name="name" value="<?=htmlspecialchars($edit['name'] ?? '')?>" required></label><br>
<label>Alias <input type="text" name="alias" value="<?=htmlspecialchars($edit['alias'] ?? '')?>" required></label><br>
<label>Цена <input type="number" step="0.01" name="price" value="<?=htmlspecialchars($edit['price'] ?? '0')?>"></label><br>
<input type="submit" value="Сохранить">
</form>
</body>
</html>
