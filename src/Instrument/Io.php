<?php
/**
 * Created by PhpStorm.
 * User: Hikki
 * Date: 2018/11/21 0021
 * Time: 下午 7:10
 */
namespace JunkMan\Instrument;

use JunkMan\E\JunkException;
/**
 * Class Io
 * @package JunkMan\Instrument
 */
class Io
{
    /**
     * @param $file
     * @param $start
     * @param $to
     * @return array
     * @throws JunkException
     */
    public static function cutFile($file, $start, $to)
    {
        $txt = [];
        $line = 1;
        try {
            $handle = fopen($file, "r");
            if ($handle) {
                while (!feof($handle)) {
                    $buffer = fgets($handle);
                    if ($line <= $to && $line >= $start) {
                        $txt[(string)$line] = $buffer;
                    }
                    $line++;
                }
                fclose($handle);
            }
        }catch (JunkException $e){
            throw new JunkException($e->getMessage());
        }

        return $txt;
    }

    /**
     * @param $file
     * @param $line
     * @return bool|string
     * @throws JunkException
     */
    public static function readLine($file, $line)
    {
        $index = 1;
        try {
            $handle = fopen($file, "r");
            if ($handle) {
                while (!feof($handle)) {
                    $buffer = fgets($handle);
                    if ($line == $index) {
                        return $buffer;
                    }
                    $index++;
                }
                fclose($handle);
            }
        }catch (JunkException $e){
            throw new JunkException($e->getMessage());
        }

        return false;
    }
}