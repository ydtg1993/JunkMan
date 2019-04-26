<?php
/**
 * Created by PhpStorm.
 * User: Hikki
 * Date: 2018/8/19
 * Time: 22:36
 */

namespace JunkMan\Container;

use JunkMan\Pipeline\Sender;
use JunkMan\Pipeline\Speaker;

date_default_timezone_set('Asia/Shanghai');

/**
 * Class Collector
 * @package JunkMan\Container
 */
class Collector
{
    private static $IP;
    private static $PORT;

    /**
     * suffix
     */
    const STREAM_SUFFIX = '.xt';

    /**
     * trace type
     */
    const TRACE_STREAM = 'stream';
    const TRACE_FLOOD = 'flood';
    const TRACE_SPOT = 'spot';

    /**
     * communication status
     */
    const STATUS_START = 'start';
    const STATUS_ING = 'running';
    const STATUS_END = 'end';

    /**
     * trace file cut paragraph
     */
    const SIDE_LINE = 1;

    public $message = [
        'agent' => 'server',
        'status' => '',
        'title' => '',
        'time' => '',
        'secret' => '',
        'process' => '',
        'error' => 0,
        'temp_file' => '',
        'trace_file' => '',
        'trace_file_content' => '',
        'trace_start_line' => '',
        'trace_end_line' => '',
        'trace_start_time' => '',
        'trace_end_time' => '',
        'stream_type' => '',
        'extend' => ''
    ];

    private $status;
    private $time;
    private $secret;
    private $process;
    private $temp;
    private $stream_title;
    private $trace_file;
    private $trace_start;
    private $trace_end;
    private $trace_type;
    private $extend;

    private $discontinue = false;

    public function __construct()
    {
        $this->setTime(time());
    }

    public function setConfig($config)
    {
        self::$IP = $config['ip'];
        self::$PORT = $config['port'];
    }

    /**
     * @return mixed
     */
    public function getSENDER()
    {
        return Sender::getInstance(['server' => self::$IP, 'port' => self::$PORT]);
    }

    public function getSpeaker()
    {
        return (new Speaker(['server' => self::$IP, 'port' => self::$PORT]));
    }

    /**
     * @return mixed
     */
    public function getTraceType()
    {
        return $this->trace_type;
    }

    /**
     * @param mixed $trace_type
     */
    public function setTraceType($trace_type)
    {
        $this->trace_type = $trace_type;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return mixed
     */
    public function getSecret()
    {
        return $this->secret;
    }

    public function getProcess()
    {
        return $this->process;
    }

    public function setProcess($process)
    {
        $this->process = $process;
        $this->message['process'] = $process;
    }

    /**
     * @param mixed $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return mixed
     */
    public function getTemp()
    {
        return $this->temp;
    }

    /**
     * @param mixed $temp
     */
    public function setTemp($temp)
    {
        $this->temp = $temp;
    }

    /**
     * @return mixed
     */
    public function getStreamTitle()
    {
        return $this->stream_title;
    }

    /**
     * @param mixed $stream_title
     */
    public function setStreamTitle($stream_title)
    {
        $this->stream_title = $stream_title;
    }

    /**
     * @return mixed
     */
    public function getTraceFile()
    {
        return $this->trace_file;
    }

    /**
     * @param mixed $trace_file
     */
    public function setTraceFile($trace_file)
    {
        $this->trace_file = $trace_file;
    }

    /**
     * @return mixed
     */
    public function getTraceStart()
    {
        return $this->trace_start;
    }

    /**
     * @param mixed $trace_start
     */
    public function setTraceStart($trace_start)
    {
        $this->trace_start = $trace_start;
    }

    /**
     * @return mixed
     */
    public function getTraceEnd()
    {
        return $this->trace_end;
    }

    /**
     * @param mixed $trace_end
     */
    public function setTraceEnd($trace_end)
    {
        $this->trace_end = $trace_end;
    }

    /**
     * @return mixed
     */
    public function getExtend()
    {
        return $this->extend;
    }

    /**
     * @param $extend
     */
    public function setExtend($extend)
    {
        $this->extend = json_encode($extend,JSON_FORCE_OBJECT);
    }

    public function setDiscontinue($discontinue)
    {
        $this->discontinue = (bool)$discontinue;
    }

    public function getDiscontinue()
    {
        return $this->discontinue;
    }
}