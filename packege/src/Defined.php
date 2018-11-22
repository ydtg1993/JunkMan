<?php
/**
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/8/19
 * Time: 22:36
 */

/**
 * Class Defined
 */
class Defined
{
    const SERVER = "127.0.0.1";
    const PORT = "55533";

    private static $TIME;
    private static $SECRET;
    private static $config;
    private static $temp;
    private static $stream_title;
    private static $trace_file;
    private static $trace_start;
    private static $trace_end;

    /**
     * @return mixed
     */
    public static function getTraceStart()
    {
        return self::$trace_start;
    }

    /**
     * @param mixed $trace_start
     */
    public static function setTraceStart($trace_start): void
    {
        self::$trace_start = $trace_start;
    }

    /**
     * @return mixed
     */
    public static function getTraceEnd()
    {
        return self::$trace_end;
    }

    /**
     * @param mixed $trace_end
     */
    public static function setTraceEnd($trace_end): void
    {
        self::$trace_end = $trace_end;
    }

    private static $ENV;
    private static $SOCKET_HEAD;

    public static function getTraceFile()
    {
        return self::$trace_file;
    }

    public static function setTraceFile($file)
    {
        self::$trace_file = $file;
    }

    /**
     * @return mixed
     */
    public static function getSOCKETHEAD()
    {
        return self::$SOCKET_HEAD;
    }

    /**
     * @param mixed $SOCKET_HEAD
     */
    public static function setSOCKETHEAD(array $SOCKET_HEAD)
    {
        self::$SOCKET_HEAD = json_encode($SOCKET_HEAD);
    }

    /**
     * @return mixed
     */
    public static function getStreamTitle()
    {
        return self::$stream_title;
    }

    /**
     * @param mixed $stream_title
     */
    public static function setStreamTitle($stream_title)
    {
        self::$stream_title = $stream_title;
    }

    /**
     * @return mixed
     */
    public static function getENV()
    {
        return self::$ENV;
    }

    /**
     * @param mixed $ENV
     */
    public static function setENV($ENV)
    {
        self::$ENV = $ENV;
    }

    /**
     * @return mixed
     */
    public static function getTemp()
    {
        return self::$temp;
    }

    /**
     * @param mixed $temp
     */
    public static function setTemp($temp)
    {
        self::$temp = $temp;
    }

    /**
     * @return mixed
     */
    public static function getTIME()
    {
        return self::$TIME;
    }

    /**
     * @param mixed $TIME
     */
    public static function setTIME($TIME)
    {
        self::$TIME = $TIME;
    }

    /**
     * @return mixed
     */
    public static function getSECRET()
    {
        return self::$SECRET;
    }

    /**
     * @param mixed $SECRET
     */
    public static function setSECRET($SECRET)
    {
        self::$SECRET = $SECRET;
    }

    /**
     * @return mixed
     */
    public static function getConfig()
    {
        return self::$config;
    }

    /**
     * @param array $config
     * @throws \Exception
     */
    public static function setConfig(array $config)
    {
        if(empty($config)){
            throw new \Exception('the config is empty');
        }
        self::$config = $config;
    }
}