<?php

require_once 'Database.php';
require 'Export.php';

$db = Database::connect();
$statement = $db->prepare("SELECT * FROM categories");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

$export = new Export('type_b.txt');
$export->writeMenuOneLevel($result);
echo "<p>Данные категорий не далее первого уровня выведены в файл type_b.txt</p>";