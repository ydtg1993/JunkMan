<?php
/**
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/9/2
 * Time: 17:29
 */

namespace Pipe;

use Core\Defined;
use Mockery\Exception;
use Stream\Stream;

class Driver
{
    public static function execute()
    {
        self::sync();
    }

    private static function sync()
    {
        $ini = Defined::getINI();
        $file = Defined::getTemp() . '.xt';
        try {
            (new Socket($ini['SERVER'], $ini['PORT']))->write($file);
        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }finally {
            unlink($file);
        }
    }

    private function async()
    {
        $pipe = Stream::ROOT_PATH.DIRECTORY_SEPARATOR.'Pipe'.DIRECTORY_SEPARATOR.'Pipeline.php';
        $handle = popen("D:\PHP\php.exe {$pipe}","w");
        $params = [
            'ini' => Defined::getINI(),
            'file' => Defined::getTemp() . '.xt',
        ];
        fwrite($handle, serialize($params));
        pclose($handle);
    }
}