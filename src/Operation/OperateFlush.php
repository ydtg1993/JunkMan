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
use JunkMan\Driver\ErrorDriver;
use JunkMan\Driver\FlushDriver;
use JunkMan\Driver\StreamDriver;
use JunkMan\E\IoException;
use JunkMan\E\OperateException;
use JunkMan\Instrument\Helper;

/**
 * Class OperateStream
 * @package JunkMan\Operation
 */
class OperateFlush extends Singleton
{
    /**
     * @var Collector
     */
    private $collector = null;

    /**
     * @param string $title
     * @throws IoException
     * @throws OperateException
     */
    public function start($title = '')
    {
        try {
            $trace_file_info = Helper::multiQuery2Array(debug_backtrace(), ['function' => 'start', 'class' => get_class()]);
            Labour::run($this->collector, $title, $trace_file_info, Collector::TRACE_FLUSH);

            xdebug_start_trace(trim($this->collector->getTemp(), Collector::STREAM_SUFFIX));

            set_error_handler(function ($error_no, $error_message, $error_file, $error_line) {
                xdebug_stop_trace();
                $this->collector->setErrorMessage([
                    'error_no' => $error_no,
                    'error_message' => $error_message,
                    'error_file' => $error_file,
                    'error_line' => $error_line
                ]);
                $this->collector->setTraceType(Collector::TRACE_ERR);
                Labour::stop();
                ErrorDriver::getInstance($this->collector);
                throw new \Exception(json_encode($this->collector->getErrorMessage()));
            });
        } catch (IoException $e) {
            throw new IoException($e->getMessage());
        } catch (\Exception $e) {
            throw new OperateException($e->getMessage());
        }
    }

    /**
     * @throws IoException
     * @throws OperateException
     */
    public function refurbish()
    {
        try {
            xdebug_stop_trace();
            $trace_file_info = Helper::multiQuery2Array(debug_backtrace(), ['function' => 'refurbish', 'class' => get_class()]);
            $trace_to = $trace_file_info['line'];
            $this->collector->setTraceEnd($trace_to);

            Labour::stop();
            (new FlushDriver())->execute($this->collector);

            xdebug_start_trace($this->collector->getTemp());
        } catch (IoException $e) {
            throw new IoException($e->getMessage());
        } catch (\Exception $e) {
            throw new OperateException($e->getMessage());
        }
    }

    /**
     * @throws IoException
     * @throws OperateException
     */
    public function end()
    {
        try {
            xdebug_stop_trace();
            $trace_file_info = Helper::multiQuery2Array(debug_backtrace(), ['function' => 'end', 'class' => get_class()]);
            $trace_to = $trace_file_info['line'];
            $this->collector->setTraceEnd($trace_to);
            Labour::stop();

            (new FlushDriver())->execute($this->collector);
        } catch (IoException $e) {
            throw new IoException($e->getMessage());
        } catch (\Exception $e) {
            throw new OperateException($e->getMessage());
        }
    }

    /**
     * @param null $data
     */
    protected function execute($data = null)
    {
        $this->collector = new Collector();
    }
}