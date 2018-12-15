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
    }

    public static function stop()
    {
        $trace_file = self::$collector->getTraceFile();
        $trace_file_content = '';

        $start_line = (int)self::$collector->getTraceStart();
        $stop_line = self::$collector->getTraceEnd() ? (int)self::$collector->getTraceEnd() : (int)self::$collector->getTraceStart();

        if (is_file($trace_file)) {
            $trace_file_content = Io::cutFile(
                $trace_file,
                $start_line - Collector::SIDE_LINE,
                $stop_line + Collector::SIDE_LINE);
        }

        $data = [
            'title' => self::$collector->getStreamTitle(),
            'time' => self::$collector->getTime(),
            'secret' => self::$collector->getSecret(),
            'trace_file' => self::$collector->getTraceFile(),
            'stream_type' => self::$collector->getTraceType()];

        self::$collector->setHeader($data);
        self::$collector->setTraceFileParagraph(['trace_file_content' => $trace_file_content]);
    }

    /**
     * @throws \Exception
     */
    private static function config()
    {
        $config = [
            'async' => JunkMan::ASYNC,
            'php' => JunkMan::PHP
        ];
        self::$collector->setConfig($config);
    }

    private static function secret()
    {
        $secret = Helper::secret(JunkMan::PASSPORT_CODE, self::$collector->getTime());
        self::$collector->setSecret($secret);
    }

    private static function setTemp()
    {
        $path = JunkMan::ROOT_PATH . DIRECTORY_SEPARATOR . 'Temp';
        if (!is_dir($path)) {
            mkdir($path);
        }
        $file = $path . DIRECTORY_SEPARATOR . self::$collector->getSecret() . Collector::STREAM_SUFFIX;
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