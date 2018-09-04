<?php

namespace ParamsValidateMicroServices\tool;

class CsvTool
{
    //创建csv
    public static function createCsv($path, $title, $data)
    {
        $fp = fopen($path, 'w');
        if ($fp === false) {
            return false;
        }
        fwrite($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($fp, $title);
        foreach ($data as $row) {
            fputcsv($fp, $row);
        }
        return fclose($fp);
    }

    /*
     * 读取csv文件
     */
    public static function readCsv($path)
    {
        $file = fopen($path, 'r');
        if ($file === false) {
            return false;
        }
        $list = [];
        while ($data = fgetcsv($file)) {
            $list[] = $data;
        }
        return fclose($file);
    }
}