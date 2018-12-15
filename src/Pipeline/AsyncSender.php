<?php
require_once __DIR__.'/../../autoload.php';

$params = getopt('file:header:config');

$collector = new \JunkMan\Container\Collector();

$header = \JunkMan\Instrument\Helper::parseSecret($params['header']);
$config = \JunkMan\Instrument\Helper::parseSecret($params['config']);

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
