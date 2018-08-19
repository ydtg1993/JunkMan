<?php
/**
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/8/19
 * Time: 21:33
 */
namespace Core;

use Stream\Stream;

class Application
{
    public function run()
    {
        date_default_timezone_set('Asia/Shanghai');
        self::Config();
        self::setTime();
        self::secret();
        self::iniDebug();
        self::setTemp(Defined::getSECRET());
    }

    private static function Config()
    {
        $config = file_get_contents(Stream::ROOT_PATH.DIRECTORY_SEPARATOR.'Config.json');
        $config = json_decode($config, true);
        Defined::setConfig($config);
    }

    private static function setTime()
    {
        Defined::setTIME(time());
    }

    private static function secret()
    {
        $config = Defined::getConfig();
        $config_str = md5(join('@',$config)).'&'.Defined::getTIME();
        $secret = bin2hex($config_str);
        Defined::setSECRET($secret);
    }

    private static function iniDebug()
    {
        //TODO
        ini_set('xdebug.collect_params',4);
        ini_set('xdebug.collect_return',1);
        ini_set('xdebug.show_mem_delta',1);
        ini_set('xdebug.collect_assignments',1);
        ini_set('xdebug.collect_vars',1);
        ini_set('xdebug.trace_format',0);
        ini_set('xdebug.profiler_enable',1);
        ini_set('xdebug.remote_enable',1);
    }

    private static function setTemp($secret)
    {
        $file = Stream::ROOT_PATH.DIRECTORY_SEPARATOR.'Temp'.DIRECTORY_SEPARATOR.$secret;
        Defined::setTemp($file);
    }
}