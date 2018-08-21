<?php
/**
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/8/19
 * Time: 21:33
 */
namespace Core;

use App\libs\Stream\Core\Ems;
use App\libs\Stream\Core\StreamException;
use Stream\Stream;

class Application
{
    public function run()
    {
        try {
            Ems::examine();
            self::Config();
            self::secret();
            self::setTemp(Defined::getSECRET());
        }catch (StreamException $e){

        }
    }

    private static function Config()
    {
        $config = file_get_contents(Stream::ROOT_PATH.DIRECTORY_SEPARATOR.'Config.json');
        $config = json_decode($config, true);
        Defined::setConfig($config);
    }

    private static function secret()
    {
        $config = Defined::getConfig();
        $config_str = md5(join('@',$config)).'&'.Defined::getTIME();
        $secret = bin2hex($config_str);
        Defined::setSECRET($secret);
    }

    private static function setTemp($secret)
    {
        $file = Stream::ROOT_PATH.DIRECTORY_SEPARATOR.'Temp'.DIRECTORY_SEPARATOR.$secret;
        Defined::setTemp($file);
    }
}