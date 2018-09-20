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
            (new Sender($ini['SERVER'], $ini['PORT']))->setHead($head)->write($file);
        }catch (Exception $e){
            unlink($file);
            throw new Exception($e->getMessage());
        }finally {
            unlink($file);
        }
    }

    private static function async()
    {
        $ini = Defined::getINI();
        $file = Defined::getTemp() . '.xt';
        $head = Defined::getSOCKETHEAD();

        try {
            $pid = pcntl_fork();
            if ($pid == -1) {
                throw new Exception('could not fork');
            } else if ($pid) {
                pcntl_wait($status);
            } else {
                (new Sender($ini['SERVER'], $ini['PORT']))->setHead($head)->write($file);
            }
        }catch (\Exception $e){
            unlink($file);
            throw new Exception($e->getMessage());
        }finally {
            unlink($file);
        }
    }
}