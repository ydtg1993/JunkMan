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
use JunkMan\Driver\StreamDriver;
use JunkMan\Tool\Helper;

class OperateStream extends Singleton
{
    /**
     * @var Collector
     */
    private $collector = null;

    public function start($title = '')
    {
        $trace_file_info = Helper::multiQuery2Array(debug_backtrace(), ['function' => 'start', 'class' => 'JunkMan\Operation\OperateStream']);
        $this->collector->setTraceFile($trace_file_info['file']);
        $this->collector->setTraceStart($trace_file_info['line']);
        $this->collector->setStreamTitle($title);
        new Decorate($this->collector);
        xdebug_start_trace($this->collector->getTemp());
    }

    public function flush()
    {

    }

    public function end()
    {
        $call_func_data = Helper::multiQuery2Array(debug_backtrace(), ['function' => 'end', 'class' => 'JunkMan\Operation\OperateStream']);
        $trace_to = $call_func_data['line'];
        $this->collector->setTraceEnd($trace_to);
        StreamDriver::getInstance($this->collector);
    }

    public function execute($data = null)
    {
        $this->collector = new Collector();
    }
}