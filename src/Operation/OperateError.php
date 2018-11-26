<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/22 0022
 * Time: 上午 11:05
 */

namespace JunkMan\Operation;

use JunkMan\Abstracts\Singleton;
use JunkMan\Container\Collector;
use JunkMan\Driver\ErrorDriver;
use JunkMan\E\OperateException;

/**
 * Class OperateError
 * @package JunkMan\Operation
 */
class OperateError extends Singleton
{
    /**
     * @var Collector
     */
    private $collector = null;

    public function dot()
    {
        try {
            $this->collector->setTraceType(Collector::TRACE_ERR);
            $header = $this->collector->getHeader();
            if(!empty($header) && isset($header['header']['stream_type'])){
                $header['header']['stream_type'] = $this->collector->getTraceType();
                $this->collector->setHeader($header);
            }
            ErrorDriver::getInstance($this->collector);
        } catch (\Exception $e) {
            throw new OperateException($e->getMessage());
        }
    }

    protected function execute($collector = null)
    {
        $this->collector = $collector;
    }
}