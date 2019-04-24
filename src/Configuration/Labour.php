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
    private $collector;

    /**
     * @param $collector
     * @param $title
     * @param $trace_file_info
     * @param $trace_type
     * @throws \Exception
     */
    public function run($collector, $title, $trace_file_info, $trace_type)
    {
        $this->collector = $collector;

        $this->collector->setTraceFile($trace_file_info['file']);
        $this->collector->setTraceStart($trace_file_info['line']);
        $this->collector->setStreamTitle($title);
        $this->collector->setTraceType($trace_type);

        $this->config();
        $this->secret();
        $this->setTemp();
        $this->setXdebug();
        $this->collector->message['trace_start_time'] = (string)array_sum(explode( ' ' ,microtime()));
    }

    public function retry()
    {
        $this->secret();
        $this->setTemp();
    }

    public function stop()
    {
        $trace_file = $this->collector->getTraceFile();
        $trace_file_content = '';

        $start_line = (int)$this->collector->getTraceStart() - Collector::SIDE_LINE;
        $stop_line = $this->collector->getTraceEnd() ? (int)$this->collector->getTraceEnd() + Collector::SIDE_LINE : (int)$this->collector->getTraceStart() + Collector::SIDE_LINE ;

        if (is_file($trace_file)) {
            $trace_file_content = Io::cutFile(
                $trace_file,
                $start_line,
                $stop_line);
        }

        $this->collector->message['title'] = (string)$this->collector->getStreamTitle();
        $this->collector->message['status'] = (string)$this->collector->getStatus();
        $this->collector->message['time'] = (string)$this->collector->getTime();
        $this->collector->message['secret'] = (string)$this->collector->getSecret();
        $this->collector->message['temp_file'] = (string)$this->collector->getTemp() . Collector::STREAM_SUFFIX;
        $this->collector->message['trace_file'] = (string)$this->collector->getTraceFile();
        $this->collector->message['trace_file_content'] = (array)$trace_file_content;
        $this->collector->message['trace_start_line'] = (string)$start_line;
        $this->collector->message['trace_end_line'] = (string)$stop_line;
        $this->collector->message['trace_end_time'] = (string)array_sum(explode( ' ' ,microtime()));
        $this->collector->message['stream_type'] = (string)$this->collector->getTraceType();
        $this->collector->message['extend'] = (string)$this->collector->getExtend();
    }

    /**
     * @throws \Exception
     */
    private function config()
    {
        $config = file_get_contents(JunkMan::ROOT_PATH . DIRECTORY_SEPARATOR . 'config.json');
        $config = (array)json_decode($config, true);
        $this->collector->setConfig($config);
    }

    private function secret()
    {
        $secret = Helper::secret(Helper::randomCode());
        $this->collector->setSecret($secret);
    }

    private function setTemp()
    {
        $path = JunkMan::ROOT_PATH . DIRECTORY_SEPARATOR . 'Temp';
        $file = $path . DIRECTORY_SEPARATOR . $this->collector->getSecret();
        $this->collector->setTemp($file);
    }

    /**
     * @throws \Exception
     */
    private function setXdebug()
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
            [$this->collector->getTraceFile()]
        );
    }
}