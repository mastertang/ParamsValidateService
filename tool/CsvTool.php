<?php

namespace ParamsValidateMicroServices\tool;

/**
 * Class CsvTool
 * @package ParamsValidateMicroServices\tool
 */
class CsvTool
{
    /**
     * 创建csv文件
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
     * @param null $page
     * @param null $limit
     * @return array|bool
     */
    public static function readCsv($path, $page = null, $limit = null)
    {
        $file = fopen($path, 'r');
        if ($file === false) {
            return false;
        }
        $list = [];
        if (is_numeric($page) && is_numeric($limit) && $page >= 1 && $limit >= 1) {
            $offset = ($page - 1) * $limit;
            $index  = 0;
            while ($data = fgetcsv($file)) {
                if ($index >= $offset) {
                    $list[] = $data;
                }
                $index++;
            }
        } else {
            while ($data = fgetcsv($file)) {
                $list[] = $data;
            }
        }
        fclose($file);
        return $list;
    }
}