<?php
/**
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/8/21
 * Time: 23:31
 */

/**
 * Class Ems
 */
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
        if (!function_exists('debug_backtrace')) {
            throw new \Exception('Need to install Xdebug');
        }
        $call_func_data = self::multiQuery2Array(debug_backtrace(), ['function' => 'start', 'class' => 'Stream']);
        Defined::setTraceFile([$call_func_data['file']]);

        ini_set('xdebug.collect_params', 4);
        ini_set('xdebug.collect_return', 1);
        ini_set('xdebug.show_mem_delta', 0);
        ini_set('xdebug.collect_assignments', 1);
        ini_set('xdebug.collect_includes', 0);
        ini_set('xdebug.trace_format', 0);
        ini_set('xdebug.profiler_enable', 1);
        ini_set('xdebug.var_display_max_depth', 10);
        ini_set('collect_assignments', 1);
        ini_set('xdebug.coverage_enable', 1);
    }

    private static function multiQuery2Array($array, array $params)
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
}