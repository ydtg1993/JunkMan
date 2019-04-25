<?php
/**
 * Created by PhpStorm.
 * User: Hikki
 * Date: 2018/8/20
 * Time: 23:08
 */
namespace JunkMan\Pipeline;


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
        try {
            socket_write($this->socket, json_encode($data));
            socket_close($this->socket);
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }
}