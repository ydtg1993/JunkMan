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
        $bool = Defined::getConfig()['async'];
        if($bool) {
            self::async();
        }else{
            self::sync();
        }
    }

    private static function sync()
    {
        $config = Defined::getConfig();
        $file = Defined::getTemp() . self::SUFFIX;
        $head = Defined::getSOCKETHEAD();
        try {
            (new Sender($config['remote']['SERVER'], $config['remote']['PORT']))->setHead($head)->write($file);
        }catch (\Exception $e){
            if(is_file($file)) {
                @unlink($file);
            }
            throw new \Exception($e->getMessage());
        }finally {
            if(is_file($file)) {
                @unlink($file);
            }
        }
    }

    private static function async()
    {
        $config = Defined::getConfig();
        $file = Defined::getTemp() . self::SUFFIX;
        $head = Defined::getSOCKETHEAD();

        if(!function_exists('pcntl_fork')){
            throw new \Exception('Need to install pcntl');
        }

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
            if(is_file($file)) {
                @unlink($file);
            }
            throw new \Exception($e->getMessage());
        }finally {
            if(is_file($file)) {
                @unlink($file);
            }
        }
    }
}