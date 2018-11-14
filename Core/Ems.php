<?php
/**
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/8/21
 * Time: 23:31
 */

namespace Core;

use Core\Defined;
use Stream\Stream;

class Ems
{
    public static function examine()
    {
        date_default_timezone_set('Asia/Shanghai');
        Defined::setTIME(time());

        self::setXdebug();
    }

    private static function setXdebug()
    {
        ini_set('xdebug.collect_params', 4);
        ini_set('xdebug.collect_return', 1);
        ini_set('xdebug.show_mem_delta', 0);
        ini_set('xdebug.collect_assignments', 1);
        //ini_set('xdebug.collect_vars', 1);
        ini_set('xdebug.trace_format', 0);
        ini_set('xdebug.profiler_enable', 1);
        ini_set('xdebug.var_display_max_depth',10);
        ini_set('collect_assignments',1);
        ini_set('xdebug.coverage_enable',1);
    }

}