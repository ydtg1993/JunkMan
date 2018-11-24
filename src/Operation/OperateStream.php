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
use JunkMan\E\OperateException;
use JunkMan\Instrument\Helper;

/**
 * Class OperateStream
 * @package JunkMan\Operation
 */
class OperateStream extends Singleton
{
    /**
     * @var Collector
     */
    private $collector = null;

    public function start($title = '')
    {
        try {
            $trace_file_info = Helper::multiQuery2Array(debug_backtrace(), ['function' => 'start', 'class' => get_class()]);
            $this->collector->setTraceFile($trace_file_info['file']);
            $this->collector->setTraceStart($trace_file_info['line']);
            $this->collector->setStreamTitle($title);
            $this->collector->setTraceType(Collector::TRACE_SPOT);
            new Decorate($this->collector);
            xdebug_start_trace($this->collector->getTemp());

            set_error_handler(function ($error_no, $error_message, $error_file, $error_line){
                xdebug_stop_trace();
                $this->collector->setErrorMessage([
                    'error_no' => $error_no,
                    'error_message' => $error_message,
                    'error_file' => $error_file,
                    'error_line' => $error_line
                ]);
                OperateError::getInstance($this->collector)->dot();
                throw new \Exception(json_encode($this->collector->getErrorMessage()));
            });
        } catch (\Exception $e) {
            throw new OperateException($e->getMessage());
        }
    }

    public function end()
    {
        try {
            xdebug_stop_trace();
            $call_func_data = Helper::multiQuery2Array(debug_backtrace(), ['function' => 'end', 'class' => get_class()]);
            $trace_to = $call_func_data['line'];
            $this->collector->setTraceEnd($trace_to);
            StreamDriver::getInstance($this->collector);
        } catch (\Exception $e) {
            throw new OperateException($e->getMessage());
        }
    }

    public function execute($data = null)
    {
        $this->collector = new Collector();
    }
}