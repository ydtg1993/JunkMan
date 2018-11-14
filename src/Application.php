<?php
/**
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/8/19
 * Time: 21:33
 */

/**
 * Class Application
 */
class Application
{
    public function run()
    {
        self::config();
        Ems::examine();
        self::secret();
        self::setTemp(Defined::getSECRET());
        self::setSocketHead();
    }

    private static function config()
    {
        $config = file_get_contents(Stream::ROOT_PATH . DIRECTORY_SEPARATOR . 'config.json');
        $config = json_decode($config, true);
        Defined::setConfig($config);
    }

    private static function secret()
    {
        $config = Defined::getConfig();
        $app_code = isset($config['app_code']) ? $config['app_code'] : '';
        $config_str = $app_code . '@' . Defined::getTIME();
        $secret = bin2hex($config_str);
        Defined::setSECRET($secret);
    }

    private static function setTemp($secret)
    {
        $path = Stream::ROOT_PATH . DIRECTORY_SEPARATOR . 'Temp';
        if (!is_dir($path)) {
            mkdir($path);
        }
        $file = $path . DIRECTORY_SEPARATOR . $secret;
        Defined::setTemp($file);
    }

    private static function setSocketHead()
    {
        $data = [
            'stream_title' => Defined::getStreamTitle(),
            'client_ip' => $_SERVER['SERVER_ADDR'],
            'client_time' => Defined::getTIME(),
            'secret' => Defined::getSECRET()
        ];
        Defined::setSOCKETHEAD($data);
    }
}