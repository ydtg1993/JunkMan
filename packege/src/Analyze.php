<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/20 0020
 * Time: 上午 10:08
 */

class Analyze
{
    private static $at = '=>';
    private static $start_mark = 'TRACE START';
    private static $end_mark = 'TRACE END';

    const BOOLEAN = 'boolean';
    const INT = 'integer';
    const FLOAT = 'float';
    const STR = 'string';
    const ARRAY = 'array';
    const CLA = 'stdClass';

    /**
     * @param $content
     * @return string|void
     */
    public static function index($content)
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
        $content = preg_replace($pattern, '', $content);

        $variable = self::analyzeVar($content);

        return json_encode([
            'variable' => $variable,
            'line' => $line
        ]);
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
            $file = Defined::getTemp() . microtime() . '_temp.php';
            file_put_contents($file, "<?php return " . $data . ';');
            $data = include_once $file;
            @unlink($file);

            return [
                'val' => $data === true ? [] : $data,
                'type' => self::ARRAY
            ];
        }

        $flag = preg_match('/^class\s(.*)\s{(.*)}$/U', $data,$matches);
        if($flag) {
            return [
                'val' => isset($matches[2]) && $matches[2] ? '{'.$matches[2].'}' : '{}',
                'type' => isset($matches[1]) && $matches[1] ? $matches[1] : self::CLA
            ];
        }
    }

}