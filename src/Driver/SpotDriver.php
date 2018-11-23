<?php
/**
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/9/2
 * Time: 17:29
 */
namespace JunkMan\Driver;

use JunkMan\Abstracts\Singleton;
use JunkMan\Container\Collector;
use JunkMan\E\OperateException;
use JunkMan\Pipeline\TcpSender;
use JunkMan\Resolver\SpotAnalyze;
use JunkMan\Tool\Helper;

/**
 * Class StreamDriver
 * @package JunkMan\Driver
 */
class SpotDriver implements DriverInterface
{
    private $SENDER = null;

    /**
     * @var Collector
     */
    private $collector;

    public function execute($collector = null)
    {
        $this->collector = $collector;
        $head = $this->collector->getHeader();
        try {

            $this->SENDER = (new TcpSender(Collector::SERVER, Collector::PORT))->setHead($head);
            //trace
            $trace_file = $this->collector->getTraceFile();
            if (is_file($trace_file)) {
                $trace_file = Helper::cutFile($trace_file,$this->collector->getTraceStart() - 5,$this->collector->getTraceStart() + 5);
                $this->SENDER->write(['trace_file' => $trace_file]);
            }
            SpotAnalyze::setLine($this->collector->getTraceStart());
            $content = SpotAnalyze::index($this->collector->getMessage());
            $this->SENDER->write($content);
        } catch (\Exception $e) {
            throw new OperateException($e->getMessage());
        } finally {
            $this->collector = null;
            $this->SENDER = null;
        }
    }
}