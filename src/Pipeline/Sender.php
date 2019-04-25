<?php
/**
 * Created by PhpStorm.
 * User: Hikki
 * Date: 2018/8/20
 * Time: 23:08
 */

namespace JunkMan\Pipeline;

use JunkMan\Abstracts\Singleton;

/**
 * Class TcpSender
 * @package JunkMan\Pipeline
 */
class Sender extends Singleton
{
    private $ip;
    private $port;
    private $socket;

    /**
     * @param null $data
     */
    public function execute($data = null)
    {
        $this->ip = $data['server'];
        $this->port = $data['port'];
        $this->socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
        socket_set_block($this->socket);
        socket_set_option($this->socket, SOL_SOCKET, SO_LINGER, 1);
        if(socket_connect($this->socket,$this->ip,$this->port) == false){
            throw new \Exception('JunkManTransfer connect fail');
        }
    }

    /**
     * @param $data
     * @return $this
     * @throws \Exception
     */
    public function write($data)
    {
        if ($this->socket) {
            return (bool)socket_write($this->socket, json_encode($data));
        }
        return false;
    }

    public function close()
    {
        socket_close($this->socket);
    }

    public function __destruct()
    {
        socket_close($this->socket);
    }
}