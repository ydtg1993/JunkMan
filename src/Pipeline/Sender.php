<?php
/**
 * Created by PhpStorm.
 * User: Hikki
 * Date: 2018/8/20
 * Time: 23:08
 */
namespace JunkMan\Pipeline;

use JunkMan\Abstracts\Singleton;
use JunkMan\E\IoException;

/**
 * Class TcpSender
 * @package JunkMan\Pipeline
 */
class Sender extends Singleton implements PipelineInterface
{
    private $ip;
    private $port;
    private $socket;

    /**
     * @param null $data
     * @throws IoException
     */
    public function execute($data = null)
    {
        $this->ip = $data['server'];
        $this->port = $data['port'];

        $create_errno = '';
        $create_errstr = '';
        $address = 'tcp://' . $this->ip . ':' . $this->port;
        try {
            $this->socket = stream_socket_client($address, $create_errno, $create_errstr, STREAM_SERVER_BIND);
        }catch (\Exception $e){
            throw new IoException($create_errno.$e->getMessage());
        }
    }

    /**
     * @param $data
     * @return $this
     * @throws IoException
     */
    public function write($data)
    {
        if(!$data){
            return $this;
        }
        try {
            fwrite($this->socket, json_encode($data) . PHP_EOL);
        }catch (\Exception $e){
            throw new IoException($e->getMessage());
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