<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/21 0021
 * Time: 下午 7:10
 */

class Helper
{
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

    public static function cutFile($file,$start,$to)
    {
        $txt = '';
        $line = 1;
        $handle = @fopen($file, "r");
        if ($handle) {
            while (($buffer = fgets($handle)) !== false) {
                $buffer = fgets($handle);
                if($line < $to && $line > $start){
                    $txt.= $buffer;
                }
                $line++;
            }
            fclose($handle);
        }

        return $txt;
    }
}