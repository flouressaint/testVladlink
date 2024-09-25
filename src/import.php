<?php

require_once 'Database.php';

$json = file_get_contents('categories.json');
if ($json === false) {
    die('Error reading the JSON file');
}
$json_data = json_decode($json);
if ($json_data === null) {
    die('Error decoding the JSON file');
}
$table = arrayFromTree($json_data);

$db = Database::connect();
foreach ($table as $value) {
    $statement = $db->prepare("
    INSERT INTO categories (id, name, alias, parent_id) 
        VALUES (:id, :name, :alias, :parent_id) 
    ON CONFLICT (id) DO UPDATE
        SET name = :name, alias = :alias, parent_id = :parent_id
    ");
    $statement->bindValue(':id', $value->id);
    $statement->bindValue(':name', $value->name);
    $statement->bindValue(':alias', $value->alias);
    $statement->bindValue(':parent_id', $value->parent_id);
    $statement->execute();
}
echo "<p>Данные из файла categories.json добавлены в БД</p>";

function arrayFromTree($array, $parent_id = null)
{
    $result = array();

    foreach ($array as $value) {
        $result[] = (object) [
            'id' => $value->id,
            'name' => $value->name,
            'alias' => $value->alias,
            'parent_id' => $parent_id
        ];
        if (isset($value->childrens)) {
            $result = array_merge($result, arrayFromTree($value->childrens, $value->id));
        }
    }

    return $result;
}