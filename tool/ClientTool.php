<?php

namespace ParamsValidateMicroServices\tool;

class ClientTool
{
    /**
     * 创建csv
     *
     * @param $path
     * @param $title
     * @param $data
     * @return bool
     */
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

    /**
     * 读取csv文件
     *
     * @param $path
     * @return bool
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