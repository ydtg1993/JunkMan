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
use JunkMan\Instrument\Helper;
use JunkMan\JunkMan;
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

    /**
     * @param $collector
     */
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

    /**
     * @throws IoException
     * @throws OperateException
     */
    private function sync()
    {
        $file = $this->collector->getTemp();
        $this->collector->setSENDER();
        try {
            if (!is_file($file)) {
                throw new IoException('not found stream file');
            }

            $this->SENDER = $this->collector->getSENDER();
            $this->SENDER->write($this->collector->getHeader());
            $this->SENDER->write($this->collector->getTraceFileParagraph());

            $handle = fopen($file, "r");
            if ($handle) {
                StreamAnalyze::setTraceFile($this->collector->getTraceFile());

                $handle = fopen($file, "r");
                while (!feof($handle)) {
                    $data = StreamAnalyze::index(fgets($handle));
                    $this->SENDER->write($data);
                }
                fclose($handle);
            }
        }catch (IoException $e){
            throw new IoException($e->getMessage());
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

    /**
     * @throws OperateException
     */
    private function async()
    {
        try {
            $header = Helper::secret($this->collector->getHeader());
            $config = Helper::secret($this->collector->getConfig());
            $trace_start = $this->collector->getTraceStart();
            $trace_end = $this->collector->getTraceEnd();

            $execute_file = JunkMan::ROOT_PATH.'/Pipeline/AsyncSender.php';
            $command = JunkMan::PHP." {$execute_file} -h {$header} -c {$config} -s {$trace_start} -t {$trace_end}  > /dev/null &";
            shell_exec($command);
        } catch (\Exception $e) {
            throw new OperateException($e->getMessage());
        }
    }
}