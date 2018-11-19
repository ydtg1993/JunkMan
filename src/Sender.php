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
                Filter::analyze($buffer);
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

class Filter extends Sender
{
    private static $at = '=>';
    private static $start_mark = 'TRACE START';
    private static $end_mark = 'TRACE END';

    const BOOLEAN = 'boolean';
    const INT = 'integer';
    const FLOAT = 'float';
    const STR = 'string';
    const ARRAY = 'array';
    const CLA = 'class';

    private static $array;
    private static $class;

    public static function analyze(&$content)
    {
        $content = trim($content);

        $flag = strpos($content, self::$start_mark);
        if ($flag === 0) {
            return $content = json_encode(['content_start' => $content]);
        }

        $flag = strpos($content, self::$end_mark);
        if ($flag === 0) {
            return $content = json_encode(['content_end' => $content]);
        }

        $flag = strpos($content, self::$at);
        if ($flag != 0) {
            return;
        }
        $content = substr($content, 3);

        if (DIRECTORY_SEPARATOR == '\\') {
            $pattern = '/(' . str_replace("\\", '\\\\', Defined::getTraceFile()) . '):(\d+)/';
        } else {
            $pattern = '/(' . Defined::getTraceFile() . '):(\d+)/';
        }
        $flag = preg_match($pattern, $content, $matches);
        if (!$flag) {
            return;
        }

        $line = isset($matches[2]) ? (int)$matches[2] : 0;
        $content = preg_replace($pattern,'',$content);

        $variable = self::analyzeVar($content);

        $content = json_encode([
            'variable'=>$variable,
            'line'=>$line
        ]);
    }

    private static function analyzeVar($content)
    {
        $pattern = '/^(\$.*)\s\=\s(.*?)$/U';
        preg_match($pattern,$content,$matches);

        if(empty($matches)){
            return trim($content);
        }

        $value = self::parseVal(trim($matches[2]));

        return [
            'var' => trim($matches[1]),
            'val' => $value['val'],
            'type' => $value['type'],
        ];
    }

    private static function parseVal($data)
    {
        $flag = strpos('\'',$data);
        if($flag === 0){
            return [
              'val'=>$data,
              'type'=>self::STR
            ];
        }

        if($data === 'TRUE' || $data === 'FALSE'){
            return [
                'val'=>$data,
                'type'=>self::BOOLEAN
            ];
        }

        $flag = preg_match('/^\d+$/',$data);
        if($flag){
            return [
                'val'=>$data,
                'type'=>self::INT
            ];
        }

        $flag = preg_match('/^\d+\.\d+$/',$data);
        if($flag){
            return [
                'val'=>$data,
                'type'=>self::FLOAT
            ];
        }

        $flag = preg_match('/array\s\((.*)\)$/',$data);
        if($flag){
            $result = self::parseArray($data);
            if($result === true){
                return self::$array;
            }
        }
    }

    private static function parseArray($data)
    {
        $flag = preg_match('/array\s\((.*)\)$/',$data,$matches);
        if($flag){
            self::$array[] = $matches[1];
        }
    }
}