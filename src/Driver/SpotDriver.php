<?php
/**
 * Created by PhpStorm.
 * User: Hikki
 * Date: 2018/9/2
 * Time: 17:29
 */
namespace JunkMan\Driver;

use JunkMan\Container\Collector;
use JunkMan\E\IoException;
use JunkMan\E\OperateException;
use JunkMan\Resolver\SpotAnalyze;

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

    /**
     * @param null $collector
     * @throws IoException
     * @throws OperateException
     */
    public function execute($collector = null)
    {
        $this->collector = $collector;
        try {
            $this->SENDER = $this->collector->getSENDER();
            $this->SENDER->write($this->collector->getHeader());
            $this->SENDER->write($this->collector->getTraceFileParagraph());

            SpotAnalyze::setLine($this->collector->getTraceStart());
            $content = SpotAnalyze::index($this->collector->getMessage());
            $this->SENDER->write($content);
        } catch (IoException $e){
            throw new IoException($e->getMessage());
        }catch (\Exception $e) {
            throw new OperateException($e->getMessage());
        } finally {
            $this->collector = null;
            $this->SENDER = null;
        }
    }
}