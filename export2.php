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

    public function write(array $elements, $parentId = 0, $level = 0)
    {
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId && $level <= 1) {
                fwrite($this->file, str_repeat("  ", $level) . $element['name'] . "\n");
                $children = $this->write(
                    $elements,
                    $element['id'],
                    $level + 1,
                );
            }
        }
    }

}


$env = parse_ini_file('.env');

$db = new PDO(
    sprintf(
        'pgsql:host=localhost;port=%s;dbname=%s;user=%s;password=%s',
        $env['POSTGRES_PORT'],
        $env['POSTGRES_DB'],
        $env['POSTGRES_USER'],
        $env['POSTGRES_PASSWORD']
    )
);


$statement = $db->prepare("SELECT * FROM categories");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

$export = new Export('type_b.txt');
$export->write($result);
