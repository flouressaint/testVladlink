<?php

class Export
{
    private $file;
    public function __construct($filename)
    {
        $this->file = fopen($filename, "w") or die("Unable to open file!");
    }

    public function __destruct()
    {
        fclose($this->file);
    }

    public function write(array $elements, $parentId = 0, $level = "", $url = "/")
    {
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                fwrite($this->file, $level . $element['name'] . "  " . $url . $element['alias'] . "\n");
                $children = $this->write(
                    $elements,
                    $element['id'],
                    $level . "  ",
                    $url . $element['alias'] . "/"
                );
            }
        }
    }

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


$statement = $db->prepare("SELECT * FROM categories");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

$export = new Export('type_a.txt');
$export->write($result);