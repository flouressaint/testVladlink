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

    public function writeMenuWithURL(array $elements, $parentId = 0, $level = "", $url = "/")
    {
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                fwrite($this->file, $level . $element['name'] . "  " . $url . $element['alias'] . "\n");
                $children = $this->writeMenuWithURL(
                    $elements,
                    $element['id'],
                    $level . "  ",
                    $url . $element['alias'] . "/"
                );
            }
        }
    }

    public function writeMenuOneLevel(array $elements, $parentId = 0, $level = 0)
    {
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId && $level <= 1) {
                fwrite($this->file, str_repeat("  ", $level) . $element['name'] . "\n");
                $children = $this->writeMenuOneLevel(
                    $elements,
                    $element['id'],
                    $level + 1,
                );
            }
        }
    }
}