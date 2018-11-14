<?php
/**
 * github:https://github.com/ydtg1993/Stream
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/8/19
 * Time: 21:14
 */
namespace Stream;

use Pipe\Driver;
use Core\Defined;
use Core\Application;

class Stream
{
    private static $instance = null;

    const ROOT_PATH = __DIR__;

    private function __construct(){

    }

    static public function start($title = '')
    {
        self::init();
        Defined::setStreamTitle($title);
        xdebug_start_trace(Defined::getTemp());
    }

    static public function end()
    {
        xdebug_stop_trace();
        self::$instance = null;
    }

    static public function log($data,$title = '')
    {
        //TODO
    }

    private static function init()
    {
        if(self::$instance === null){
            spl_autoload_register([self::class,'autoLoad']);
            try {
                (new Application)->run();
            }catch (\Exception $e){
                throw new \Exception($e->getMessage());
            }
            self::$instance = new self();
            return;
        }

        throw new \Exception('Stream have already started！');
    }

    private static function autoLoad($class)
    {
        $file = self::ROOT_PATH.DIRECTORY_SEPARATOR.$class.'.php';
        if(is_file($file)) {
            require_once $file;
        }
    }

    public function __destruct()
    {
        Driver::execute();
    }
}