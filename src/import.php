<?php


class Database
{
    public function __construct($port, $dbname, $user, $password)
    {
        $this->db = new PDO(
            sprintf(
                'pgsql:host=db;port=%s;dbname=%s;user=%s;password=%s',
                $port,
                $dbname,
                $user,
                $password
            )
        );
    }



    public function __destruct()
    {
        $this->db = null;
    }
}

function array_values_recursive($array, $parent_id = null)
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
            $result = array_merge($result, array_values_recursive($value->childrens, $value->id));
        }
    }

    return $result;
}


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
// $statement = $db->prepare("SELECT * FROM categories");
// $statement->execute();
// $result = $statement->fetchAll(PDO::FETCH_ASSOC);
// echo json_encode($result);


$json = file_get_contents('categories.json');
if ($json === false) {
    die('Error reading the JSON file');
}

$json_data = json_decode($json);
if ($json_data === null) {
    die('Error decoding the JSON file');
}

// print_r($json_data);



$table = array_values_recursive($json_data);
// print_r($table);

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