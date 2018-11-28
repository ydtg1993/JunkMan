<?php
/**
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/9/2
 * Time: 17:29
 */
namespace JunkMan\Driver;

use JunkMan\Abstracts\Singleton;
use JunkMan\Configuration\Labour;
use JunkMan\Container\Collector;
use JunkMan\E\IoException;
use JunkMan\E\OperateException;
use JunkMan\Instrument\Io;
use JunkMan\Resolver\StreamAnalyze;

/**
 * Class StreamDriver
 * @package JunkMan\Driver
 */
class StreamDriver extends Singleton implements DriverInterface
{
    private $SENDER = null;

    /**
     * @var Collector
     */
    private $collector;

    public function execute($collector = null)
    {
        $this->collector = $collector;
        $bool = $this->collector->getConfig()['async'];
        if ($bool) {
            $this->async();
        } else {
            $this->sync();
        }
    }

    private function sync()
    {
        $file = $this->collector->getTemp() . Collector::STREAM_SUFFIX;
        try {
            if (!is_file($file)) {
                throw new IoException('not found stream file');
            }

            $this->SENDER = $this->collector->getSENDER();
            $this->SENDER->write($this->collector->getHeader());

            $handle = fopen($file, "r");
            if ($handle) {
                StreamAnalyze::setTemp($this->collector->getTemp());
                StreamAnalyze::setTraceFile($this->collector->getTraceFile());

                $handle = fopen($file, "r");
                while (!feof($handle)) {
                    $data = StreamAnalyze::index(fgets($handle));
                    $this->SENDER->write($data);
                }
                fclose($handle);
            }
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

    private function async()
    {
        $file = $this->collector->getTemp() . Collector::STREAM_SUFFIX;

        if (!function_exists('pcntl_fork')) {
            throw new \Exception('Need to install pcntl');
        }

        try {
            $pid = pcntl_fork();
            if ($pid == -1) {
                throw new \Exception('could not fork');
            } else if ($pid) {
                pcntl_wait($status);
            } else {
                if (!is_file($file)) {
                    throw new IoException('not found stream file');
                }

                $this->SENDER = $this->collector->getSENDER();
                $this->SENDER->write($this->collector->getHeader());

                $handle = fopen($file, "r");
                if ($handle) {
                    StreamAnalyze::setTemp($this->collector->getTemp());
                    StreamAnalyze::setTraceFile($this->collector->getTraceFile());

                    $handle = fopen($file, "r");
                    while (!feof($handle)) {
                        $data = StreamAnalyze::index(fgets($handle));
                        $this->SENDER->write($data);
                    }
                    fclose($handle);
                }
            }
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