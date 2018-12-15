<?php
/**
 * Created by PhpStorm.
 * User: Hikki
 * Date: 2018/11/22 0022
 * Time: 上午 11:57
 */

namespace JunkMan\Resolver;

/**
 * Class StreamAnalyze
 * @package JunkMan\Resolver
 */
class StreamAnalyze extends Analyze
{
    private static $traceFile;

    /**
     * @param mixed $traceFile
     */
    public static function setTraceFile($traceFile)
    {
        self::$traceFile = $traceFile;
    }

    /**
     * @param $content
     * @return string|void
     */
    public static function index($content)
    {
        $content = trim($content);

        $flag = strpos($content, Analyze::start_mark);
        if ($flag === 0) {
            preg_match("/\[(.*)\]/", $content, $matches);
            return ['content_start' => $matches[1]];
        }

        $flag = strpos($content, Analyze::end_mark);
        if ($flag === 0) {
            preg_match("/\[(.*)\]/", $content, $matches);
            return ['content_end' => $matches[1]];
        }

        $flag = strpos($content, Analyze::at);
        if ($flag != 0) {
            return;
        }
        $content = substr($content, 3);

        if (strtolower(PHP_OS) == 'linux') {
            $pattern = '/(' . str_replace("/", "\/", self::$traceFile) . '):(\d+)/';
        } else {
            $pattern = '/(' . str_replace("\\", '\\\\', self::$traceFile) . '):(\d+)/';
        }
        $flag = preg_match($pattern, $content, $matches);
        if (!$flag) {
            return;
        }

        $line = isset($matches[2]) ? (int)$matches[2] : 0;
        $content = preg_replace($pattern, '', $content);

        $variable = self::analyzeVar($content);

        return [
            'variable' => $variable,
            'line' => $line
        ];
    }

    /**
     * @param $content
     * @return array|string
     */
    private static function analyzeVar($content)
    {
        $pattern = '/^(\$.*)\s\=\s(.*?)$/U';
        preg_match($pattern, $content, $matches);

        if (empty($matches)) {
            return trim($content);
        }

        $value = self::parseVal(trim($matches[2]));

        return [
            'var' => trim($matches[1]),
            'val' => $value['val'],
            'type' => $value['type'],
        ];
    }

    /**
     * @param $data
     * @return array
     */
    private static function parseVal($data)
    {
        if ($data === 'TRUE' || $data === 'FALSE') {
            return [
                'val' => $data,
                'type' => self::BOOLEAN
            ];
        }

        $flag = preg_match('/^\d+$/', $data);
        if ($flag) {
            return [
                'val' => $data,
                'type' => self::INT
            ];
        }

        $flag = preg_match('/^\d+\.\d+$/', $data);
        if ($flag) {
            return [
                'val' => $data,
                'type' => self::FLOAT
            ];
        }

        $flag = preg_match('/^\'(.*)\'$/', $data);
        if ($flag) {
            return [
                'val' => $data,
                'type' => self::STR
            ];
        }

        $flag = preg_match('/^array/', $data);
        if ($flag) {
            $paragraph = "\$data = json_encode($data);";
            eval($paragraph);

            return [
                'val' => $data === true ? [] : json_decode($data,true),
                'type' => self::ARRAY
            ];
        }

        $flag = preg_match('/^class\s(.*)\s{(.*)}$/U', $data, $matches);
        if ($flag) {
            return [
                'val' => isset($matches[2]) && $matches[2] ? '{' . $matches[2] . '}' : '{}',
                'type' => isset($matches[1]) && $matches[1] ? $matches[1] : self::CLA
            ];
        }

        return [
            'val' => $data,
            'type' => self::UNDEFINED
        ];
    }
}