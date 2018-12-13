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
    const PASSPORT_CODE = "YOUR APP CODE";

    /**
     * only for linux
     * stream log async exec
     */
    const ASYNC = false;

    /**
     * only for linux
     * php path
     */
    const PHP = PHP_BINDIR.'/php';

    /**
     * @var OperateStream
     */
    private static $STEAM;

    /**
     * @var OperateFlush
     */
    private static $FLUSH;

    public static function stream()
    {
        self::$STEAM = OperateStream::getInstance();
        return self::$STEAM;
    }

    public static function flush()
    {
        self::$FLUSH = OperateFlush::getInstance();
        return self::$FLUSH;
    }

    public static function spot()
    {
        return new OperateSpot();
    }
}