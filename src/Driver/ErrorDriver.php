<?php
/**
 * Created by PhpStorm.
 * User: Hikki
 * Date: 2018/9/2
 * Time: 17:29
 */
namespace JunkMan\Driver;

use JunkMan\Abstracts\Singleton;
use JunkMan\Container\Collector;
use JunkMan\E\IoException;
use JunkMan\E\OperateException;
use JunkMan\Pipeline\Sender;

/**
 * Class StreamDriver
 * @package JunkMan\Driver
 */
class ErrorDriver extends Singleton implements DriverInterface
{
    /**
     * @var Sender
     */
    private $SENDER = null;

    /**
     * @var Collector
     */
    private $collector;

    /**
     * @param null $collector
     * @throws OperateException
     */
    public function execute($collector = null)
    {
        $this->collector = $collector;
        $file = $this->collector->getTemp();
        try {
            if (!is_file($file)) {
                throw new IoException('not found stream file');
            }
            $this->collector->setSENDER();
            $this->SENDER = $this->collector->getSENDER();
            $this->SENDER->write($this->collector->getHeader());
            $this->SENDER->write($this->collector->getTraceFileParagraph());

            $this->SENDER->write($this->collector->getErrorMessage());
        } catch (\Exception $e) {
            throw new OperateException($e->getMessage());
        } finally {
            $this->SENDER = null;
            $this->collector = null;
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }

}