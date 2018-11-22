<?php
/**
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/9/2
 * Time: 17:29
 */
namespace JunkMan\Driver;

use JunkMan\Abstracts\Singleton;
use JunkMan\Container\Collecter;
use JunkMan\Pipeline\TcpSender;
use JunkMan\Resolver\StreamAnalyze;
use JunkMan\Tool\Helper;

/**
 * Class StreamDriver
 * @package JunkMan\Driver
 */
class StreamDriver extends Singleton
{
    const SUFFIX = '.xt';

    private static $SENDER = null;

    /**
     * @var Collecter
     */
    private $collecter;

    public function execute($collecter = null)
    {
        $this->collecter = $collecter;
        $bool = $this->collecter->getConfig()['async'];
        if ($bool) {
            $this->async();
        } else {
            $this->sync();
        }
    }

    private function sync()
    {
        $file = $this->collecter->getTemp() . self::SUFFIX;
        $head = $this->collecter->getHeader();
        try {
            if (!is_file($file)) {
                throw new \Exception('not found stream file');
            }

            self::$SENDER = (new TcpSender(Collecter::SERVER, Collecter::PORT))->setHead($head);
            //trace
            $trace_file = $this->collecter->getTraceFile();
            if (is_file($trace_file)) {
                $trace_file = Helper::cutFile($trace_file,$this->collecter->getTraceStart() - 5,$this->collecter->getTraceEnd() + 5);
                self::$SENDER->write(['trace_file' => $trace_file]);
            }

            $handle = fopen($file, "r");
            if ($handle) {
                while (($buffer = fgets($handle)) !== false) {
                    $buffer = StreamAnalyze::index($buffer);
                    self::$SENDER->write($buffer);
                }
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        } finally {
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }

    private function async()
    {
        $file = $this->collecter->getTemp() . self::SUFFIX;
        $head = $this->collecter->getSOCKETHEAD();

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
                    throw new \Exception('not found stream file');
                }

                self::$SENDER = (new TcpSender(Collecter::SERVER, Collecter::PORT))->setHead($head);
                //trace
                $trace_file = $this->collecter->getTraceFile();
                if (is_file($trace_file)) {
                    $trace_file = Helper::cutFile($trace_file,$this->collecter->getTraceStart() - 5,$this->collecter->getTraceEnd() + 5);
                    self::$SENDER->write(['trace_file' => $trace_file]);
                }

                $handle = fopen($file, "r");
                if ($handle) {
                    while (($buffer = fgets($handle)) !== false) {
                        $buffer = StreamAnalyze::index($buffer);
                        self::$SENDER->write($buffer);
                    }
                }
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        } finally {
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }
}