<?php
/**
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/8/19
 * Time: 21:14
 */
namespace Stream;

use Core\Defined;
use Mockery\Exception;
use Core\Application;

class Stream
{
    private static $instance = null;

    const ROOT_PATH = __DIR__;

    private function __construct(){

    }

    static public function start()
    {
        self::init();
        xdebug_start_trace(Defined::getTemp());
    }

    static public function end()
    {
        xdebug_stop_trace();
        self::$instance = null;
    }

    private static function init()
    {
        if(self::$instance === null){
            spl_autoload_register([self::class,'autoLoad']);
            (new Application)->run();
            self::$instance = new self();
            return;
        }
        throw new Exception('Stream have already started！');
    }

    private static function autoLoad($class)
    {
        require_once self::ROOT_PATH.DIRECTORY_SEPARATOR.$class.'.php';
    }

    private function __destruct()
    {
        // TODO: Implement __destruct() method.
        echo 111;
    }
}