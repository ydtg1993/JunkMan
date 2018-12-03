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
    private static $line;

    /**
     * @param mixed $line
     */
    public static function setLine($line)
    {
        self::$line = $line;
    }

    /**
     * @param $content
     * @return array|mixed
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
            $type = self::ARRAY;
        }

        if (is_object($content)) {
            $type = get_class($content);
        }

        return [
            'variable' => [
                'val' => $content,
                'type' => $type
            ],
            'line' => self::$line
        ];
    }

}