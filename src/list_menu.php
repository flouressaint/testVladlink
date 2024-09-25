<!DOCTYPE html>
<html>

<head>
    <title>list menu</title>
</head>

<body>
    <?php
    $env = parse_ini_file('local.env');

    $db = new PDO(
        sprintf(
            'pgsql:host=db;port=%s;dbname=%s;user=%s;password=%s',
            $env['POSTGRES_PORT'],
            $env['POSTGRES_DB'],
            $env['POSTGRES_USER'],
            $env['POSTGRES_PASSWORD']
        )
    );


    $statement = $db->prepare("SELECT * FROM categories");
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

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

    write($result);
    ?>
</body>



</html>