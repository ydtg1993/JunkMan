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
        $address = 'tcp://' . $this->ip . ':' . $this->port;
        $this->socket = stream_socket_client($address, $create_errno, $create_errstr, STREAM_SERVER_BIND);
    }

    public function setHead($head)
    {
        fwrite($this->socket, $head);

        return $this;
    }

    public function write($data)
    {
        if(!$data){
            return $this;
        }
        fwrite($this->socket, $data);
        return $this;
    }

    public function __destruct()
    {
        fclose($this->socket);
    }
}