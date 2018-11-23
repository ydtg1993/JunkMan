<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/22 0022
 * Time: 上午 11:05
 */

namespace JunkMan\Operation;

use JunkMan\Abstracts\Singleton;
use JunkMan\Configuration\Decorate;
use JunkMan\Container\Collector;
use JunkMan\Driver\SpotDriver;
use JunkMan\Tool\Helper;

class OperateSpot
{
    /**
     * @var Collector
     */
    private $collector = null;

    public function dot($title = '',$content = '')
    {
        $this->collector = new Collector();
        $trace_file_info = Helper::multiQuery2Array(debug_backtrace(), ['function' => 'dot', 'class' => get_class()]);
        $this->collector->setTraceFile($trace_file_info['file']);
        $this->collector->setTraceStart($trace_file_info['line']);
        $this->collector->setStreamTitle($title);
        $this->collector->setMessage($content);
        new Decorate($this->collector);
        (new SpotDriver())->execute($this->collector);
    }
}