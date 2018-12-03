<?php
/**
 * Created by PhpStorm.
 * User: Hikki
 * Date: 2018/11/21 0021
 * Time: 下午 7:10
 */
namespace JunkMan\Instrument;

use JunkMan\E\IoException;

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
     * @return string
     * @throws IoException
     */
    public static function cutFile($file, $start, $to)
    {
        $txt = '';
        $line = 1;
        try {
            $handle = fopen($file, "r");
            if ($handle) {
                while (!feof($handle)) {
                    $buffer = fgets($handle);
                    if ($line <= $to && $line >= $start) {
                        $txt .= $buffer;
                    }
                    $line++;
                }
                fclose($handle);
            }
        }catch (\Exception $e){
            throw new IoException($e->getMessage());
        }

        return $txt;
    }
}