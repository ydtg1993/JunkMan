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
        self::async();
    }

    private static function sync()
    {
        $ini = Defined::getINI();
        $file = Defined::getTemp() . '.xt';
        $head = Defined::getSOCKETHEAD();
        try {
            (new Socket($ini['SERVER'], $ini['PORT']))->setHead($head)->write($file);
        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }finally {
            unlink($file);
        }
    }

    private static function async()
    {
        $file = Defined::getTemp() . '.xt';
        $server = Defined::getINI()['SERVER'];
        $port = Defined::getINI()['PORT'];
        $head = Defined::getSOCKETHEAD();

        $pipe = Stream::ROOT_PATH.DIRECTORY_SEPARATOR.'Pipe'.DIRECTORY_SEPARATOR.'Pipeline.php';
        $command = "D:\PHP\php.exe {$pipe} -I{$server} -P{$port} -F{$file} -H{$head}";
        $handle = popen($command,"w");
        pclose($handle);
    }
}