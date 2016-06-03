<?php

use Illuminate\Database\Seeder;

class CSVSeeder extends Seeder
{

    protected $table      = 'table name';

    protected $isTest     = false;

    protected $isDisabled = false;

    public function run()
    {
        if (!$this->isDisabled) {
            DB::table($this->table)->delete();
        }
        $csvFile = dirname(__FILE__).'/data/'.$this->table.'.csv';
        if ($this->isTest) {
            $csvFile = dirname(__FILE__).'/data/testing/'.$this->table.'.csv';
        } else {
            if ($this->isDisabled) {
                $csvFile = dirname(__FILE__).'/data/disabled/'.$this->table.'.csv';
            }
        }
        $data = $this->csvToArray($csvFile);
        if (count($data) < 100) {
            DB::table($this->table)->insert($data);
        } else {
            foreach ($data as $row) {
                DB::table($this->table)->insert($row);
            }
        }
        $data = null;
    }

    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }

        $header = null;
        $data = [];

        if (( $handle = fopen($filename, 'r') ) !== false) {
            while (( $row = fgetcsv($handle, 1500, $delimiter) ) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    try {
                        $data[] = array_combine($header, $row);
                    } catch (Exception $e) {
                        print_r($row);
                        print $e->getMessage();
                        exit;
                    }
                }
            }
            fclose($handle);
        }

        return $data;
    }
}
