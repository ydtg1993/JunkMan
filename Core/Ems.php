<?php
/**
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/8/21
 * Time: 23:31
 */

namespace App\libs\Stream\Core;

use Core\Defined;
use Stream\Stream;

class Ems
{
    private static $INI;
    private static $config;

    public static function examine()
    {
        date_default_timezone_set('Asia/Shanghai');
        Defined::setTIME(time());
        self::$config = Defined::getConfig();
        if(!is_file(self::$config['PHP'])) {
            self::checkPhp();
            self::upIni();
        }

        self::setPhpIni();
    }

    private static function checkPhp()
    {
        if (!self::$config['PHP']) {
            self::$INI = ini_get_all();
            Defined::setINI(self::$INI);
            $path = dirname(self::$INI['extension_dir']['local_value']);
            if (substr(strtolower(PHP_OS), 0, 3) == 'win') {
                $php = $path . DIRECTORY_SEPARATOR . 'php.exe';
            } else {
                $php = $path . DIRECTORY_SEPARATOR . 'php';
            }

            if (is_file($php)) {
                self::$config['PHP'] = $php;
                Defined::setPHP($php);
            }
        }
    }

    private static function setPhpIni()
    {
        ini_set('xdebug.collect_params', 4);
        ini_set('xdebug.collect_return', 1);
        ini_set('xdebug.show_mem_delta', 1);
        ini_set('xdebug.collect_assignments', 1);
        ini_set('xdebug.collect_vars', 1);
        ini_set('xdebug.trace_format', 0);
        ini_set('xdebug.profiler_enable', 1);
        ini_set('xdebug.remote_enable', 1);
    }

    private static function upIni()
    {
        $config_str = var_export(self::$config, true);
        $config_path = Stream::ROOT_PATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Config.php';
        @file_put_contents($config_path, "<?php\r\nreturn " . $config_str . ';');
    }

}