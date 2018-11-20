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
    private $ip;
    private $port;
    private $socket;
    protected static $boot = [];

    public function __construct($ip, $port)
    {
        $this->ip = $ip;
        $this->port = $port;

        $create_errno = '';
        $create_errstr = '';
        $address = 'udp://' . $this->ip . ':' . $this->port;
        $this->socket = stream_socket_client($address, $create_errno, $create_errstr, STREAM_SERVER_BIND);
        if ($this->socket < 0) {
            throw new \Exception('create socket fail:' . $create_errno . $create_errstr);
        }
    }

    public function setHead($head)
    {
        fwrite($this->socket, $head);

        return $this;
    }

    public function write($file)
    {
        //trace
        $trace_file = Defined::getTraceFile();
        if (is_file($trace_file)) {
            $data = serialize(file_get_contents($trace_file));
            fwrite($this->socket, $data);
        }

        //boday
        if (!is_file($file)) {
            throw new \Exception('not found stream file');
        }

        $handel = fopen($file, "r");
        if ($handel) {
            while (($buffer = fgets($handel)) !== false) {
                Analyze::index($buffer);
                if (!$buffer) {
                    continue;
                }

                fwrite($this->socket, $buffer);
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