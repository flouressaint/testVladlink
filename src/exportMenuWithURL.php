<?php

require_once 'Database.php';
require 'Export.php';

$db = Database::connect();
$statement = $db->prepare("SELECT * FROM categories");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

$export = new Export('type_a.txt');
$export->writeMenuWithURL($result);
echo "<p>Данные категорий с ссылками выведены в файл type_a.txt</p>";