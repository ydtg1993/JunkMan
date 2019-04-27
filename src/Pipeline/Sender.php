<?php
/**
 * Created by PhpStorm.
 * User: Hikki
 * Date: 2018/8/20
 * Time: 23:08
 */

namespace JunkMan\Pipeline;

use JunkMan\Abstracts\Singleton;
use JunkMan\E\JunkException;

/**
 * Class TcpSender
 * @package JunkMan\Pipeline
 */
class Sender extends Singleton
{
    private $ip;
    private $port;
    private $socket;

    public function execute($data = null)
    {
        $this->ip = $data['server'];
        $this->port = $data['port'];
        $this->socket = stream_socket_client("tcp://{$this->ip}:{$this->port}",$errno,$errstr,3);
        if(!$this->socket){
            throw new JunkException('JunkManTransfer connect fail');
        }
        stream_set_blocking($this->socket,true);
    }

    /**
     * @param $data
     * @return $this
     * @throws \Exception
     */
    public function write($data)
    {
        if ($this->socket) {
            sleep(1);
            return (bool)fwrite($this->socket, json_encode($data));;
        }
        return false;
    }

    public function close()
    {
        stream_socket_shutdown($this->socket,STREAM_SOCK_DGRAM);
    }

    public function __destruct()
    {
        stream_socket_shutdown($this->socket,STREAM_SOCK_DGRAM);
    }
}