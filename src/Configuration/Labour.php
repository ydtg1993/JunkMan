<?php
/**
 * Created by PhpStorm.
 * User: Hikki
 * Date: 2018/8/19
 * Time: 21:33
 */

namespace JunkMan\Configuration;

use JunkMan\Container\Collector;
use JunkMan\Instrument\Io;
use JunkMan\JunkMan;
use JunkMan\Instrument\Helper;

/**
 * Class Decorate
 * @package JunkMan\Configuration
 */
class Labour
{
    /**
     * @var Collector
     */
    private static $collector;

    /**
     * @param $collector
     * @param $title
     * @param $trace_file_info
     * @param $trace_type
     * @throws \Exception
     */
    public static function run($collector, $title, $trace_file_info, $trace_type)
    {
        self::$collector = $collector;

        self::$collector->setTraceFile($trace_file_info['file']);
        self::$collector->setTraceStart($trace_file_info['line']);
        self::$collector->setStreamTitle($title);
        self::$collector->setTraceType($trace_type);

        self::config();
        self::secret();
        self::setTemp();
        self::setXdebug();
        self::$collector->message['trace_start_time'] = (string)microtime();
    }

    public static function retry()
    {
        self::secret();
        self::setTemp();
    }

    public static function stop()
    {
        $trace_file = self::$collector->getTraceFile();
        $trace_file_content = '';

        $start_line = (int)self::$collector->getTraceStart() - Collector::SIDE_LINE;
        $stop_line = self::$collector->getTraceEnd() ? (int)self::$collector->getTraceEnd() + Collector::SIDE_LINE : (int)self::$collector->getTraceStart() + Collector::SIDE_LINE ;

        if (is_file($trace_file)) {
            $trace_file_content = Io::cutFile(
                $trace_file,
                $start_line,
                $stop_line);
        }

        self::$collector->message['title'] = (string)self::$collector->getStreamTitle();
        self::$collector->message['status'] = (string)self::$collector->getStatus();
        self::$collector->message['time'] = (string)self::$collector->getTime();
        self::$collector->message['secret'] = (string)self::$collector->getSecret();
        self::$collector->message['temp_file'] = (string)self::$collector->getTemp() . Collector::STREAM_SUFFIX;
        self::$collector->message['trace_file'] = (string)self::$collector->getTraceFile();
        self::$collector->message['trace_file_content'] = (array)$trace_file_content;
        self::$collector->message['trace_start_line'] = (string)$start_line;
        self::$collector->message['trace_end_line'] = (string)$stop_line;
        self::$collector->message['trace_end_time'] = (string)microtime();
        self::$collector->message['stream_type'] = (string)self::$collector->getTraceType();
        self::$collector->message['extend'] = (string)self::$collector->getExtend();
    }

    /**
     * @throws \Exception
     */
    private static function config()
    {
        $config = file_get_contents(JunkMan::ROOT_PATH . DIRECTORY_SEPARATOR . 'config.json');
        $config = (array)json_decode($config, true);
        self::$collector->setConfig($config);
    }

    private static function secret()
    {
        $secret = Helper::secret(Helper::randomCode());
        self::$collector->setSecret($secret);
    }

    private static function setTemp()
    {
        $path = JunkMan::ROOT_PATH . DIRECTORY_SEPARATOR . 'Temp';
        $file = $path . DIRECTORY_SEPARATOR . self::$collector->getSecret();
        self::$collector->setTemp($file);
    }

    /**
     * @throws \Exception
     */
    private static function setXdebug()
    {
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
        xdebug_set_filter(
            XDEBUG_FILTER_TRACING,
            XDEBUG_PATH_BLACKLIST,
            [self::$collector->getTraceFile()]
        );
    }
}