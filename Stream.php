<?php
/**
 * github:https://github.com/ydtg1993/Stream
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/8/19
 * Time: 21:14
 */

/**
 * Class Stream
 */
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
            foreach (glob(self::ROOT_PATH.DIRECTORY_SEPARATOR.'src/*') as $file) {
                if(is_file($file)) {
                    require_once $file;
                }
            }
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

    public function __destruct()
    {
        Driver::execute();
    }
}