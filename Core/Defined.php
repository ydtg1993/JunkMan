<?php
/**
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/8/19
 * Time: 22:36
 */

namespace Core;

use Mockery\Exception;

class Defined
{
    private static $TIME;
    private static $SECRET;
    private static $SERVER;
    private static $PORT;
    private static $config;
    private static $temp;

    private static $INI;
    private static $PHP;
    private static $ENV;

    /**
     * @return mixed
     */
    public static function getPHP()
    {
        return self::$PHP;
    }

    /**
     * @param mixed $PHP
     */
    public static function setPHP($PHP)
    {
        self::$PHP = $PHP;
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
    public static function getINI()
    {
        return self::$INI;
    }

    /**
     * @param mixed $INI
     */
    public static function setINI($INI)
    {
        self::$INI = $INI;
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
    public static function getSERVER()
    {
        return self::$SERVER;
    }

    /**
     * @param mixed $SERVER
     */
    public static function setSERVER($SERVER)
    {
        self::$SERVER = $SERVER;
    }

    /**
     * @return mixed
     */
    public static function getPORT()
    {
        return self::$PORT;
    }

    /**
     * @param mixed $PORT
     */
    public static function setPORT($PORT)
    {
        self::$PORT = $PORT;
    }

    /**
     * @return mixed
     */
    public static function getConfig()
    {
        return self::$config;
    }

    /**
     * @param mixed $config
     */
    public static function setConfig(array $config)
    {
        if(empty($config)){
            throw new Exception('the config is empty');
        }
        self::$config = $config;
    }
}