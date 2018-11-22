<?php
/**
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/8/19
 * Time: 21:33
 */

namespace JunkMan\Configuration;

use JunkMan\Container\Collecter;
use JunkMan\JunkMan;
use JunkMan\Tool\Helper;

/**
 * Class Application
 */
class Decorate
{
    /**
     * @var Collecter
     */
    private $collecter;

    public function __construct($collecter)
    {
        $this->collecter = $collecter;
        $this->config();
        $this->setXdebug();
        $this->secret();
        $this->setTemp();
        $this->setHead();
    }

    private function config()
    {
        $config = file_get_contents(JunkMan::ROOT_PATH . DIRECTORY_SEPARATOR . 'config.json');
        $config = (array)json_decode($config, true);
        $this->collecter->setConfig($config);
    }

    private function secret()
    {
        $config = $this->collecter->getConfig();
        $secret = Helper::secret($config['app_code'], $this->collecter->getTIME());
        $this->collecter->setSecret($secret);
    }

    private function setTemp()
    {
        $path = JunkMan::ROOT_PATH . DIRECTORY_SEPARATOR . 'Temp';
        if (!is_dir($path)) {
            mkdir($path);
        }
        $file = $path . DIRECTORY_SEPARATOR . $this->collecter->getSecret();
        $this->collecter->setTemp($file);
    }

    private function setHead()
    {
        $data = [
            'header' => [
                'stream_title' => $this->collecter->getStreamTitle(),
                'time' => $this->collecter->getTIME(),
                'secret' => $this->collecter->getSecret(),
                'trace_file' => $this->collecter->getTraceFile()
            ]
        ];
        $this->collecter->setHeader($data);
    }

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
            [$this->collecter->getTraceFile()]
        );
    }
}