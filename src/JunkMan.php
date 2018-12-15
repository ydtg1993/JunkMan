<?php
/**
 * Created by PhpStorm.
 * User: Hikki
 * Date: 2018/11/22 0022
 * Time: 下午 2:27
 */

namespace JunkMan;

use JunkMan\Operation\OperateFlush;
use JunkMan\Operation\OperateSpot;
use JunkMan\Operation\OperateStream;

class JunkMan
{
    /**
     * project root path
     */
    const ROOT_PATH = __DIR__;

    /**
     * service app code
     */
    const PASSPORT_CODE = "YOUR_APP_CODE";

    /**
     * only for linux
     * stream log async exec
     */
    const ASYNC = true;

    /**
     * only for linux
     * php path
     */
    const PHP = 'php';

    /**
     * @var OperateStream
     */
    private static $STEAM;

    /**
     * @var OperateFlush
     */
    private static $FLUSH;

    /**
     * trace the code bloke.collect the GC stream
     *
     * @return OperateStream
     */
    public static function stream()
    {
        self::$STEAM = OperateStream::getInstance();
        return self::$STEAM;
    }

    /**
     * trace the code bloke.if your task executes too much time.
     * flush the stream of the trace block.
     *
     * @return OperateFlush
     */
    public static function flush()
    {
        self::$FLUSH = OperateFlush::getInstance();
        return self::$FLUSH;
    }

    /**
     * collect the variable
     *
     * @return OperateSpot
     */
    public static function spot()
    {
        return new OperateSpot();
    }
}