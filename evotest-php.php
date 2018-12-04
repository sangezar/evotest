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

        usort($dataArray, function ($foo, $bar) {
            if ($foo[0] == $bar[0]) {
                if ($foo[1] == $bar[1]) {
                    return 0;
                }
                return ($foo[1] > $bar[1]) ? 1 : -1;
            }
            return ($foo[0] > $bar[0]) ? 1 : -1;
        });

        $resultArray = [];

        foreach ($dataArray as $item) {
            if(!isset($resultArray[$item[0]])){
              $resultArray[$item[0]] = [
                  "date" => '',
                  "total" => 0
              ];
            }

            if($resultArray[$item[0]]["total"] < $this->threshold){
                $resultArray[$item[0]]["date"] = $item[1];
                $resultArray[$item[0]]["total"] += (double) $item[2];
            }
        }

        foreach ($resultArray as $key => $value) {
            if($value['total'] >= $this->threshold) {
              echo $key . " " . $value['date'] . ' ' .  $value['total'] . "\n";
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
