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

        $create_errno = '';
        $create_errstr = '';
        $address = 'tcp://' . $this->ip . ':' . $this->port;
        try {
            $this->socket = stream_socket_client($address, $create_errno, $create_errstr, STREAM_SERVER_BIND);
        }catch (\Exception $e){
            throw new \Exception($create_errno.$e->getMessage());
        }
    }

    /**
     * @param $data
     * @return $this
     * @throws \Exception
     */
    public function write($data)
    {
        if(!$data){
            return $this;
        }
        try {
            fwrite($this->socket, json_encode($data));
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
        return $this;
    }

    public function close()
    {
        fclose($this->socket);
    }

    public function __destruct()
    {
        fclose($this->socket);
    }
}