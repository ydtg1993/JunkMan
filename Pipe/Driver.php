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

        $pid = pcntl_fork();
        if ($pid == -1) {
            //错误处理：创建子进程失败时返回-1.
            throw new Exception('could not fork');
        } else if ($pid) {
            //父进程会得到子进程号，所以这里是父进程执行的逻辑
            pcntl_wait($status); //等待子进程中断，防止子进程成为僵尸进程。
        } else {
            //子进程得到的$pid为0, 所以这里是子进程执行的逻辑。
            (new Sender($ini['SERVER'], $ini['PORT']))->setHead($head)->write($file);
        }
    }
}