<?php
/**
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/9/2
 * Time: 17:29
 */

/**
 * Class Driver
 */
class Driver
{
    const SUFFIX = '.xt';

    public static function execute()
    {
        self::async();
    }

    private static function sync()
    {
        $config = Defined::getConfig();
        $file = Defined::getTemp() . self::SUFFIX;
        $head = Defined::getSOCKETHEAD();
        try {
            (new Sender($config['remote']['SERVER'], $config['remote']['PORT']))->setHead($head)->write($file);
        }catch (\Exception $e){
            unlink($file);
            throw new \Exception($e->getMessage());
        }finally {
            unlink($file);
        }
    }

    private static function async()
    {
        $config = Defined::getConfig();
        $file = Defined::getTemp() . self::SUFFIX;
        $head = Defined::getSOCKETHEAD();

        try {
            $pid = pcntl_fork();
            if ($pid == -1) {
                throw new \Exception('could not fork');
            } else if ($pid) {
                pcntl_wait($status);
            } else {
                (new Sender($config['remote']['SERVER'], $config['remote']['PORT']))->setHead($head)->write($file);
            }
        }catch (\Exception $e){
            unlink($file);
            throw new \Exception($e->getMessage());
        }finally {
            unlink($file);
        }
    }
}