<?php
/**
 * Created by PhpStorm.
 * User: ydtg1
 * Date: 2018/8/20
 * Time: 23:08
 */

/**
 * Class Sender
 */
class Sender
{
    const BUFFER_LEN = 1024;
    private $ip;
    private $port;
    private $socket;
    protected static $boot = [];

    public function __construct($ip, $port)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->start();
    }

    public function start()
    {
        $create_errno = '';
        $create_errstr = '';
        $address = 'udp://'.$this->ip.':'.$this->port;
        $this->socket = stream_socket_client($address, $create_errno, $create_errstr,STREAM_SERVER_BIND);
        if ($this->socket < 0) {
            throw new \Exception('create socket fail:'.$create_errno.$create_errstr);
        }

        return $this;
    }

    public function setHead($head)
    {
        return $this;
    }

    public function write($file)
    {
        if (!is_file($file)) {
            throw new \Exception('not found stream file');
        }

        $handel = fopen($file, "r");
        if ($handel) {
            while (($buffer = fgets($handel, self::BUFFER_LEN)) !== false) {
                if ($buffer) {
                    fwrite($this->socket, $buffer);
                }
            }

            fclose($handel);
        }
    }

    public function setBoot()
    {
        //var_dump(self::$boot);exit;
    }

    public function __destruct()
    {
        $this->setBoot();
        fclose($this->socket);
    }
}

class Filter extends Sender
{
    private static $at = ['=>', '->'];

    public static function analyze(&$content)
    {
        $temp = strstr($content, self::$at[1]);
        if ($temp) {
            $content = $temp;
        } else {
            $content = strstr($content, self::$at[0]);
        }

        if (!$content) {
            return;
        }

        $content_array = explode(' ', $content);
        $file = end($content_array);
        $num = strrpos($file, ':');
        $file = substr($file, 0, $num);
        if (false == in_array($file, self::$boot) && is_file($file)) {
            self::$boot[] = $file;
        }
    }
}