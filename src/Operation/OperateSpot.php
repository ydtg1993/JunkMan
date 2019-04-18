<?php
/**
 * Created by PhpStorm.
 * User: Hikki
 * Date: 2018/11/22 0022
 * Time: 上午 11:05
 */

namespace JunkMan\Operation;

use JunkMan\Configuration\Labour;
use JunkMan\Container\Collector;
use JunkMan\Instrument\Helper;
use JunkMan\Instrument\Io;
use JunkMan\Resolver\SpotAnalyze;

/**
 * Class OperateSpot
 * @package JunkMan\Operation
 */
class OperateSpot
{
    /**
     * @var Collector
     */
    private $collector = null;

    /**
     * @param string $title
     * @param null $content
     * @return string
     */
    public function dot($title = '', $content = null)
    {
        try {
            $this->collector = new Collector();
            $trace_file_info = Helper::multiQuery2Array(debug_backtrace(), ['function' => 'dot', 'class' => get_class()]);

            Labour::run($this->collector, $title, $trace_file_info, Collector::TRACE_SPOT);

            $lineContent = Io::readLine($trace_file_info['file'],$trace_file_info['line']);
            preg_match("/dot\(.*,(.*)\)/U",$lineContent,$match);
            $var = isset($match[1]) ? $match[1] : 'variable';
            SpotAnalyze::setVar($var);
            SpotAnalyze::setLine($this->collector->getTraceStart());
            $this->collector->setExtend(SpotAnalyze::index($content));
            $this->collector->setStatus(Collector::STATUS_END);
            Labour::stop();

            $this->collector->getSpeaker()->write($this->collector->message);
        } catch (\Exception $e) {
            return $e->getFile();
        }
        return '';
    }
}