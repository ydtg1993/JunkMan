<?php
/**
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/8/21
 * Time: 23:31
 */

namespace App\libs\Stream\Core;

use Core\Defined;

class Ems
{
    public static function examine()
    {
        date_default_timezone_set('Asia/Shanghai');
        Defined::setTIME(time());

        self::checkPhp();
        self::checkXdebug();
        self::checkSocket();

        self::setPhpIni();
    }

    private static function checkPhp()
    {

    }

    private static function checkXdebug()
    {

    }
    private static function checkSocket()
    {

    }

    private static function setPhpIni()
    {
        ini_set('xdebug.collect_params',4);
        ini_set('xdebug.collect_return',1);
        ini_set('xdebug.show_mem_delta',1);
        ini_set('xdebug.collect_assignments',1);
        ini_set('xdebug.collect_vars',1);
        ini_set('xdebug.trace_format',0);
        ini_set('xdebug.profiler_enable',1);
        ini_set('xdebug.remote_enable',1);
    }
}