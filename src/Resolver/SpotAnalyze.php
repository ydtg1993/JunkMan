<?php
/**
 * Created by PhpStorm.
 * User: Hikki
 * Date: 2018/11/22 0022
 * Time: 上午 11:57
 */

namespace JunkMan\Resolver;


/**
 * Class StreamAnalyze
 * @package JunkMan\Resolver
 */
class SpotAnalyze extends Analyze
{
    private static $var;
    private static $line;

    public static function setVar($var){
        self::$var = $var;
    }

    /**
     * @param mixed $line
     */
    public static function setLine($line)
    {
        self::$line = $line;
    }

    /**
     * @param $content
     * @return array
     */
    public static function index($content)
    {
        $type = '';
        if (is_string($content)) {
            $type = self::STR;
        }

        if (is_int($content)) {
            $type = self::INT;
        }

        if (is_float($content)) {
            $type = self::FLOAT;
        }

        if (is_array($content)) {
            $type = self::ARR;
        }

        if (is_object($content)) {
            $type = get_class($content);
        }

        return [
            'Variable' => self::$var,
            'Value' => $content,
            'Type' => $type,
            'Line' => self::$line
        ];
    }

}