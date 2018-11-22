<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/21 0021
 * Time: 下午 7:10
 */
namespace JunkMan\Tool;

/**
 * Class Helper
 * @package JunkMan\Tool
 */
class Helper
{
    /**
     * @param $array
     * @param array $params
     * @return array
     */
    public static function multiQuery2Array($array, array $params)
    {
        foreach ($array as $item) {
            $add = true;
            foreach ($params as $field => $value) {
                if ($item[$field] != $value) {
                    $add = false;
                }
            }
            if ($add) {
                return $item;
            }
        }

        return [];
    }

    /**
     * @param $file
     * @param $start
     * @param $to
     * @return string
     */
    public static function cutFile($file, $start, $to)
    {
        $txt = '';
        $line = 1;
        $handle = @fopen($file, "r");
        if ($handle) {
            while (($buffer = fgets($handle)) !== false) {
                if ($line <= $to && $line >= $start) {
                    $txt.= $buffer;
                }
                $line++;
            }
            fclose($handle);
        }

        return $txt;
    }

    /**
     * @param mixed ...$params
     * @return string
     */
    public static function secret(...$params)
    {
        return bin2hex(json_encode($params));
    }
}