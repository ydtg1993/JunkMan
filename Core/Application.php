<?php
/**
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/8/19
 * Time: 21:33
 */

namespace Core;

use App\libs\Stream\Core\Ems;
use Stream\Stream;

class Application
{
    public function run()
    {
        self::config();
        Ems::examine();
        self::secret();
        self::setTemp(Defined::getSECRET());
    }

    private static function config()
    {
        $data = include_once Stream::ROOT_PATH . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'Config.php';
        Defined::setConfig($data);
    }

    private static function secret()
    {
        $config = Defined::getConfig();
        $config_str = md5(join('@', $config)) . '&' . Defined::getTIME();
        $secret = bin2hex($config_str);
        Defined::setSECRET($secret);
    }

    private static function setTemp($secret)
    {
        $file = Stream::ROOT_PATH . DIRECTORY_SEPARATOR . 'Temp' . DIRECTORY_SEPARATOR . $secret;
        Defined::setTemp($file);
    }
}