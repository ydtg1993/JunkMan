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
use JunkMan\Driver\SpotDriver;
use JunkMan\E\OperateException;
use JunkMan\Instrument\Helper;

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

    public function dot($title = '',$content = '')
    {
        try {
            $this->collector = new Collector();
            $this->collector->setMessage($content);

            $trace_file_info = Helper::multiQuery2Array(debug_backtrace(), ['function' => 'dot', 'class' => get_class()]);
            Labour::run($this->collector,$title,$trace_file_info,Collector::TRACE_SPOT);
            Labour::stop();

            (new SpotDriver())->execute($this->collector);
        }catch (\Exception $e){
            throw new OperateException($e->getMessage());
        }
    }
}