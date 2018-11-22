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
use JunkMan\Container\Collecter;
use JunkMan\Driver\StreamDriver;
use JunkMan\Tool\Helper;

class OperateStream extends Singleton
{
    /**
     * @var Collecter
     */
    private $collecter = null;

    public function start($title = '')
    {
        $trace_file_info = Helper::multiQuery2Array(debug_backtrace(), ['function' => 'start', 'class' => 'JunkMan\Operation\OperateStream']);
        $this->collecter->setTraceFile($trace_file_info['file']);
        $this->collecter->setTraceStart($trace_file_info['line']);
        $this->collecter->setStreamTitle($title);
        new Decorate($this->collecter);
        xdebug_start_trace($this->collecter->getTemp());
    }

    public function flush()
    {

    }

    public function end()
    {
        $call_func_data = Helper::multiQuery2Array(debug_backtrace(), ['function' => 'end', 'class' => 'JunkMan\Operation\OperateStream']);
        $trace_to = $call_func_data['line'];
        $this->collecter->setTraceEnd($trace_to);
        StreamDriver::getInstance($this->collecter);
    }

    public function execute($data = null)
    {
        $this->collecter = new Collecter();
    }
}