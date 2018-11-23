<?php
/**
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/8/19
 * Time: 21:33
 */

namespace JunkMan\Configuration;

use JunkMan\Container\Collector;
use JunkMan\JunkMan;
use JunkMan\Instrument\Helper;

/**
 * Class Decorate
 * @package JunkMan\Configuration
 */
class Decorate
{
    /**
     * @var Collector
     */
    private $collector;

    public function __construct($collector = null)
    {
        $this->collector = $collector;
        $this->config();
        $this->setXdebug();
        $this->secret();
        $this->setTemp();
        $this->setHead();
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
        $config = $this->collector->getConfig();
        $secret = Helper::secret($config['app_code'], $this->collector->getTime());
        $this->collector->setSecret($secret);
    }

    private function setTemp()
    {
        $path = JunkMan::ROOT_PATH . DIRECTORY_SEPARATOR . 'Temp';
        if (!is_dir($path)) {
            mkdir($path);
        }
        $file = $path . DIRECTORY_SEPARATOR . $this->collector->getSecret();
        $this->collector->setTemp($file);
    }

    private function setHead()
    {
        $data = [
            'header' => [
                'stream_title' => $this->collector->getStreamTitle(),
                'time' => $this->collector->getTime(),
                'secret' => $this->collector->getSecret(),
                'trace_file' => $this->collector->getTraceFile(),
                'stream_type' => $this->collector->getTraceType()
            ]
        ];
        $this->collector->setHeader($data);
    }

    /**
     * @throws \Exception
     */
    private function setXdebug()
    {
        if (!function_exists('xdebug_set_filter')) {
            throw new \Exception('Need to install Xdebug version >= 2.6');
        }
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