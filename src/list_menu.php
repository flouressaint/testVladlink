<!DOCTYPE html>
<html>

<head>
    <title>list menu</title>
</head>

<body>
    <?php
    require_once 'Database.php';

    $db = Database::connect();
    $statement = $db->prepare("SELECT * FROM categories");
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    if (count($result) == 0) {
        echo "Данные категорий не найдены";
        return;
    }
    write($result);
    function write(array $elements, $parentId = 0, $level = "")
    {
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                echo  $level . $element['name'] . "<br>";
                $children = write(
                    $elements,
                    $element['id'],
                    $level . "&nbsp;&nbsp;",
                );
            }
        }
    }

    ?>
</body>

</html>