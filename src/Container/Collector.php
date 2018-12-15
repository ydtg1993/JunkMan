<?php
/**
 * Created by PhpStorm.
 * User: Hikki
 * Date: 2018/8/19
 * Time: 22:36
 */
namespace JunkMan\Container;

use JunkMan\Pipeline\PipelineInterface;
use JunkMan\Pipeline\Sender;

date_default_timezone_set('Asia/Shanghai');

/**
 * Class Collector
 * @package JunkMan\Container
 */
class Collector
{
    const SERVER = "www.jinono.com";
    const PORT = "1993";
    const STREAM_SUFFIX = '.xt';

    const TRACE_STREAM = 'stream';
    const TRACE_FLUSH = 'flush';
    const TRACE_SPOT = 'spot';
    const TRACE_ERR = 'error';

    const SIDE_LINE = 1;

    private $time;
    private $secret;
    private $config;
    private $temp;
    private $stream_title;
    private $trace_file;
    private $trace_file_paragraph;
    private $trace_start;
    private $trace_end;
    private $header;
    private $message;
    private $trace_type;
    private $error_message;

    private $SENDER;

    public function __construct()
    {
        $this->setTime(time());
    }

    /**
     * @return mixed
     */
    public function getSENDER()
    {
        return $this->SENDER;
    }

    /**
     * @return mixed
     */
    public function setSENDER()
    {
        $this->SENDER = Sender::getInstance(['server'=>self::SERVER,'port'=>self::PORT]);
        return $this->SENDER;
    }

    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->error_message;
    }

    /**
     * @param mixed $error_message
     */
    public function setErrorMessage($error_message)
    {
        $this->error_message = $error_message;
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
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
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
    public function getConfig()
    {
        return $this->config;
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
    public function getTraceFileParagraph()
    {
        return $this->trace_file_paragraph;
    }

    /**
     * @param mixed $trace_file_paragraph
     */
    public function setTraceFileParagraph($trace_file_paragraph)
    {
        $this->trace_file_paragraph = $trace_file_paragraph;
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
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param mixed $header
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }

    /**
     * @param array $config
     * @throws \Exception
     */
    public function setConfig(array $config)
    {
        if(empty($config)){
            throw new \Exception('the config is empty');
        }
        $this->config = $config;
    }
}