<?php
require_once __DIR__ . '/../../autoload.php';

$params = getopt('h:c:s:t:');

$collector = new \JunkMan\Container\Collector();

$header = \JunkMan\Instrument\Helper::parseSecret($params['h']);
$config = \JunkMan\Instrument\Helper::parseSecret($params['c']);
$trace_start = (int)$params['s'];
$trace_end = (int)$params['t'];

$path = \JunkMan\JunkMan::ROOT_PATH . DIRECTORY_SEPARATOR . 'Temp';
$file = $path . DIRECTORY_SEPARATOR . $header['secret'] . \JunkMan\Container\Collector::STREAM_SUFFIX;

try {
    if (!is_file($file)) {
        throw new \Exception('not found stream file');
    }

    $collector->setSENDER();
    $sender = $collector->getSENDER();
    $sender->write($header);

    $handle = fopen($file, "r");
    if ($handle) {
        \JunkMan\Resolver\StreamAnalyze::setTraceFile($collector->getTraceFile());

        $trace_file = $header['trace_file'];
        $trace_file_content = "";
        if (is_file($trace_file)) {
            $trace_file_content = \JunkMan\Instrument\Io::cutFile(
                $trace_file,
                $trace_start - \JunkMan\Container\Collector::SIDE_LINE,
                $trace_end + \JunkMan\Container\Collector::SIDE_LINE);
        }
        $sender->write(['trace_file_content' => $trace_file_content]);


        $handle = fopen($file, "r");
        while (!feof($handle)) {
            $data = \JunkMan\Resolver\StreamAnalyze::index(fgets($handle));
            $sender->write($data);
        }
        fclose($handle);
    }
} catch (\Exception $e) {
    throw new\Exception($e->getMessage());
} finally {
    if (is_file($file)) {
        @unlink($file);
    }
}
