<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/21 0021
 * Time: 下午 7:10
 */
namespace JunkMan\Instrument;

/**
 * Class Helper
 * @package JunkMan\Instrument
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
     * @param mixed ...$params
     * @return string
     */
    public static function secret(...$params)
    {
        return bin2hex(json_encode($params));
    }
}