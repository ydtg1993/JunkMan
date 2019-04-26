<?php
/**
 * Created by PhpStorm.
 * User: Hikki
 * Date: 2018/11/22 0022
 * Time: 上午 11:05
 */

namespace JunkMan\Operation;

use JunkMan\Abstracts\Singleton;
use JunkMan\Configuration\Labour;
use JunkMan\Container\Collector;
use JunkMan\E\JunkException;
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

    /**
     * @var Labour
     */
    private $labour;

    /**
     * @param string $title
     * @return string
     */
    public function start($title = '')
    {
        try {
            $trace_file_info = Helper::multiQuery2Array(debug_backtrace(), ['function' => 'start', 'class' => get_class()]);
            $this->labour = new Labour();
            $this->labour->run($this->collector, $title, $trace_file_info, Collector::TRACE_STREAM);

            xdebug_start_trace($this->collector->getTemp());

            set_error_handler(function ($error_no, $error_message, $error_file, $error_line) {
                xdebug_stop_trace();
                $this->collector->setDiscontinue(true);
                $this->collector->message['error'] = 1;
                $this->collector->setExtend([
                    'error_no' => $error_no,
                    'error_message' => $error_message,
                    'error_file' => $error_file,
                    'error_line' => $error_line
                ]);
                $this->collector->setStatus(Collector::STATUS_END);
                $this->collector->setTraceEnd($error_line);
                $this->labour->stop();
                $flag = $this->collector->getSENDER()->write($this->collector->message);
                if(!$flag && is_file($this->collector->getTemp().Collector::STREAM_SUFFIX)){
                    unlink($this->collector->getTemp() . Collector::STREAM_SUFFIX);
                }
            });
        } catch (JunkException $e) {
            return $e->getMessage();
        }
        return '';
    }

    /**
     * @return string
     */
    public function end()
    {
        try {
            if($this->collector->getDiscontinue()){
                return;
            }
            xdebug_stop_trace();
            $trace_file_info = Helper::multiQuery2Array(debug_backtrace(), ['function' => 'end', 'class' => get_class()]);
            $trace_to = $trace_file_info['line'];
            $this->collector->setTraceEnd($trace_to);
            $this->collector->setStatus(Collector::STATUS_END);
            $this->labour->stop();
            $flag = $this->collector->getSENDER()->write($this->collector->message);
            if(!$flag && is_file($this->collector->getTemp().Collector::STREAM_SUFFIX)){
                unlink($this->collector->getTemp() . Collector::STREAM_SUFFIX);
            }
        } catch (JunkException $e) {
            if(is_file($this->collector->getTemp().Collector::STREAM_SUFFIX)) {
                unlink($this->collector->getTemp() . Collector::STREAM_SUFFIX);
            }
            return $e->getMessage();
        }
        return '';
    }

    /**
     * @param null $data
     */
    protected function execute($data = null)
    {
        $this->collector = new Collector();
    }
}