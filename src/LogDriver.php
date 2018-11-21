<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/21 0021
 * Time: 下午 4:34
 */

class LogDriver
{
    private static $SENDER = null;
    private static $trace_file;
    private static $trace_line;

    public static function execute($message, $title)
    {
        if (!function_exists('xdebug_set_filter')) {
            throw new \Exception('Need to install Xdebug version >= 2.6');
        }

        try {
            $time = time();
            $call_func_data = Helper::multiQuery2Array(debug_backtrace(), ['function' => 'log', 'class' => 'Stream']);
            self::$trace_file = $call_func_data['file'];
            self::$trace_line = $call_func_data['line'];

            $config = file_get_contents(Stream::ROOT_PATH . DIRECTORY_SEPARATOR . 'config.json');
            $config = (array)json_decode($config, true);

            $app_code = isset($config['app_code']) ? $config['app_code'] : '';
            $config_str = $app_code . '@' . $time;
            $secret = bin2hex($config_str);
            $head = json_encode([
                'header' => [
                    'log_title' => $title,
                    'time' => $time,
                    'secret' => $secret,
                    'trace_file' => self::$trace_file
                ]
            ]);

            self::$SENDER = new Sender(Defined::SERVER, Defined::PORT);
            $trace_file = json_encode([
                'trace_file' => Helper::cutFile(self::$trace_file,self::$trace_line - 5, self::$trace_line + 5)
            ]);
            self::$SENDER->setHead($head)->write($trace_file)->write(self::parseData($message));


        } catch (Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    private static function parseData($data)
    {
        $type = '';
        if (is_string($data)) {
            $type = Analyze::STR;
        }

        if (is_int($data)) {
            $type = Analyze::INT;
        }

        if (is_float($data)) {
            $type = Analyze::FLOAT;
        }

        if (is_array($data)) {
            $type = Analyze::ARRAY;
        }

        if (is_object($data)) {
            $type = get_class($data);
        }

        return json_encode([
            'variable' => [
                'val' => $data,
                'type' => $type
            ],
            'line'=>self::$trace_line
        ]);
    }
}