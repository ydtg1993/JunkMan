<?php
/**
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/9/2
 * Time: 17:29
 */

/**
 * Class Driver
 */
class Driver
{
    const SUFFIX = '.xt';

    private static $SENDER = null;

    public static function execute()
    {
        $bool = Defined::getConfig()['async'];
        if ($bool) {
            self::async();
        } else {
            self::sync();
        }
    }

    private static function sync()
    {
        $file = Defined::getTemp() . self::SUFFIX;
        $head = Defined::getSOCKETHEAD();
        try {
            self::$SENDER = (new Sender(Defined::SERVER, Defined::PORT))->setHead($head);
            //trace
            $trace_file = Defined::getTraceFile();
            if (is_file($trace_file)) {
                $trace_file = serialize(file_get_contents($trace_file));
                self::$SENDER->write(json_encode([
                    'trace_file' => $trace_file
                ]));
            }
            if (!is_file($file)) {
                throw new \Exception('not found stream file');
            }

            $handle = fopen($file, "r");
            if ($handle) {
                while (($buffer = fgets($handle)) !== false) {
                    $buffer = Analyze::index($buffer);
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

    private static function async()
    {
        $file = Defined::getTemp() . self::SUFFIX;
        $head = Defined::getSOCKETHEAD();

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
                self::$SENDER = (new Sender(Defined::SERVER, Defined::PORT))->setHead($head);
                //trace
                $trace_file = Defined::getTraceFile();
                if (is_file($trace_file)) {
                    $trace_file = serialize(file_get_contents($trace_file));
                    self::$SENDER->write(json_encode([
                        'trace_file' => $trace_file
                    ]));
                }
                if (!is_file($file)) {
                    throw new \Exception('not found stream file');
                }

                $handle = fopen($file, "r");
                if ($handle) {
                    while (($buffer = fgets($handle)) !== false) {
                        $buffer = Analyze::index($buffer);
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