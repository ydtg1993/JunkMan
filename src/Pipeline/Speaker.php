<?php
/**
 * Created by PhpStorm.
 * User: Hikki
 * Date: 2018/8/20
 * Time: 23:08
 */
namespace JunkMan\Pipeline;

use JunkMan\E\JunkException;
/**
 * Class TcpSender
 * @package JunkMan\Pipeline
 */
class Speaker
{
    private $ip;
    private $port;
    private $socket;

    /**
     * Speaker constructor.
     * @param null $data
     * @throws \Exception
     */
    public function __construct($data = null)
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
        try {
            fwrite($this->socket, json_encode($data,JSON_FORCE_OBJECT));
            stream_socket_shutdown($this->socket,STREAM_SOCK_DGRAM);
        }catch (JunkException $e){
            throw new JunkException($e->getMessage());
        }
    }
}