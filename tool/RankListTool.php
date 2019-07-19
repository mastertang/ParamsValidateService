<?php

namespace ParamsValidateMicroServices\tool;

/**
 * Class RankListTool
 * @package ParamsValidateMicroServices\tool
 */
class RankListTool
{
    /**
     * 获取我的位置
     *
     * @param $scoreTable
     * @param $pkey
     * @param $sqlQuery
     * @param $countFieldName
     * @param array $fields
     * @param array $orders
     * @return bool|mixed
     */
    public static function getMyPosition(
        $scoreTable,
        $pkey,
        $sqlQuery,
        $countFieldName,
        $fields = [],
        $orders = []
    )
    {
        if (!($sqlQuery instanceof \Closure) || empty($countFieldName) || empty($scoreTable) || empty($pkey) || !is_array($pkey) || !is_string($scoreTable)) {
            return false;
        }
        $sql = "select :fields from (select *,(@rowno:=@rowno+1) as :countField from :table,(select (@rowno:=0)) b :order) c where :condition";

        $pkeyString  = '';
        $fieldString = '';
        $orderString = '';

        foreach ($pkey as $fieldName => $value) {
            $pkeyString .= ' and ' . $fieldName . '=' . (is_string($value) ? "'{$value}'" : $value);
        }
        $pkeyString = substr($pkeyString, 5);

        if (is_array($fields) && !empty($fields)) {
            $fieldString = $countFieldName . ',' . implode(',', $fields);
        }

        if (is_array($orders) && !empty($orders)) {
            foreach ($orders as $field => $order) {
                if (in_array(strtolower($order), ['desc', 'asc'])) {
                    $orderString .= ",{$field} {$order}";
                }
            }
            if (!empty($orderString)) {
                $orderString = substr($orderString, 1);
            }
        }

        if (empty($fieldString)) {
            $fieldString = '*';
        }

        $sql = str_replace(
            [
                ':fields',
                ':countField',
                ':table',
                ':order',
                ':condition'
            ],
            [
                $fieldString,
                $countFieldName,
                $scoreTable,
                empty($orderString) ? '' : 'order by ' . $orderString,
                $pkeyString
            ],
            $sql
        );

        $myInfo = call_user_func_array($sqlQuery, [$sql]);
        return $myInfo;
    }

    /**
     * 获取当场分数的排位
     *
     * @param $nowScore
     * @param $table
     * @param $totalRank
     * @param $sqlQuery
     * @return bool|int|mixed
     */
    public static function getRealPosition(
        $nowScore,
        $table,
        $totalRank,
        $sqlQuery
    )
    {
        if (!($sqlQuery instanceof \Closure) || !is_array($nowScore) || empty($nowScore) || !isset($nowScore[0], $nowScore[1]) || empty($table)) {
            return false;
        }
        if ($totalRank == 0) {
            return 1;
        }
        $sql = 'select count(*) from :table where :scoreField < :score';
        list($scoreFieldName, $scoreValue) = $nowScore;
        $sql   = str_replace(
            [
                ':table',
                ':scoreField',
                ':score'
            ],
            [
                $table,
                $scoreFieldName,
                $scoreValue
            ],
            $sql
        );
        $count = call_user_func_array($sqlQuery, [$sql]);
        if (empty($count)) {
            return $totalRank + 1;
        }
        return $totalRank - $count;
    }

    /*
     * 获取打败人数
     */
    public static function getBestPersion($myPosition, $totalRank, $percent = false, $minCount = 0.00, $maxCount = 99.99, $size = 0)
    {
        $beastCount = $totalRank - $myPosition;
        if ($percent) {
            if ($myPosition == 1) {
                return $size == 0 ? (int)$maxCount : $maxCount;
            }
            $tempBeast = (abs($beastCount) / $totalRank) * 100;
            return $beastCount <= 0 ? ($size == 0 ? (int)$minCount : $minCount) : ($size == 0 ? (int)$tempBeast : round($tempBeast, $size));
        } else {
            return $beastCount <= 0 ? 0 : abs($beastCount);
        }
    }
}