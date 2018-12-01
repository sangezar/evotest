<?php

class CSVParser
{
    protected $file;
    protected $threshold;

    public function __construct()
    {
        $argv = $_SERVER['argv'];

        if (!isset($argv) || count($argv) <= 2) {
            throw new Exception("[Error] Пропущены аргументы.");
        }

        $this->file = $argv[1];
        $this->threshold = $argv[2];
    }

    public function start()
    {
        if (!file_exists($this->file)) {
            throw new Exception("[Error] Нет такого файла.");
        }

        if (!isset(pathinfo($this->file)['extension'])
            || pathinfo($this->file)['extension'] !== 'csv') {
            throw new Exception("[Error] Не известный формат файла.");
        }

        $dataArray = array_map('str_getcsv', file($this->file));

        foreach ($dataArray as $key => $value) {
            if ((double) $value[2] >= $this->threshold) {
                echo $value[0] . " " . $value[1]. " " . $value[2] . "\n";
            }
        }
    }
}

try{
    $parser = new CSVParser();
    $parser->start();
} catch (Exception $e) {
    echo $e->getMessage(), "\n";
}
